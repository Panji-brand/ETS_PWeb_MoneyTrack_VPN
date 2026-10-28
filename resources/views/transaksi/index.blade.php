@extends('layouts.app')

@section('title', 'MoneyTrack | Transaksi')
@section('page-title', 'Transaksi')
@section('page-description', 'Kelola pemasukan dan pengeluaranmu.')

@section('content')
    <section class="transactions-page">

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Transaksi gagal disimpan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card transaction-form-card">
            <h2 class="section-title">Tambah Transaksi</h2>
            <p class="section-description">
                Catat pemasukan atau pengeluaran keuanganmu.
            </p>

            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf

                <div class="transaction-form-grid">

                    <div class="form-group">
                        <label for="jenis">Jenis Transaksi</label>
                        <select id="jenis" name="jenis" required>
                            <option value="">Pilih jenis transaksi</option>
                            <option value="pemasukan"
                                {{ old('jenis') === 'pemasukan' ? 'selected' : '' }}>
                                Pemasukan
                            </option>
                            <option value="pengeluaran"
                                {{ old('jenis') === 'pengeluaran' ? 'selected' : '' }}>
                                Pengeluaran
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="judul">Nama Transaksi</label>
                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            value="{{ old('judul') }}"
                            placeholder="Contoh: Gaji bulanan"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select id="kategori_id" name="kategori_id">
                            <option value="">Tanpa kategori</option>

                            @foreach ($kategoris as $kategori)
                                <option
                                    value="{{ $kategori->id }}"
                                    data-jenis="{{ $kategori->jenis }}"
                                    {{ (string) old('kategori_id') === (string) $kategori->id ? 'selected' : '' }}
                                >
                                    {{ $kategori->nama }}
                                    ({{ ucfirst($kategori->jenis) }})
                                </option>
                            @endforeach
                        </select>

                        <small id="kategori-hint">
                            Pilih jenis transaksi terlebih dahulu.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="nominal">Nominal (Rp)</label>
                        <input
                            type="number"
                            id="nominal"
                            name="nominal"
                            value="{{ old('nominal') }}"
                            placeholder="Contoh: 50000"
                            min="0.01"
                            step="0.01"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input
                            type="date"
                            id="tanggal"
                            name="tanggal"
                            value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan (Opsional)</label>
                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="2"
                            placeholder="Tambahkan catatan transaksi..."
                        >{{ old('catatan') }}</textarea>
                    </div>

                </div>

                <button type="submit" class="btn-primary">
                    + Simpan Transaksi
                </button>
            </form>
        </div>

        <div class="card transactions-card">
            <div class="table-heading">
                <div>
                    <h2 class="section-title">Daftar Transaksi</h2>
                    <p class="section-description">
                        Semua transaksi yang tersimpan di database.
                    </p>
                </div>

                <span class="transaction-count">
                    {{ $transaksis->count() }} transaksi
                </span>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>TRANSAKSI</th>
                            <th>KATEGORI</th>
                            <th>TANGGAL</th>
                            <th>JENIS</th>
                            <th>NOMINAL</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transaksis as $transaksi)
                            <tr>
                                <td>
                                    <strong>{{ $transaksi->judul }}</strong>

                                    @if ($transaksi->catatan)
                                        <br>
                                        <small>{{ $transaksi->catatan }}</small>
                                    @endif
                                </td>

                                <td>
                                    {{ $transaksi->kategori?->nama ?? 'Tanpa kategori' }}
                                </td>

                                <td>
                                    {{ $transaksi->tanggal->format('d M Y') }}
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

                                <td>
                                    <div class="transaction-actions">
                                        <a
                                            href="{{ route('transaksi.edit', $transaksi) }}"
                                            class="btn-edit"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('transaksi.destroy', $transaksi) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    Belum ada transaksi.
                                    Silakan tambahkan transaksi melalui formulir di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jenisSelect = document.getElementById('jenis');
            const kategoriSelect = document.getElementById('kategori_id');
            const kategoriHint = document.getElementById('kategori-hint');

            function filterKategori() {
                const jenis = jenisSelect.value;
                const previousValue = kategoriSelect.value;
                let previousValueAvailable = false;

                Array.from(kategoriSelect.options).forEach(function (option) {
                    if (!option.value) {
                        option.hidden = false;
                        option.disabled = false;
                        return;
                    }

                    const cocok = !jenis || option.dataset.jenis === jenis;

                    option.hidden = !cocok;
                    option.disabled = !cocok;

                    if (cocok && option.value === previousValue) {
                        previousValueAvailable = true;
                    }
                });

                if (!previousValueAvailable) {
                    kategoriSelect.value = '';
                }

                kategoriHint.textContent = !jenis
                    ? 'Pilih jenis transaksi terlebih dahulu.'
                    : 'Kategori ditampilkan berdasarkan jenis transaksi.';
            }

            jenisSelect.addEventListener('change', filterKategori);

            filterKategori();
        });
    </script>
@endsection
