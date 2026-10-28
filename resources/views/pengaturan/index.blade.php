@extends('layouts.app')

@section('title', 'MoneyTrack | Pengaturan')
@section('page-title', 'Pengaturan')
@section('page-description', 'Kelola preferensi dan informasi aplikasi MoneyTrack.')

@section('content')
    <section class="card" style="margin-bottom: 24px;">
        <h3 class="section-title">Profil Pengguna</h3>
        <p class="section-description">
            Informasi akun yang digunakan pada aplikasi MoneyTrack.
        </p>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 24px;">
            <div style="
                width: 56px;
                height: 56px;
                border-radius: 50%;
                background: #eee9ff;
                color: #6554ff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                font-weight: bold;
            ">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div>
                <strong style="font-size: 16px;">{{ auth()->user()->name }}</strong>
                <p class="section-description" style="margin-top: 4px;">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>
    </section>

    <section class="card" style="margin-bottom: 24px;">
        <h3 class="section-title">Preferensi Tampilan</h3>
        <p class="section-description">
            Informasi preferensi tampilan keuangan.
        </p>

        <div style="margin-top: 24px;">
            <label for="mata-uang" style="display: block; margin-bottom: 8px; font-weight: 600;">
                Mata Uang
            </label>

            <select
                id="mata-uang"
                disabled
                style="
                    width: 100%;
                    max-width: 400px;
                    padding: 12px;
                    border: 1px solid #e2e5ef;
                    border-radius: 8px;
                    background: #f7f8fc;
                    color: #555;
                "
            >
                <option value="IDR" selected>Rupiah (IDR)</option>
            </select>

            <p class="section-description" style="margin-top: 8px;">
                Saat ini MoneyTrack menggunakan Rupiah sebagai mata uang.
            </p>
        </div>
    </section>

    <section class="card">
        <h3 class="section-title">Tentang MoneyTrack</h3>
        <p class="section-description">
            Informasi aplikasi.
        </p>

        <div style="margin-top: 20px;">
            <div style="margin-bottom: 16px;">
                <span class="card-label">Nama Aplikasi</span>
                <p style="margin-top: 6px; font-weight: 600;">MoneyTrack</p>
            </div>

            <div style="margin-bottom: 16px;">
                <span class="card-label">Deskripsi</span>
                <p style="margin-top: 6px;">
                    Aplikasi untuk mencatat dan memantau pemasukan,
                    pengeluaran, kategori, serta anggaran keuangan pribadi.
                </p>
            </div>

            <div>
                <span class="card-label">Versi</span>
                <p style="margin-top: 6px; font-weight: 600;">1.0.0</p>
            </div>
        </div>
    </section>
@endsection