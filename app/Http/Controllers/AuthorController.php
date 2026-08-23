<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Menampilkan daftar penulis.
     */
    public function index()
    {
        $authors = Author::withCount('books')
            ->orderBy('author_id', 'asc')
            ->get();

        return view('pages.tables.authors.authors', [
            'title' => 'Data Penulis',
            'authors' => $authors,
        ]);
    }

    /**
     * Menampilkan form tambah penulis.
     */
    public function create()
    {
        return view('pages.tables.authors.create', [
            'title' => 'Tambah Penulis',
        ]);
    }

    /**
     * Menyimpan penulis baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'author_name.required' => 'Nama penulis wajib diisi.',
            'author_name.string' => 'Nama penulis harus berupa teks.',
            'author_name.max' => 'Nama penulis maksimal 255 karakter.',
        ]);

        Author::create([
            'author_name' => $validated['author_name'],
        ]);

        return redirect()
            ->route('authors')
            ->with('success', 'Penulis berhasil ditambahkan.');
    }

    /**
     * Form edit penulis.
     */
    public function edit($id)
    {
        $author = Author::findOrFail($id);

        return view('pages.tables.authors.edit', [
            'title' => 'Edit Penulis',
            'author' => $author,
        ]);
    }

    /**
     * Update penulis.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'author_name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $author = Author::findOrFail($id);

        $author->update([
            'author_name' => $validated['author_name'],
        ]);

        return redirect()
            ->route('authors')
            ->with('success', 'Penulis berhasil diperbarui.');
    }

    /**
     * Hapus penulis.
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        $author->delete();

        return redirect()
            ->route('authors')
            ->with('success', 'Penulis berhasil dihapus.');
    }
}