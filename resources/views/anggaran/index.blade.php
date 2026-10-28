@extends('layouts.app')

@section('title', 'MoneyTrack | Anggaran')
@section('page-title', 'Anggaran')
@section('page-description', 'Atur batas pengeluaran dan pantau anggaranmu.')

@section('content')
    <div class="budget-page">

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="budget-alert budget-alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan validasi --}}
        @if ($errors->any())
            <div class="budget-alert budget-alert-error">
                <strong>Periksa kembali input kamu:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form tambah anggaran --}}
        <section class="budget-card">
            <div class="budget-card-heading">
                <div>
                    <h2>Tambah Anggaran</h2>
                    <p>Buat batas pengeluaran untuk kategori dan periode tertentu.</p>
                </div>
            </div>

            <form action="{{ route('anggaran.store') }}" method="POST">
                @csrf

                <div class="budget-form-grid">
                    <div class="budget-form-group">
                        <label for="kategori_id">Kategori</label>

                        <select id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih kategori</option>

                            @foreach ($kategoris as $kategori)
                                <option
                                    value="{{ $kategori->id }}"
                                    @selected(old('kategori_id') == $kategori->id)
                                >
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>

                        @if ($kategoris->isEmpty())
                            <small>
                                Belum ada kategori pengeluaran. Tambahkan kategori terlebih dahulu.
                            </small>
                        @endif
                    </div>

                    <div class="budget-form-group">
                        <label for="jumlah_anggaran">Jumlah Anggaran (Rp)</label>

                        <input
                            type="number"
                            id="jumlah_anggaran"
                            name="jumlah_anggaran"
                            min="0.01"
                            step="0.01"
                            value="{{ old('jumlah_anggaran') }}"
                            placeholder="Contoh: 500000"
                            required
                        >
                    </div>

                    <div class="budget-form-group">
                        <label for="bulan">Bulan</label>

                        <select id="bulan" name="bulan" required>
                            <option value="">Pilih bulan</option>

                            @foreach ([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ] as $nomor => $namaBulan)
                                <option
                                    value="{{ $nomor }}"
                                    @selected(old('bulan', now()->month) == $nomor)
                                >
                                    {{ $namaBulan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="budget-form-group">
                        <label for="tahun">Tahun</label>

                        <input
                            type="number"
                            id="tahun"
                            name="tahun"
                            min="2000"
                            max="2100"
                            value="{{ old('tahun', now()->year) }}"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="budget-button">
                    + Simpan Anggaran
                </button>
            </form>
        </section>

        {{-- Daftar anggaran --}}
        <section class="budget-card">
            <div class="budget-list-heading">
                <div>
                    <h2>Daftar Anggaran</h2>
                    <p>Anggaran yang sudah kamu buat.</p>
                </div>

                <span class="budget-count">
                    {{ $anggarans->count() }}
                    {{ $anggarans->count() === 1 ? 'anggaran' : 'anggaran' }}
                </span>
            </div>

            <div class="budget-table-wrapper">
                <table class="budget-table">
                    <thead>
                        <tr>
                            <th>KATEGORI</th>
                            <th>PERIODE</th>
                            <th>JUMLAH ANGGARAN</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($anggarans as $anggaran)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $anggaran->kategori->nama ?? 'Kategori tidak ditemukan' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::createFromDate(
                                        $anggaran->tahun,
                                        $anggaran->bulan,
                                        1
                                    )->translatedFormat('F Y') }}
                                </td>

                                <td class="budget-amount">
                                    Rp {{ number_format((float) $anggaran->jumlah_anggaran, 0, ',', '.') }}
                                </td>

                                {{-- Tombol Edit dan Hapus --}}
                                <td>
                                    <div class="budget-action-buttons">

                                        <a
                                            href="{{ route('anggaran.edit', $anggaran->id) }}"
                                            class="budget-edit-button"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('anggaran.destroy', $anggaran->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus anggaran ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="budget-delete-button"
                                            >
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="budget-empty">
                                    Belum ada anggaran. Tambahkan anggaran melalui form di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>
@endsection