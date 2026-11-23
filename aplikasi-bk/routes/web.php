<?php

use App\Http\Controllers\Backend\BatasStatusController;
use App\Http\Controllers\Backend\GuruController;
use App\Http\Controllers\Backend\JenisPelanggaranController;
use App\Http\Controllers\Backend\KelasController;
use App\Http\Controllers\Backend\LaporanPelanggaranController;
use App\Http\Controllers\Backend\NotifikasiWaController;
use App\Http\Controllers\Backend\PelanggaranController;
use App\Http\Controllers\Backend\PembinaanController;
use App\Http\Controllers\Backend\SiswaController;
use App\Http\Controllers\Backend\TataTertibController;
use App\Http\Controllers\Backend\TindakLanjutController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    // 1. Menu Bersama (Dapat diakses oleh Admin, Guru BK, dan Siswa sesuai wewenang masing-masing)
    Volt::route('master/tata-tertib', 'pages.master.tata-tertib')->name('master.tata-tertib');
    Volt::route('pelanggaran', 'pages.pelanggaran.index')->name('pelanggaran.index');
    Volt::route('pembinaan', 'pages.pembinaan.index')->name('pembinaan.index');

    // 2. Operasional Pelanggaran (Khusus Guru BK & Admin, Siswa Dilarang / 403 Forbidden)
    Route::middleware('role:guru_bk|admin')->group(function () {
        Volt::route('pelanggaran/create', 'pages.pelanggaran.create')->name('pelanggaran.create');
    });

    // 3. Sesi Konseling & Pembinaan (Khusus Guru BK, Admin & Siswa Dilarang / 403 Forbidden)
    Route::middleware('role:guru_bk')->group(function () {
        Volt::route('pembinaan/create', 'pages.pembinaan.create')->name('pembinaan.create');
    });

    // 4. Panel Manajemen Sistem & Master Data (Khusus Administrator, Guru BK & Siswa Dilarang / 403 Forbidden)
    Route::middleware('role:admin')->group(function () {
        Volt::route('notifikasi-wa', 'pages.notifikasi-wa.index')->name('notifikasi-wa.index');
        Volt::route('master/siswa', 'pages.master.siswa')->name('master.siswa');
        Volt::route('laporan', 'pages.laporan.index')->name('laporan.index');
    });
});

Route::middleware('auth')->prefix('bk')->name('bk.')->group(function () {
    Route::get('batas-status', [BatasStatusController::class, 'index'])->name('batas-status.index');
    Route::put('batas-status', [BatasStatusController::class, 'update'])->name('batas-status.update');
    Route::get('laporan/pelanggaran/pdf', [LaporanPelanggaranController::class, 'pdf'])
        ->name('laporan.pelanggaran.pdf');
    Route::get('laporan/pelanggaran/excel', [LaporanPelanggaranController::class, 'excel'])
        ->name('laporan.pelanggaran.excel');
    Route::get('notifikasi-wa', [NotifikasiWaController::class, 'index'])->name('notifikasi-wa.index');
    Route::post('notifikasi-wa/{notifikasiWa}/kirim-ulang', [NotifikasiWaController::class, 'retry'])
        ->name('notifikasi-wa.retry');
    Route::apiResource('pengguna', UserController::class)->only(['index', 'store', 'update']);
    Route::apiResource('guru', GuruController::class);
    Route::apiResource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
    Route::apiResource('tata-tertib', TataTertibController::class)
        ->parameters(['tata-tertib' => 'tataTertib']);
    Route::apiResource('jenis-pelanggaran', JenisPelanggaranController::class)
        ->parameters(['jenis-pelanggaran' => 'jenisPelanggaran']);
    Route::apiResource('siswa', SiswaController::class);
    Route::apiResource('pelanggaran', PelanggaranController::class);
    Route::apiResource('pembinaan', PembinaanController::class)->except('destroy');
    Route::patch('tindak-lanjut/{tindakLanjut}', [TindakLanjutController::class, 'update'])
        ->name('tindak-lanjut.update');
    Route::post('pembinaan/{pembinaan}/tindak-lanjut', [PembinaanController::class, 'storeTindakLanjut'])
        ->name('pembinaan.tindak-lanjut.store');
});

require __DIR__.'/auth.php';
