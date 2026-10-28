<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::latest()->get();

        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis' => 'required|in:pemasukan,pengeluaran',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max' => 'Nama kategori maksimal 100 karakter.',
            'jenis.required' => 'Jenis kategori wajib dipilih.',
            'jenis.in' => 'Jenis kategori tidak valid.',
        ]);

        Kategori::create($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->transaksis()->exists()) {
            return redirect()
                ->route('kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh transaksi.');
        }

        if ($kategori->anggarans()->exists()) {
            return redirect()
                ->route('kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh anggaran.');
        }

        $kategori->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
