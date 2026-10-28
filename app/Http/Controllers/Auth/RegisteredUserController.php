<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            // Klaim data lama hanya jika seluruh data keuangan belum dimiliki akun mana pun.
            // Tidak menghapus atau mengubah nominal/tanggal transaksi.
            $hasOwnedData = DB::table('kategoris')->whereNotNull('user_id')->exists()
                || DB::table('transaksis')->whereNotNull('user_id')->exists()
                || DB::table('anggarans')->whereNotNull('user_id')->exists();

            if (! $hasOwnedData) {
                DB::table('kategoris')->whereNull('user_id')->update(['user_id' => $user->id]);
                DB::table('transaksis')->whereNull('user_id')->update(['user_id' => $user->id]);
                DB::table('anggarans')->whereNull('user_id')->update(['user_id' => $user->id]);
            }

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang di MoneyTrack!');
    }
}
