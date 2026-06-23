<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retensi;
use App\Models\Dokumen;
use App\Models\Pemusnahan;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RetensiController extends Controller
{
    /**
     * Halaman Retensi
     */
    public function index()
    {
        $retensis = Retensi::with('dokumen.box.rak')
            ->latest()
            ->get();

        return view('retensi.index', compact('retensis'));
    }

    /**
     * Cek Retensi Otomatis
     */
    public function cekRetensi()
    {
        $today = Carbon::today();

        $retensis = Retensi::with('dokumen')->get();

        $jumlahNotif = 0;

        // Gunakan admin login, jika tidak ada gunakan id 1
        $adminId = auth()->id() ?? 1;

        foreach ($retensis as $retensi) {

            if (!$retensi->dokumen) {
                continue;
            }

            $tglKadaluarsa = Carbon::parse($retensi->tgl_kadaluarsa);
            $selisihHari = $today->diffInDays($tglKadaluarsa, false);

            /*
            ========================================
            SUDAH KADALUARSA
            ========================================
            */
            if ($selisihHari < 0) {

                if ($retensi->status != 'kadaluarsa') {

                    $retensi->update([
                        'status' => 'kadaluarsa'
                    ]);
                }

                $pesan = "Dokumen dengan No Surat {$retensi->dokumen->no_surat} telah melewati masa retensi dan siap dimusnahkan.";

                $notif = Notifikasi::firstOrCreate(
                    [
                        'admin_id' => $adminId,
                        'pesan' => $pesan,
                    ],
                    [
                        'status_baca' => false,
                        'tgl_notif' => now(),
                    ]
                );

                if ($notif->wasRecentlyCreated) {
                    $jumlahNotif++;
                }
            }

            /*
            ========================================
            AKAN KADALUARSA (30 HARI)
            ========================================
            */
            elseif ($selisihHari <= 30) {

                if ($retensi->status != 'akan_kadaluarsa') {

                    $retensi->update([
                        'status' => 'akan_kadaluarsa'
                    ]);
                }

                $pesan = "Dokumen dengan No Surat {$retensi->dokumen->no_surat} akan kadaluarsa dalam {$selisihHari} hari.";

                $notif = Notifikasi::firstOrCreate(
                    [
                        'admin_id' => $adminId,
                        'pesan' => $pesan,
                    ],
                    [
                        'status_baca' => false,
                        'tgl_notif' => now(),
                    ]
                );

                if ($notif->wasRecentlyCreated) {
                    $jumlahNotif++;
                }
            }

            /*
            ========================================
            MASIH AKTIF
            ========================================
            */
            else {

                if ($retensi->status != 'aktif') {

                    $retensi->update([
                        'status' => 'aktif'
                    ]);
                }
            }
        }

        return redirect()
            ->route('retensi.index')
            ->with(
                'success',
                "Pengecekan retensi selesai. {$jumlahNotif} notifikasi baru berhasil dibuat."
            );
    }

    /**
     * Pemusnahan Dokumen
     */
    public function musnahkan($id)
    {
        $dokumen = Dokumen::with('box.rak')->findOrFail($id);

        $eksekutor = auth()->user()->name ?? 'Admin';

        Pemusnahan::create([
            'no_surat' => $dokumen->no_surat,
            'nama_dokumen' => $dokumen->nama_dokumen,
            'kategori' => $dokumen->kategori,
            'kode_box_lama' => $dokumen->box->kode_box ?? '-',
            'kode_rak_lama' => $dokumen->box->rak->kode_rak ?? '-',
            'tanggal_pemusnahan' => now(),
            'eksekutor' => $eksekutor,
            'alasan' => 'Telah melewati masa retensi dokumen.',
        ]);

        if ($dokumen->file_path) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return redirect()
            ->route('retensi.riwayat')
            ->with('success', 'Dokumen berhasil dimusnahkan dan dicatat pada riwayat pemusnahan.');
    }

    /**
     * Riwayat Pemusnahan
     */
    public function riwayatPemusnahan()
    {
        $riwayats = Pemusnahan::latest()->get();

        return view('retensi.riwayat', compact('riwayats'));
    }
}