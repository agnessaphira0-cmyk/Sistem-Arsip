<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::latest()->get();
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function tandaiBaca(Notifikasi $notifikasi)
    {
        $notifikasi->update([
            'status_baca' => true
        ]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca!');
    }

    public function tandaiSemuaBaca()
    {
        Notifikasi::where('status_baca', false)
            ->update([
                'status_baca' => true
            ]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca!');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus!');
    }
}