<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $q = Buku::query();

        if ($s = $request->search)     $q->search($s);
        if ($k = $request->kategori)   $q->byKategori($k);
        if ($request->tersedia === '1') $q->available();

        $sort = $request->sort ?? 'latest';
        match($sort) {
            'az'       => $q->orderBy('judul'),
            'za'       => $q->orderByDesc('judul'),
            'populer'  => $q->orderByDesc('total_dipinjam'),
            'stok'     => $q->orderByDesc('stok'),
            default    => $q->latest(),
        };

        $bukuList = $q->paginate(12)->withQueryString();
        return view('buku.index', compact('bukuList'));
    }

    public function show(Buku $buku)
    {
        $buku->load('peminjaman');
        return view('buku.show', compact('buku'));
    }

    public function create()
    {
        return view('buku.form', ['buku' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'kategori'     => 'required|in:' . implode(',', Buku::KATEGORI),
            'sinopsis'     => 'nullable|string',
            'lokasi_rak'   => 'nullable|string|max:50',
            'stok'         => 'required|integer|min:0',
            'cover'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers','public');
        } else {
            unset($data['cover']);
        }

        $data['status'] = $data['stok'] > 0 ? 'tersedia' : 'tidak_tersedia';
        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Buku $buku)
    {
        return view('buku.form', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $data = $request->validate([
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer',
            'kategori'     => 'required|in:' . implode(',', Buku::KATEGORI),
            'sinopsis'     => 'nullable|string',
            'lokasi_rak'   => 'nullable|string|max:50',
            'stok'         => 'required|integer|min:0',
            'cover'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($buku->cover) Storage::disk('public')->delete($buku->cover);
            $data['cover'] = $request->file('cover')->store('covers','public');
        } elseif ($request->boolean('hapus_cover')) {
            if ($buku->cover) Storage::disk('public')->delete($buku->cover);
            $data['cover'] = null;
        } else {
            unset($data['cover']);
        }

        $data['status'] = $data['stok'] > 0 ? 'tersedia' : 'tidak_tersedia';
        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover) Storage::disk('public')->delete($buku->cover);
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
