<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('books', 'book_code'),
            ],
            'cat_no' => 'required|string|max:100',
            'location_id' => 'required|exists:locations,location_id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ], [
            'book_no.required' => 'Book No. wajib diisi.',
            'book_no.unique' => 'Book No. sudah digunakan. Silakan gunakan Book No. lain.',
            'cat_no.required' => 'Cat. No. wajib diisi.',
            'location_id.required' => 'Lokasi wajib dipilih.',
            'location_id.exists' => 'Lokasi yang dipilih tidak valid.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Author wajib diisi.',
            'publisher.required' => 'Publisher wajib diisi.',
            'qty.required' => 'Jumlah buku wajib diisi.',
            'qty.integer' => 'Jumlah buku harus berupa angka.',
            'qty.min' => 'Jumlah buku minimal 1.',
        ]);

        DB::transaction(function () use ($validated) {
            $book = Book::create([
                'book_code' => $validated['book_no'],
                'cat_no' => $validated['cat_no'],
                'title' => $validated['title'],
                'publisher' => $validated['publisher'],
                'location_id' => $validated['location_id'],
                'description' => $validated['description'] ?? null,
            ]);

            $author = Author::firstOrCreate([
                'author_name' => $validated['author'],
            ]);

            $book->authors()->attach($author->author_id);

            for ($i = 1; $i <= $validated['qty']; $i++) {
                BookCopy::create([
                    'book_id' => $book->book_id,
                    'copy_code' => $book->book_code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'condition' => 'Baik',
                    'status' => 'Tersedia',
                ]);
            }
        });

        return redirect()
            ->route('data-buku')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function checkBookNo(Request $request)
    {
        $bookNo = trim($request->query('book_no', ''));

        if ($bookNo === '') {
            return response()->json([
                'exists' => false,
                'valid' => false,
                'message' => 'Book No. wajib diisi.',
            ]);
        }

        $exists = Book::where('book_code', $bookNo)->exists();

        return response()->json([
            'exists' => $exists,
            'valid' => !$exists,
            'message' => $exists
                ? 'Book No. sudah digunakan.'
                : 'Book No. tersedia.',
        ]);
    }
}