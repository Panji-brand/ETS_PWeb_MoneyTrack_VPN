<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Transaksi;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/anggaran', [AnggaranController::class, 'index'])->name('anggaran.index');
    Route::post('/anggaran', [AnggaranController::class, 'store'])->name('anggaran.store');
    Route::get('/anggaran/{anggaran}/edit', [AnggaranController::class, 'edit'])->name('anggaran.edit');
    Route::put('/anggaran/{anggaran}', [AnggaranController::class, 'update'])->name('anggaran.update');
    Route::delete('/anggaran/{anggaran}', [AnggaranController::class, 'destroy'])->name('anggaran.destroy');

    Route::get('/laporan', function (Request $request) {
        $periode = $request->query('periode', 'semua');
        $jenis = $request->query('jenis', 'semua');
        if (!in_array($periode, ['semua', 'bulan_ini', 'bulan_lalu', 'tahun_ini', 'rentang'], true)) $periode = 'semua';
        if (!in_array($jenis, ['semua', 'pemasukan', 'pengeluaran'], true)) $jenis = 'semua';

        $query = Transaksi::query();
        if ($periode === 'bulan_ini') {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        } elseif ($periode === 'bulan_lalu') {
            $bulanLalu = now()->subMonth();
            $query->whereMonth('tanggal', $bulanLalu->month)->whereYear('tanggal', $bulanLalu->year);
        } elseif ($periode === 'tahun_ini') {
            $query->whereYear('tanggal', now()->year);
        } elseif ($periode === 'rentang') {
            $mulai = $request->query('tanggal_mulai');
            $akhir = $request->query('tanggal_akhir');
            if ($mulai && $akhir && $mulai <= $akhir) $query->whereBetween('tanggal', [$mulai, $akhir]);
            else $periode = 'semua';
        }

        $totalPemasukan = (clone $query)->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = (clone $query)->where('jenis', 'pengeluaran')->sum('nominal');
        if ($jenis !== 'semua') $query->where('jenis', $jenis);
        $transaksi = $query->with('kategori')->orderByDesc('tanggal')->orderByDesc('id')->get();

        return view('laporan.index', compact('totalPemasukan', 'totalPengeluaran', 'transaksi', 'periode'));
    })->name('laporan.index');

    Route::get('/pengaturan', function () { return view('pengaturan.index'); })->name('pengaturan.index');
});
