@extends('layouts.app')

@section('title', 'MoneyTrack | Edit Transaksi')
@section('page-title', 'Edit Transaksi')
@section('page-description', 'Perbarui informasi transaksi keuanganmu.')

@section('content')
<section class="card">
    <div class="table-heading">
        <div>
            <h2 class="section-title">Edit Transaksi</h2>
            <p class="section-description">
                Ubah informasi transaksi, lalu simpan perubahan.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-error" role="alert">
            <strong>Periksa kembali input kamu:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transaksi.update', $transaksi->id) }}"
          method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label for="jenis">Jenis Transaksi</label>
                <select id="jenis" name="jenis" required>
                    <option value="pemasukan"
                        {{ old('jenis', $transaksi->jenis) === 'pemasukan' ? 'selected' : '' }}>
                        Pemasukan
                    </option>
                    <option value="pengeluaran"
                        {{ old('jenis', $transaksi->jenis) === 'pengeluaran' ? 'selected' : '' }}>
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
                    value="{{ old('judul', $transaksi->judul) }}"
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
                            {{ (string) old('kategori_id', $transaksi->kategori_id) === (string) $kategori->id ? 'selected' : '' }}
                        >
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                <small>Kategori harus sesuai dengan jenis transaksi.</small>
            </div>

            <div class="form-group">
                <label for="nominal">Nominal (Rp)</label>
                <input
                    type="number"
                    id="nominal"
                    name="nominal"
                    value="{{ old('nominal', $transaksi->nominal) }}"
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
                    value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="catatan">Catatan (Opsional)</label>
                <textarea
                    id="catatan"
                    name="catatan"
                    rows="3"
                    maxlength="5000"
                >{{ old('catatan', $transaksi->catatan) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('transaksi.index') }}" class="btn-secondary">
                Batal
            </a>
        </div>
    </form>
</section>
@endsection