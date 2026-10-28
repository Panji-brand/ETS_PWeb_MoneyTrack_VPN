<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('kategori')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $kategoris = Kategori::orderBy('nama')->get();

        return view('transaksi.index', compact('transaksis', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => [
                'required',
                Rule::in(['pemasukan', 'pengeluaran']),
            ],
            'kategori_id' => [
                'nullable',
                'integer',
                Rule::exists('kategoris', 'id')->where(
                    fn ($query) => $query->where('jenis', $request->jenis)->where('user_id', $request->user()->id)
                ),
            ],
            'judul' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'gt:0'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        Transaksi::create($validated);

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function edit(Transaksi $transaksi)
{
    $kategoris = Kategori::where('jenis', $transaksi->jenis)
        ->orderBy('nama')
        ->get();

    return view('transaksi.edit', compact('transaksi', 'kategoris'));
}

public function update(Request $request, Transaksi $transaksi)
{
    $validated = $request->validate([
        'jenis' => ['required', 'in:pemasukan,pengeluaran'],
        'judul' => ['required', 'string', 'max:255'],
        'kategori_id' => ['nullable', 'integer', 'exists:kategoris,id'],
        'nominal' => ['required', 'numeric', 'gt:0', 'max:9999999999999.99'],
        'tanggal' => ['required', 'date'],
        'catatan' => ['nullable', 'string', 'max:5000'],
    ], [
        'jenis.required' => 'Jenis transaksi wajib dipilih.',
        'jenis.in' => 'Jenis transaksi tidak valid.',
        'judul.required' => 'Nama transaksi wajib diisi.',
        'judul.max' => 'Nama transaksi maksimal 255 karakter.',
        'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
        'nominal.required' => 'Nominal wajib diisi.',
        'nominal.numeric' => 'Nominal harus berupa angka.',
        'nominal.gt' => 'Nominal harus lebih besar dari nol.',
        'tanggal.required' => 'Tanggal transaksi wajib diisi.',
        'tanggal.date' => 'Tanggal transaksi tidak valid.',
    ]);

    if ($validated['kategori_id'] !== null) {
        $kategoriValid = Kategori::where('id', $validated['kategori_id'])
            ->where('jenis', $validated['jenis'])
            ->exists();

        if (!$kategoriValid) {
            return back()
                ->withErrors([
                    'kategori_id' => 'Kategori harus sesuai dengan jenis transaksi.',
                ])
                ->withInput();
        }
    }

    $transaksi->update($validated);

    return redirect()
        ->route('transaksi.index')
        ->with('success', 'Transaksi berhasil diperbarui.');
}
}