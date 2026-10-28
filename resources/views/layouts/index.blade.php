@extends('layouts.app')

@section('title', 'MoneyTrack | Transaksi')
@section('page-title', 'Transaksi')
@section('page-description', 'Kelola pemasukan dan pengeluaranmu.')

@section('content')
<section class="transactions-page">

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="card">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="card">
            <strong>Transaksi gagal disimpan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form tambah transaksi --}}
    <section class="card">
        <h3 class="section-title">Tambah Transaksi</h3>

        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf

            <div>
                <label for="jenis">Jenis Transaksi</label>
                <select name="jenis" id="jenis" required>
                    <option value="">Pilih jenis transaksi</option>
                    <option value="pemasukan"
                        {{ old('jenis') == 'pemasukan' ? 'selected' : '' }}>
                        Pemasukan
                    </option>
                    <option value="pengeluaran"
                        {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }}>
                        Pengeluaran
                    </option>
                </select>
            </div>

            <div>
                <label for="judul">Nama Transaksi</label>
                <input
                    type="text"
                    name="judul"
                    id="judul"
                    value="{{ old('judul') }}"
                    placeholder="Contoh: Gaji bulanan"
                    maxlength="255"
                    required
                >
            </div>

            <div>
                <label for="nominal">Nominal (Rp)</label>
                <input
                    type="number"
                    name="nominal"
                    id="nominal"
                    value="{{ old('nominal') }}"
                    min="1"
                    step="0.01"
                    placeholder="Contoh: 50000"
                    required
                >
            </div>

            <div>
                <label for="kategori_id">Kategori</label>
                <select name="kategori_id" id="kategori_id">
                    <option value="">Tanpa kategori</option>
                </select>
                <small>
                    Pilihan kategori akan dihubungkan dengan jenis transaksi.
                </small>
            </div>

            <div>
                <label for="tanggal">Tanggal</label>
                <input
                    type="date"
                    name="tanggal"
                    id="tanggal"
                    value="{{ old('tanggal', date('Y-m-d')) }}"
                    required
                >
            </div>

            <div>
                <label for="catatan">Catatan</label>
                <textarea
                    name="catatan"
                    id="catatan"
                    rows="3"
                    placeholder="Catatan tambahan (opsional)"
                >{{ old('catatan') }}</textarea>
            </div>

            <button type="submit">Simpan Transaksi</button>
        </form>
    </section>

    {{-- Daftar transaksi --}}
    <section class="card">
        <h3 class="section-title">Daftar Transaksi</h3>
        <p class="section-description">
            Berikut seluruh transaksi yang tersimpan.
        </p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Transaksi</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td>{{ $transaksi->judul }}</td>

                            <td>
                                {{ $transaksi->kategori?->nama ?? 'Tanpa kategori' }}
                            </td>

                            <td>
                                {{ $transaksi->tanggal->format('d M Y') }}
                            </td>

                            <td>
                                {{ ucfirst($transaksi->jenis) }}
                            </td>

                            <td>
                                Rp {{ number_format((float) $transaksi->nominal, 0, ',', '.') }}
                            </td>

                            <td>
                                <form
                                    action="{{ route('transaksi.destroy', $transaksi) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                Belum ada transaksi. Silakan tambahkan transaksi pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</section>
@endsection