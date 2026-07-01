<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Box;
use App\Models\Retensi;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DokumenController extends Controller
{
    /**
     * Query dokumen dengan filter (box, tahun retensi, status/kondisi retensi).
     */
    private function filteredDokumens(Request $request)
    {
        $query = Dokumen::with('box.rak', 'retensi');

        if ($request->filled('box_id')) {
            $query->where('box_id', $request->box_id);
        }

        if ($request->filled('tahun_retensi')) {
            $tahun = $request->tahun_retensi;
            $query->whereHas('retensi', function ($q) use ($tahun) {
                $q->whereYear('tgl_kadaluarsa', $tahun);
            });
        }

        if ($request->filled('status_retensi')) {
            $status = $request->status_retensi;
            $query->whereHas('retensi', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        return $query->latest()->get();
    }

    /**
     * Tampilkan daftar dokumen (Manajemen Dokumen) beserta filter.
     */
    public function index(Request $request)
    {
        $dokumens = $this->filteredDokumens($request);
        $boxes = Box::with('rak')->latest()->get();

        return view('dokumen.index', compact('dokumens', 'boxes'));
    }

    /**
     * Tampilkan form input dokumen baru.
     */
    public function create()
    {
        $boxes = Box::with('rak')->latest()->get();
        return view('dokumen.create', compact('boxes'));
    }

    /**
     * Simpan dokumen baru dan buat relasi jadwal retensi secara otomatis.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_surat'      => 'nullable|string|max:255',
            'nama_dokumen'  => 'required|string|max:255',
            'kategori'      => 'required|string',
            'box_id'        => 'required|exists:boxes,id',
            'jenis'         => 'required|in:fisik,digital',
            'tgl_masuk'     => 'required|date',
            'masa_simpan'   => 'required', // Dropdown Masa Simpan (Tahun) dari Form
            'file'          => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
            'keterangan'    => 'nullable|string',
        ]);

        $dokumen = new Dokumen();
        $dokumen->no_surat = $request->no_surat;
        $dokumen->nama_dokumen = $request->nama_dokumen;
        $dokumen->kategori = $request->kategori;
        $dokumen->box_id = $request->box_id;
        $dokumen->jenis = $request->jenis;
        $dokumen->tgl_masuk = $request->tgl_masuk;
        $dokumen->keterangan = $request->keterangan;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dokumens', 'public');
            $dokumen->file = $path;
        }

        $dokumen->save();

        // SIMPAN RELASI RETENSI OTOMATIS (Mencegah Error Carbon & Masuk ke Halaman Siap Musnah)
        $retensi = new Retensi();
        $retensi->dokumen_id = $dokumen->id;
        
        // Hitung tgl_kadaluarsa: tgl_masuk + (int) masa_simpan
        $calculatedDate = Carbon::parse($request->tgl_masuk)->addYears((int)$request->masa_simpan);
        $retensi->tgl_kadaluarsa = $calculatedDate;
        $retensi->ket_retensi = "Retensi dokumen selama " . $request->masa_simpan . " tahun terhitung sejak tanggal masuk.";
        
        // Cek langsung status retensinya berdasarkan tanggal hari ini
        $retensi->status = $calculatedDate->isPast() ? 'kadaluarsa' : 'aktif';
        $retensi->save();

        return redirect()->route('dokumen.index')->with('success', 'Dokumen dan Jadwal Retensi Berhasil Disimpan.');
    }

    /**
     * Tampilkan detail dokumen (Internal Admin).
     */
    public function show($id)
    {
        $dokumen = Dokumen::with('box.rak', 'retensi')->findOrFail($id);
        return view('dokumen.show', compact('dokumen'));
    }

    /**
     * Tampilkan form edit data dokumen.
     */
    public function edit($id)
    {
        $dokumen = Dokumen::with('retensi')->findOrFail($id);
        $boxes = Box::with('rak')->latest()->get();
        
        return view('dokumen.edit', compact('dokumen', 'boxes'));
    }

    /**
     * Perbarui data dokumen dan hitung ulang jadwal retensinya di DB.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_surat'      => 'nullable|string|max:255',
            'nama_dokumen'  => 'required|string|max:255',
            'kategori'      => 'required|string',
            'box_id'        => 'required|exists:boxes,id',
            'jenis'         => 'required|in:fisik,digital',
            'tgl_masuk'     => 'required|date',
            'masa_simpan'   => 'required', // Input Masa Simpan Baru
            'file'          => 'nullable|file|mimes:pdf|max:10240',
            'keterangan'    => 'nullable|string',
        ]);

        $dokumen = Dokumen::findOrFail($id);
        $dokumen->no_surat = $request->no_surat;
        $dokumen->nama_dokumen = $request->nama_dokumen;
        $dokumen->kategori = $request->kategori;
        $dokumen->box_id = $request->box_id;
        $dokumen->jenis = $request->jenis;
        $dokumen->tgl_masuk = $request->tgl_masuk;
        $dokumen->keterangan = $request->keterangan;

        if ($request->hasFile('file')) {
            if ($dokumen->file) {
                Storage::disk('public')->delete($dokumen->file);
            }
            $path = $request->file('file')->store('dokumens', 'public');
            $dokumen->file = $path;
        }

        $dokumen->save();

        // UPDATE DATA RETENSI DINAMIS TERHUBUNG
        $retensi = Retensi::firstOrNew(['dokumen_id' => $dokumen->id]);
        
        $calculatedDate = Carbon::parse($request->tgl_masuk)->addYears((int)$request->masa_simpan);
        $retensi->tgl_kadaluarsa = $calculatedDate;
        $retensi->ket_retensi = "Retensi dokumen diperbarui menjadi " . $request->masa_simpan . " tahun.";
        $retensi->status = $calculatedDate->isPast() ? 'kadaluarsa' : 'aktif';
        
        $retensi->save();

        return redirect()->route('dokumen.index')->with('success', 'Data Dokumen Berhasil Diperbarui.');
    }

    /**
     * Hapus dokumen beserta file salinan digital dan data retensinya.
     */
    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if ($dokumen->file) {
            Storage::disk('public')->delete($dokumen->file);
        }

        // Hapus otomatis data retensinya agar database tetap bersih
        Retensi::where('dokumen_id', $dokumen->id)->delete();
        $dokumen->delete();

        return redirect()->route('dokumen.index')->with('success', 'Dokumen Berhasil Dihapus.');
    }

    /**
     * Fitur Pencarian Dinamis (Search AJAX/Form).
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $dokumens = Dokumen::with('box.rak', 'retensi')
            ->where(function ($query) use ($search) {
                $query->where('nama_dokumen', 'LIKE', "%{$search}%")
                      ->orWhere('no_surat', 'LIKE', "%{$search}%")
                      ->orWhere('kategori', 'LIKE', "%{$search}%")
                      ->orWhere('keterangan', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();

        $boxes = Box::with('rak')->latest()->get();

        return view('dokumen.index', compact('dokumens', 'boxes'));
    }

    /**
     * Cetak Laporan PDF Data Dokumen Internal TI.
     */
    public function exportPdf(Request $request)
    {
        $dokumens = $this->filteredDokumens($request);
        $pdf = Pdf::loadView('dokumen.pdf', compact('dokumens'))->setPaper('a4', 'landscape');
        
        return $pdf->stream('laporan-arsip-dokumen-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Cetak Laporan Excel Data Dokumen Internal TI.
     */
    public function exportExcel(Request $request)
    {
        $dokumens = $this->filteredDokumens($request);

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=laporan-arsip-dokumen-" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'No Surat', 'Nama Dokumen', 'Kategori', 'Kode Box', 'Kode Rak', 'Jenis', 'Tanggal Masuk', 'Status Retensi', 'Tgl Kadaluarsa'];

        $callback = function() use($dokumens, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($dokumens as $i => $dok) {
                fputcsv($file, [\
                    $i + 1,\
                    $dok->no_surat ?? '-',\
                    $dok->nama_dokumen,\
                    $dok->kategori,\
                    $dok->box->kode_box ?? '-',\
                    $dok->box->rak->kode_rak ?? '-',\
                    ucfirst($dok->jenis),\
                    $dok->tgl_masuk,\
                    $dok->retensi->status ?? '-',\
                    $dok->retensi->tgl_kadaluarsa ?? '-',\
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * =========================================================================
     * GRUP JALUR PUBLIK: KHUSUS HP SCAN QR CODE BYPASS LOGIN SATPAM
     * =========================================================================
     */
    public function showPublic($id)
    {
        // Mengambil data dokumen beserta relasi box dan rak-nya sesuai ERD
        $dokumen = Dokumen::with('box.rak', 'retensi')->findOrFail($id);
        
        // Melempar data ke halaman verifikasi publik tanpa sidebar admin
        return view('dokumen.public_show', compact('dokumen'));
    }
}