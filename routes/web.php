<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\RetensiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;

// Halaman Awal Pengalihan Ke Halaman Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi Pengguna (Login & Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get'); // fallback GET logout

// Grouping Hak Akses Khusus Admin IT Bank Sumut
Route::middleware('auth.admin')->group(function () {

    // Dashboard Utama
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Manajemen Infrastruktur Fisik Gudang (Rak & Box)
    Route::resource('rak', RakController::class);
    Route::resource('box', BoxController::class);

    // Pengelolaan Dokumen & Pelaporan Ekspor Data
    Route::get('/dokumen/export-pdf', [DokumenController::class, 'exportPdf'])->name('dokumen.export.pdf');
    Route::get('/dokumen/export-excel', [DokumenController::class, 'exportExcel'])->name('dokumen.export.excel');
    Route::get('/dokumen-search', [DokumenController::class, 'search'])->name('dokumen.search');
    Route::resource('dokumen', DokumenController::class)->parameters([
        'dokumen' => 'dokumen'
    ]);

    // Alur Kerja Siklus Retensi & Pemusnahan Terpisah yang Saling Terhubung
    Route::get('/retensi/riwayat', [RetensiController::class, 'riwayatPemusnahan'])->name('retensi.riwayat');
    Route::get('/retensi-cek', [RetensiController::class, 'cekRetensi'])->name('retensi.cek');
    Route::delete('/retensi-musnahkan/{id}', [RetensiController::class, 'musnahkan'])->name('retensi.musnahkan');
    Route::resource('retensi', RetensiController::class);

    // Manajemen Pusat Pemberitahuan Peringatan
    Route::resource('notifikasi', NotifikasiController::class);
    Route::post('/notifikasi-baca/{notifikasi}', [NotifikasiController::class, 'tandaiBaca'])->name('notifikasi.baca');
    Route::post('/notifikasi-baca-semua', [NotifikasiController::class, 'tandaiSemuaBaca'])->name('notifikasi.baca.semua');

    // Otoritas Keamanan Pengguna Aplikasi (Manajemen User)
    // Index, edit & update bisa diakses admin & superadmin
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::patch('/user/{user}', [UserController::class, 'update']);

    // Tambah & hapus admin: khusus superadmin
    Route::middleware('superadmin')->group(function () {
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    });
});