<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $search = $request->input('search');

    $authors = Author::when($search, function ($query, $search) {
        $query->where('author_name', 'like', '%' . $search . '%');
    })
    ->orderBy('author_name', 'asc')
    ->paginate(10)
    ->withQueryString();

    return view('authors.index', compact('authors', 'search'));
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
        'pseudonym' => 'nullable|string|max:255',
        'birth_date' => 'nullable|date',
        'nationality' => 'nullable|string|max:100',
        'biography' => 'nullable|string',
        'website' => 'nullable|url|max:255',
    ], [
        'author_name.required' => 'Nama penulis wajib diisi.',
        'birth_date.date' => 'Format tanggal lahir tidak valid.',
        'website.url' => 'Website harus berupa URL yang valid.',
    ]);

    Author::create($validated);

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
        'author_name'  => 'required|string|max:255',
        'pseudonym'    => 'nullable|string|max:255',
        'birth_date'   => 'nullable|date',
        'nationality'  => 'nullable|string|max:100',
        'website'      => 'nullable|url|max:255',
        'biography'    => 'nullable|string',
    ]);

    $author->update([
        'author_name'  => $validated['author_name'],
        'pseudonym'    => $validated['pseudonym'] ?? null,
        'birth_date'   => $validated['birth_date'] ?? null,
        'nationality'  => $validated['nationality'] ?? null,
        'website'      => $validated['website'] ?? null,
        'biography'    => $validated['biography'] ?? null,
    ]);

    return redirect()->route('authors.show', $author->author_id)
        ->with('success', 'Data penulis berhasil diperbarui.');
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
