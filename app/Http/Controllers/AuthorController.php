<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    // Daftar penulis
    public function index(Request $request)
    {
        $search = trim(
            $request->query('search', '')
        );

        $authors = Author::query()
            ->withCount('books')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(
                    'author_name',
                    'like',
                    '%' . $search . '%'
                );
            })
            ->orderBy('author_name')
            ->paginate(20)
            ->withQueryString();

        return view('pages.tables.authors', [
            'title' => 'Data Penulis',
            'authors' => $authors,
            'search' => $search,
        ]);
    }

    // Tambah penulis
    public function create()
    {
        return view('pages.tables.authors.create', [
            'title' => 'Tambah Penulis',
        ]);
    }

    // Simpan penulis
    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'author_name.required' =>
                'Nama penulis wajib diisi.',
            'author_name.string' =>
                'Nama penulis harus berupa teks.',
            'author_name.max' =>
                'Nama penulis maksimal 255 karakter.',
        ]);

        Author::create([
            'author_name' => $validated['author_name'],
        ]);

        return redirect()
            ->route('authors')
            ->with(
                'success',
                'Penulis berhasil ditambahkan.'
            );
    }

    // Edit penulis
    public function edit($id)
    {
        $author = Author::findOrFail($id);

        return view('pages.tables.authors.edit', [
            'title' => 'Edit Penulis',
            'author' => $author,
        ]);
    }

    // Update penulis
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'author_name' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'author_name.required' =>
                'Nama penulis wajib diisi.',
            'author_name.string' =>
                'Nama penulis harus berupa teks.',
            'author_name.max' =>
                'Nama penulis maksimal 255 karakter.',
        ]);

        $author = Author::findOrFail($id);

        $author->update([
            'author_name' =>
                $validated['author_name'],
        ]);

        return redirect()
            ->route('authors')
            ->with(
                'success',
                'Penulis berhasil diperbarui.'
            );
    }

    // Hapus penulis terpilih
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:200'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:authors,author_id'],
        ]);

        try {
            $deletedCount = DB::transaction(function () use ($validated) {
                $authors = Author::query()
                    ->whereIn('author_id', $validated['ids'])
                    ->lockForUpdate()
                    ->get();

                if ($authors->count() !== count($validated['ids'])) {
                    throw new \RuntimeException('Sebagian data penulis tidak ditemukan.');
                }

                foreach ($authors as $author) {
                    // Lepas relasi buku tanpa menghapus bukunya
                    $author->books()->detach();
                    $author->delete();
                }

                return $authors->count();
            });

            return redirect()
                ->route('authors', [
                    'search' => $request->input('search', ''),
                ])
                ->with('success', $deletedCount . ' penulis berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->with('error', 'Gagal menghapus penulis yang dipilih.');
        }
    }

    // Hapus penulis
    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        $author->delete();

        return redirect()
            ->route('authors')
            ->with(
                'success',
                'Penulis berhasil dihapus.'
            );
    }
}