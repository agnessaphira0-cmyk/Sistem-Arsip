<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Box;
use App\Models\Retensi;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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
     * Menampilkan daftar semua dokumen arsip.
     */
    public function index(Request $request)
    {
        $dokumens = $this->filteredDokumens($request);
        $boxes = Box::with('rak')->get();

        $tahunList = Retensi::selectRaw('YEAR(tgl_kadaluarsa) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('dokumen.index', compact('dokumens', 'boxes', 'tahunList'));
    }

    /**
     * Menampilkan halaman form tambah dokumen baru.
     */
    public function create()
    {
        $boxes = Box::with('rak')->get();
        return view('dokumen.create', compact('boxes'));
    }

    /**
     * Menyimpan data dokumen baru beserta relasi retensi dan file PDF.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_surat'       => 'required|string|max:100',
            'nama_dokumen'   => 'required|string|max:255',
            'kategori'       => 'required|string|max:100',
            'box_id'         => 'required|exists:boxes,id',
            'tgl_masuk'      => 'required|date',
            'jenis'          => 'required|in:fisik,digital',
            'file_path'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tgl_kadaluarsa' => 'required|date|after:tgl_masuk',
        ]);

        // Proses upload file digital jika melampirkan berkas
        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('dokumens', 'public');
        }

        // Simpan data utama dokumen ke database
        $dokumen = Dokumen::create([
            'no_surat'     => $request->no_surat,
            'nama_dokumen' => $request->nama_dokumen,
            'kategori'     => $request->kategori,
            'box_id'       => $request->box_id,
            'tgl_masuk'    => $request->tgl_masuk,
            'jenis'        => $request->jenis,
            'file_path'    => $filePath,
            'keterangan'   => $request->keterangan,
        ]);

        // Otomatis membuat baris aturan retensi pelacakan masa aktif berkas
        Retensi::create([
            'dokumen_id'     => $dokumen->id,
            'tgl_mulai'      => $request->tgl_masuk,
            'tgl_kadaluarsa' => $request->tgl_kadaluarsa,
            'status'         => 'aktif',
            'ket_retensi'    => $request->ket_retensi,
        ]);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil disimpan!');
    }

    /**
     * Menampilkan informasi detail sebuah dokumen beserta QR Code.
     */
    public function show($id)
    {
        $dokumen = Dokumen::with(['box.rak', 'retensi'])->findOrFail($id);
        return view('dokumen.show', compact('dokumen'));
    }

    /**
     * Menampilkan halaman form edit data dokumen.
     */
    public function edit(Dokumen $dokumen)
    {
        $boxes = Box::with('rak')->get();
        return view('dokumen.edit', compact('dokumen', 'boxes'));
    }

    /**
     * Memperbarui data dokumen dan mengganti file lama di storage.
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        $request->validate([
            'no_surat'       => 'required|string|max:100',
            'nama_dokumen'   => 'required|string|max:255',
            'kategori'       => 'required|string|max:100',
            'box_id'         => 'required|exists:boxes,id',
            'tgl_masuk'      => 'required|date',
            'jenis'          => 'required|in:fisik,digital',
            'file_path'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tgl_kadaluarsa' => 'required|date',
        ]);

        $filePath = $dokumen->file_path;

        // Jika ada unggahan file baru, hapus file fisik lama agar storage tidak penuh
        if ($request->hasFile('file_path')) {
            if ($dokumen->file_path) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            $filePath = $request->file('file_path')->store('dokumens', 'public');
        }

        // Update data tabel dokumen
        $dokumen->update([
            'no_surat'     => $request->no_surat,
            'nama_dokumen' => $request->nama_dokumen,
            'kategori'     => $request->kategori,
            'box_id'       => $request->box_id,
            'tgl_masuk'    => $request->tgl_masuk,
            'jenis'        => $request->jenis,
            'file_path'    => $filePath,
            'keterangan'   => $request->keterangan,
        ]);

        // Sinkronisasi pembaruan aturan retensi berkas
        if ($dokumen->retensi) {
            $dokumen->retensi->update([
                'tgl_mulai'      => $request->tgl_masuk,
                'tgl_kadaluarsa' => $request->tgl_kadaluarsa,
                'ket_retensi'    => $request->ket_retensi,
            ]);
        }

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diupdate!');
    }

    /**
     * Menghapus dokumen dari database beserta file fisiknya di storage.
     */
    public function destroy(Dokumen $dokumen)
    {
        if ($dokumen->file_path) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        $dokumen->delete();
        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus!');
    }

    /**
     * Fitur Pencarian Dokumen (Aman dari Bug & Tergrup Sempurna).
     */
    public function search(Request $request)
    {
        // Mengamankan kata kunci pencarian jika bernilai null / kosong
        $keyword = $request->input('keyword', '');

        // Menjalankan pencarian logis multi-kolom yang aman menggunakan Query Grouping
        $dokumens = Dokumen::with('box.rak')
            ->where(function($query) use ($keyword) {
                $query->where('nama_dokumen', 'like', "%{$keyword}%")
                      ->orWhere('no_surat', 'like', "%{$keyword}%")
                      ->orWhere('kategori', 'like', "%{$keyword}%");
            })
            ->latest()
            ->get();

        return view('dokumen.search', compact('dokumens', 'keyword'));
    }

    /**
     * Cetak Laporan Arsip ke format PDF (mendukung filter box, tahun retensi, status).
     */
    public function exportPdf(Request $request)
    {
        $dokumens = $this->filteredDokumens($request);
        $pdf = Pdf::loadView('dokumen.pdf', compact('dokumens'));
        return $pdf->download('Laporan_Arsip_Dokumen_Bank_Sumut.pdf');
    }

    /**
     * Cetak Laporan Arsip ke format CSV/Excel (mendukung filter box, tahun retensi, status).
     */
    public function exportExcel(Request $request)
    {
        $dokumens = $this->filteredDokumens($request);
        $filename = "Laporan_Arsip_Dokumen_Bank_Sumut.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'No Surat', 'Nama Dokumen', 'Kategori', 'Kode Box', 'Kode Rak', 'Jenis', 'Tanggal Masuk', 'Status Retensi', 'Tgl Kadaluarsa'];

        $callback = function() use($dokumens, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($dokumens as $i => $dok) {
                fputcsv($file, [
                    $i + 1,
                    $dok->no_surat ?? '-',
                    $dok->nama_dokumen,
                    $dok->kategori,
                    $dok->box->kode_box ?? '-',
                    $dok->box->rak->kode_rak ?? '-',
                    ucfirst($dok->jenis),
                    $dok->tgl_masuk,
                    $dok->retensi->status ?? '-',
                    $dok->retensi->tgl_kadaluarsa ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}