@extends('layouts.app')

@section('title', 'MoneyTrack | Laporan')
@section('page-title', 'Laporan Keuangan')
@section('page-description', 'Ringkasan pemasukan dan pengeluaran keuanganmu.')

@section('content')
    {{-- Filter Periode Laporan --}}
    <section class="card" style="margin-bottom: 24px; padding: 24px;">
        <form action="{{ route('laporan.index') }}" method="GET">
            <div style="display: flex; flex-wrap: wrap; align-items: end; gap: 16px;">

                <div style="flex: 1; min-width: 200px;">
                    <label for="periode" style="display: block; margin-bottom: 8px;">
                        Periode Laporan
                    </label>

                    <select
                        name="periode"
                        id="periode"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                    >
                        <option value="semua" {{ $periode === 'semua' ? 'selected' : '' }}>
                            Semua Waktu
                        </option>

                        <option value="bulan_ini" {{ $periode === 'bulan_ini' ? 'selected' : '' }}>
                            Bulan Ini
                        </option>

                        <option value="bulan_lalu" {{ $periode === 'bulan_lalu' ? 'selected' : '' }}>
                            Bulan Lalu
                        </option>

                        <option value="tahun_ini" {{ $periode === 'tahun_ini' ? 'selected' : '' }}>
                            Tahun Ini
                        </option>

                        <option value="rentang" {{ $periode === 'rentang' ? 'selected' : '' }}>
                            Rentang Tanggal
                        </option>
                    </select>
                </div>

                <div style="flex: 1; min-width: 200px;">
                    <label for="jenis" style="display: block; margin-bottom: 8px;">
                        Jenis Transaksi
                    </label>
                    
                    <select
                    name="jenis"
                    id="jenis"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                    >
                    <option value="semua" {{ request('jenis', 'semua') === 'semua' ? 'selected' : '' }}>
                        Semua Jenis
                    </option>
                    
                    <option value="pemasukan" {{ request('jenis') === 'pemasukan' ? 'selected' : '' }}>
                        Pemasukan
                    </option>
                    
                    <option value="pengeluaran" {{ request('jenis') === 'pengeluaran' ? 'selected' : '' }}>
                        Pengeluaran
                    </option>
                </select>
            </div>

                <div
                    id="rentang-tanggal"
                    style="display: {{ $periode === 'rentang' ? 'flex' : 'none' }}; flex-wrap: wrap; gap: 16px; flex: 2;"
                >
                    <div style="flex: 1; min-width: 150px;">
                        <label for="tanggal_mulai" style="display: block; margin-bottom: 8px;">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            id="tanggal_mulai"
                            value="{{ request('tanggal_mulai') }}"
                            style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                        >
                    </div>

                    <div style="flex: 1; min-width: 150px;">
                        <label for="tanggal_akhir" style="display: block; margin-bottom: 8px;">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            name="tanggal_akhir"
                            id="tanggal_akhir"
                            value="{{ request('tanggal_akhir') }}"
                            style="width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"
                        >
                    </div>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button
                        type="submit"
                        style="padding: 10px 18px; border: none; border-radius: 8px; cursor: pointer;"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ route('laporan.index') }}"
                        style="padding: 10px 18px; border: 1px solid #ddd; border-radius: 8px; text-decoration: none;"
                    >
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </section>

    {{-- Kartu Ringkasan --}}
    <section class="summary-cards">
        <div class="card">
            <div class="card-top">
                <span class="card-label">Total Pemasukan</span>
                <div class="card-icon income-icon">↗</div>
            </div>

            <h2>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h2>
            <p class="card-description">Pemasukan sesuai periode</p>
        </div>

        <div class="card">
            <div class="card-top">
                <span class="card-label">Total Pengeluaran</span>
                <div class="card-icon expense-icon">↘</div>
            </div>

            <h2>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
            <p class="card-description">Pengeluaran sesuai periode</p>
        </div>

        <div class="card">
            <div class="card-top">
                <span class="card-label">Selisih Keuangan</span>
                <div class="card-icon">▣</div>
            </div>

            <h2>Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</h2>
            <p class="card-description">Pemasukan dikurangi pengeluaran</p>
        </div>
    </section>

    {{-- Riwayat Transaksi --}}
    <section class="card transactions-card">
        <div class="table-heading">
            <div>
                <h3 class="section-title">Riwayat Transaksi</h3>

                <p class="section-description">
                    @switch($periode)
                        @case('bulan_ini')
                            Transaksi pada bulan ini.
                            @break

                        @case('bulan_lalu')
                            Transaksi pada bulan lalu.
                            @break

                        @case('tahun_ini')
                            Transaksi pada tahun ini.
                            @break

                        @case('rentang')
                            Transaksi dari
                            {{ request('tanggal_mulai') ?: 'tanggal awal' }}
                            sampai
                            {{ request('tanggal_akhir') ?: 'tanggal akhir' }}.
                            @break

                        @default
                            Seluruh transaksi yang tercatat dalam MoneyTrack.
                    @endswitch
                </p>
            </div>

            <a href="{{ route('transaksi.index') }}" class="see-all">
                Kelola Transaksi →
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
                    @forelse ($transaksi as $item)
                        <tr>
                            <td class="transaction-name">
                                {{ $item->judul }}
                            </td>

                            <td class="transaction-category">
                                {{ $item->kategori->nama ?? 'Tanpa Kategori' }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                @if ($item->jenis === 'pemasukan')
                                    <span class="transaction-type badge-income">
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="transaction-type badge-expense">
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>

                            <td class="{{ $item->jenis === 'pemasukan' ? 'amount-income' : 'amount-expense' }}">
                                {{ $item->jenis === 'pemasukan' ? '+' : '-' }}
                                Rp {{ number_format((float) $item->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 24px;">
                                Tidak ada transaksi pada periode yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Tampilkan atau sembunyikan input tanggal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const periode = document.getElementById('periode');
            const rentangTanggal = document.getElementById('rentang-tanggal');
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalAkhir = document.getElementById('tanggal_akhir');

            function toggleRentangTanggal() {
                const aktif = periode.value === 'rentang';

                rentangTanggal.style.display = aktif ? 'flex' : 'none';

                tanggalMulai.required = aktif;
                tanggalAkhir.required = aktif;
            }

            periode.addEventListener('change', toggleRentangTanggal);

            toggleRentangTanggal();
        });
    </script>
@endsection