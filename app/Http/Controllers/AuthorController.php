<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::orderBy('author_name')->get();

        return view('authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $validated = $request->validate([
        'author_name' => 'required|string|max:255',
    ]);

    Author::create([
        'author_name' => $validated['author_name'],
    ]);

    return redirect()
        ->route('authors.index')
        ->with('success', 'Penulis berhasil ditambahkan.');
    }
    /** 
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $author->load('books');

        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
        ]);

        $author->update([
            'author_name' => $validated['author_name'],
        ]);

        return redirect()
            ->route('authors.index')
            ->with('success', 'Penulis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->books()->detach();

        $author->delete();

        return redirect()
            ->route('authors.index')
            ->with('success', 'Penulis berhasil dihapus.');
    }
}
