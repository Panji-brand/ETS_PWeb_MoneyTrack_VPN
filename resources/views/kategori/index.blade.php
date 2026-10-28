@extends('layouts.app')

@section('title', 'MoneyTrack | Kategori')
@section('page-title', 'Kategori')
@section('page-description', 'Kelola kategori pemasukan dan pengeluaranmu.')

@section('content')
<div class="transactions-page">

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form kategori --}}
    <section class="content-card">
        <h2>Tambah Kategori</h2>

        <p class="section-description">
            Buat kategori untuk mengelompokkan transaksi pemasukan dan pengeluaranmu.
        </p>

        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="nama">Nama Kategori</label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="Contoh: Makanan"
                        value="{{ old('nama') }}"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="jenis">Jenis Kategori</label>

                    <select id="jenis" name="jenis" required>
                        <option value="">Pilih jenis kategori</option>
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
            </div>

            <button type="submit" class="btn-primary">
                + Simpan Kategori
            </button>
        </form>
    </section>

    {{-- Tabel kategori --}}
    <section class="content-card">
        <div class="section-header">
            <div>
                <h2>Daftar Kategori</h2>
                <p class="section-description">
                    Kelola kategori yang digunakan dalam transaksi.
                </p>
            </div>

            <span class="item-count">
                {{ $kategoris->count() }} kategori
            </span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NAMA KATEGORI</th>
                        <th>JENIS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kategoris as $kategori)
                        <tr>
                            <td>
                                <strong>{{ $kategori->nama }}</strong>
                            </td>

                            <td>
                                @if ($kategori->jenis === 'pemasukan')
                                    <span class="badge badge-income">
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="badge badge-expense">
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>

                            <td>
                                <form
                                    action="{{ route('kategori.destroy', $kategori) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-delete">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">
                                Belum ada kategori. Tambahkan kategori melalui form di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection
