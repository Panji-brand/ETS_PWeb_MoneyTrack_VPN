@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Anggaran</h1>
    <p>Perbarui informasi anggaran kamu melalui formulir berikut.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('anggaran.update', $anggaran) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
    <label for="kategori_id">Kategori Pengeluaran</label>
    <select name="kategori_id" id="kategori_id" required>
        @foreach ($kategoris as $kategori)
            <option
                value="{{ $kategori->id }}"
                {{ old('kategori_id', $anggaran->kategori_id) == $kategori->id ? 'selected' : '' }}
            >
                {{ $kategori->nama }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="jumlah_anggaran">Jumlah Anggaran (Rp)</label>
    <input
        type="number"
        name="jumlah_anggaran"
        id="jumlah_anggaran"
        value="{{ old('jumlah_anggaran', $anggaran->jumlah_anggaran) }}"
        min="1"
        required
    >
</div>

<div class="form-group">
    <label for="bulan">Bulan</label>
    <select name="bulan" id="bulan" required>
        @foreach (range(1, 12) as $nomorBulan)
            <option
                value="{{ $nomorBulan }}"
                {{ old('bulan', $anggaran->bulan) == $nomorBulan ? 'selected' : '' }}
            >
                {{ \Carbon\Carbon::create()->month($nomorBulan)->translatedFormat('F') }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="tahun">Tahun</label>
    <input
        type="number"
        name="tahun"
        id="tahun"
        value="{{ old('tahun', $anggaran->tahun) }}"
        min="2000"
        max="2100"
        required
    >
</div>

<button type="submit">Simpan Perubahan</button>
<a href="{{ route('anggaran.index') }}">Batal</a>
</div>

<style>
    .container {
        max-width: 700px;
        margin: 0 auto;
        padding: 24px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        box-sizing: border-box;
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font: inherit;
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #777;
    }

    .btn-save,
    .btn-cancel {
        display: inline-block;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font: inherit;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-save {
        background: #6845ff;
        color: white;
    }

    .btn-cancel {
        background: #eee;
        color: #333;
    }

    .alert-danger {
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 8px;
        background: #ffe8e8;
        color: #a40000;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisSelect = document.getElementById('jenis');
        const kategoriSelect = document.getElementById('kategori_id');

        function filterKategori() {
            const jenis = jenisSelect.value;
            let selectedKategoriValid = false;

            Array.from(kategoriSelect.options).forEach(function (option) {
                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                const kategoriJenis = @json($kategoris->pluck('jenis', 'id'));
                const cocok = kategoriJenis[option.value] === jenis;

                option.hidden = !cocok;

                if (cocok && option.selected) {
                    selectedKategoriValid = true;
                }
            });

            if (!selectedKategoriValid) {
                kategoriSelect.value = '';
            }
        }

        jenisSelect.addEventListener('change', filterKategori);
        filterKategori();
    });
</script>
@endsection