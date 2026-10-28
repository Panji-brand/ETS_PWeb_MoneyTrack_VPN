<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kategori;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $sekarang = Carbon::now();
        $bulanIni = $sekarang->month;
        $tahunIni = $sekarang->year;

        $totalPemasukan = Transaksi::where('jenis', 'pemasukan')
            ->sum('nominal');

        $totalPengeluaran = Transaksi::where('jenis', 'pengeluaran')
            ->sum('nominal');

        $totalSaldo = $totalPemasukan - $totalPengeluaran;

        $pemasukanBulanIni = Transaksi::where('jenis', 'pemasukan')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('nominal');

        $pengeluaranBulanIni = Transaksi::where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('nominal');

        $transaksiTerbaru = Transaksi::with('kategori')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $pengeluaranKategori = Transaksi::with('kategori')
            ->where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->get()
            ->groupBy(function ($transaksi) {
                return $transaksi->kategori->nama
                    ?? 'Tanpa Kategori';
            })
            ->map(function ($transaksiKategori, $namaKategori) {
                return [
                    'nama' => $namaKategori,
                    'total' => $transaksiKategori->sum('nominal'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $grafikKeuangan = collect();

        for ($i = 5; $i >= 0; $i--) {
            $periode = $sekarang->copy()->subMonths($i);

            $pemasukan = Transaksi::where('jenis', 'pemasukan')
                ->whereMonth('tanggal', $periode->month)
                ->whereYear('tanggal', $periode->year)
                ->sum('nominal');

            $pengeluaran = Transaksi::where('jenis', 'pengeluaran')
                ->whereMonth('tanggal', $periode->month)
                ->whereYear('tanggal', $periode->year)
                ->sum('nominal');

            $grafikKeuangan->push([
                'bulan' => $periode->translatedFormat('M'),
                'pemasukan' => (float) $pemasukan,
                'pengeluaran' => (float) $pengeluaran,
            ]);
        }

        $maksimumGrafik = max(
            1,
            (float) $grafikKeuangan->max('pemasukan'),
            (float) $grafikKeuangan->max('pengeluaran')
        );

        return view('welcome', compact(
            'totalSaldo',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'transaksiTerbaru',
            'pengeluaranKategori',
            'grafikKeuangan',
            'maksimumGrafik'
        ));
    }
}