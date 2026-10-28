@extends('layouts.app')

@section('title', 'MoneyTrack | Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Selamat datang kembali! Berikut ringkasan keuanganmu.')

@section('content')
    <section class="summary-cards">
        <div class="card">
            <div class="card-top">
                <span class="card-label">Total Saldo</span>
                <div class="card-icon">▣</div>
            </div>
            <h2>Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h2>
            <p class="card-description">Saldo dari seluruh transaksi</p>
        </div>

        <div class="card">
            <div class="card-top">
                <span class="card-label">Total Pemasukan</span>
                <div class="card-icon income-icon">↗</div>
            </div>
            <h2>Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h2>
            <p class="card-description">
                <span class="income-text">Pemasukan</span> bulan ini
            </p>
        </div>

        <div class="card">
            <div class="card-top">
                <span class="card-label">Total Pengeluaran</span>
                <div class="card-icon expense-icon">↘</div>
            </div>
            <h2>Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h2>
            <p class="card-description">
                <span class="expense-text">Pengeluaran</span> bulan ini
            </p>
        </div>
    </section>

    <section class="middle-section">
        <div class="card">
            <div class="chart-heading">
                <div>
                    <h3 class="section-title">Ringkasan Keuangan</h3>
                    <p class="section-description">
                        Perbandingan pemasukan dan pengeluaran selama 6 bulan terakhir
                    </p>
                </div>
                <span class="section-description">6 Bulan</span>
            </div>

            <div class="chart">
                @foreach ($grafikKeuangan as $data)
                    <div class="month-column">
                        <div class="bars">
                            <div
                                class="bar bar-income"
                                style="height: {{ ($data['pemasukan'] / $maksimumGrafik) * 100 }}%;"
                                title="Pemasukan: Rp {{ number_format($data['pemasukan'], 0, ',', '.') }}"
                            ></div>

                            <div
                                class="bar bar-expense"
                                style="height: {{ ($data['pengeluaran'] / $maksimumGrafik) * 100 }}%;"
                                title="Pengeluaran: Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}"
                            ></div>
                        </div>

                        <span class="month-label">{{ $data['bulan'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="legend">
                <div class="legend-item">
                    <span class="legend-color bar-income"></span>
                    Pemasukan
                </div>
                <div class="legend-item">
                    <span class="legend-color bar-expense"></span>
                    Pengeluaran
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="section-title">Kategori Pengeluaran</h3>
            <p class="section-description">Distribusi pengeluaran bulan ini</p>

            <div class="category-list">
                @php
                    $pengeluaranMaksimumKategori = max(
                        1,
                        (float) $pengeluaranKategori->max('total')
                    );
                @endphp

                @forelse ($pengeluaranKategori as $kategori)
                    <div class="category-item">
                        <div class="category-icon">↘</div>

                        <div class="category-info">
                            <strong>{{ $kategori['nama'] }}</strong>

                            <div class="progress-bar">
                                <div
                                    class="progress"
                                    style="width: {{ ($kategori['total'] / $pengeluaranMaksimumKategori) * 100 }}%;"
                                ></div>
                            </div>
                        </div>

                        <span class="category-amount">
                            Rp {{ number_format($kategori['total'], 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <p class="section-description">
                        Belum ada pengeluaran pada bulan ini.
                    </p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="card transactions-card">
        <div class="table-heading">
            <div>
                <h3 class="section-title">Transaksi Terbaru</h3>
                <p class="section-description">Aktivitas keuangan terakhir</p>
            </div>

            <a href="{{ route('transaksi.index') }}" class="see-all">
                Lihat Semua →
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>TRANSAKSI</th>
                        <th>KATEGORI</th>
                        <th>TANGGAL</th>
                        <th>JENIS</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksiTerbaru as $transaksi)
                        <tr>
                            <td class="transaction-name">
                                {{ $transaksi->judul }}
                            </td>

                            <td class="transaction-category">
                                {{ $transaksi->kategori->nama ?? 'Tanpa Kategori' }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                @if ($transaksi->jenis === 'pemasukan')
                                    <span class="transaction-type badge-income">
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="transaction-type badge-expense">
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>

                            <td class="{{ $transaksi->jenis === 'pemasukan' ? 'amount-income' : 'amount-expense' }}">
                                {{ $transaksi->jenis === 'pemasukan' ? '+' : '-' }}
                                Rp {{ number_format((float) $transaksi->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 24px;">
                                Belum ada transaksi. Silakan tambahkan transaksi terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection