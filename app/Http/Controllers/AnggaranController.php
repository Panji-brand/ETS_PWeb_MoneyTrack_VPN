<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggaranController extends Controller
{
    public function index()
    {
        $anggarans = Anggaran::with('kategori')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        $kategoris = Kategori::where('jenis', 'pengeluaran')
            ->orderBy('nama')
            ->get();

        return view('anggaran.index', compact('anggarans', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'integer',
                Rule::exists('kategoris', 'id')
                    ->where(fn ($query) => $query->where('jenis', 'pengeluaran')->where('user_id', request()->user()->id)),
            ],
            'jumlah_anggaran' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'bulan' => [
                'required',
                'integer',
                'between:1,12',
            ],
            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.integer' => 'Kategori yang dipilih tidak valid.',
            'kategori_id.exists' => 'Kategori harus berupa kategori pengeluaran yang valid.',

            'jumlah_anggaran.required' => 'Jumlah anggaran wajib diisi.',
            'jumlah_anggaran.numeric' => 'Jumlah anggaran harus berupa angka.',
            'jumlah_anggaran.min' => 'Jumlah anggaran harus lebih besar dari nol.',

            'bulan.required' => 'Bulan wajib dipilih.',
            'bulan.integer' => 'Bulan harus berupa angka bulat.',
            'bulan.between' => 'Bulan harus antara 1 sampai 12.',

            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka bulat.',
            'tahun.min' => 'Tahun minimal 2000.',
            'tahun.max' => 'Tahun maksimal 2100.',
        ]);

        $duplikat = Anggaran::where('kategori_id', $validated['kategori_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($duplikat) {
            return back()
                ->withErrors([
                    'kategori_id' => 'Anggaran untuk kategori dan periode tersebut sudah ada.',
                ])
                ->withInput();
        }

        Anggaran::create($validated);

        return redirect()
            ->route('anggaran.index')
            ->with('success', 'Anggaran berhasil ditambahkan.');
    }

    public function edit(Anggaran $anggaran)
{
    $kategoris = Kategori::where('jenis', 'pengeluaran')
        ->orderBy('nama')
        ->get();

    return view('anggaran.edit', compact('anggaran', 'kategoris'));
}
    public function update(Request $request, Anggaran $anggaran)
{
    $validated = $request->validate([
        'kategori_id' => [
            'required',
            'integer',
            Rule::exists('kategoris', 'id')
                ->where(fn ($query) => $query->where('jenis', 'pengeluaran')->where('user_id', request()->user()->id)),
        ],
        'jumlah_anggaran' => [
            'required',
            'numeric',
            'min:0.01',
        ],
        'bulan' => [
            'required',
            'integer',
            'between:1,12',
        ],
        'tahun' => [
            'required',
            'integer',
            'min:2000',
            'max:2100',
        ],
    ], [
        'kategori_id.required' => 'Kategori wajib dipilih.',
        'kategori_id.exists' => 'Kategori harus berupa kategori pengeluaran yang valid.',
        'jumlah_anggaran.required' => 'Jumlah anggaran wajib diisi.',
        'jumlah_anggaran.numeric' => 'Jumlah anggaran harus berupa angka.',
        'jumlah_anggaran.min' => 'Jumlah anggaran harus lebih besar dari nol.',
        'bulan.required' => 'Bulan wajib dipilih.',
        'bulan.between' => 'Bulan harus antara 1 sampai 12.',
        'tahun.required' => 'Tahun wajib diisi.',
        'tahun.min' => 'Tahun minimal 2000.',
        'tahun.max' => 'Tahun maksimal 2100.',
    ]);

    $duplikat = Anggaran::where('kategori_id', $validated['kategori_id'])
        ->where('bulan', $validated['bulan'])
        ->where('tahun', $validated['tahun'])
        ->where('id', '!=', $anggaran->id)
        ->exists();

    if ($duplikat) {
        return back()
            ->withErrors([
                'kategori_id' => 'Anggaran untuk kategori dan periode tersebut sudah ada.',
            ])
            ->withInput();
    }

    $anggaran->update($validated);

    return redirect()
        ->route('anggaran.index')
        ->with('success', 'Anggaran berhasil diperbarui.');
}
    public function destroy(Anggaran $anggaran)
    {
        $anggaran->delete();

        return redirect()
            ->route('anggaran.index')
            ->with('success', 'Anggaran berhasil dihapus.');
    }
}