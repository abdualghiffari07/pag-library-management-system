<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;

class BookCopyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $copies = BookCopy::with('book')
            ->orderBy('copy_id')
            ->get();

        return view('book-copies.index', compact('copies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::orderBy('title')->get();

        return view('book-copies.create', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,book_id',
            'copy_code' => 'required|string|max:100|unique:book_copies,copy_code',
            'condition' => 'required|string|max:100',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        BookCopy::create($validated);

        return redirect()
            ->route('books.show', $validated['book_id'])
            ->with('success', 'Eksemplar buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $copy = BookCopy::findOrFail($id);

        return view('book-copies.edit', compact('copy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $copy = BookCopy::findOrFail($id);

        $validated = $request->validate([
            'copy_code' => [
                'required',
                'string',
                'max:100',
                'unique:book_copies,copy_code,' . $copy->copy_id . ',copy_id',
            ],
            'condition' => 'required|string|max:100',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $copy->update($validated);

        return redirect()
            ->route('books.show', $copy->book_id)
            ->with('success', 'Eksemplar buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $copy = BookCopy::findOrFail($id);

        if ($copy->status === 'dipinjam') {
            return redirect()
                ->route('books.show', $copy->book_id)
                ->with('error', 'Eksemplar yang sedang dipinjam tidak dapat dihapus.');
        }

        $bookId = $copy->book_id;

        $copy->delete();

        return redirect()
            ->route('books.show', $bookId)
            ->with('success', 'Eksemplar buku berhasil dihapus.');
    }
}