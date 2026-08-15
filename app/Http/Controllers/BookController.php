<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku.
     */
    public function index()
    {
        $books = Book::with([
            'authors',
            'categories',
            'location'
        ])
        ->orderBy('book_id', 'desc')
        ->get();

        return view('books.index', compact('books'));
    }

    /**
     * Menampilkan form tambah buku.
     */
    public function create()
    {
        $authors = Author::orderBy('author_name')->get();

        $categories = Category::orderBy('category_name')->get();

        $locations = Location::orderBy('location_name')->get();

        return view('books.create', compact(
            'authors',
            'categories',
            'locations'
        ));
    }

    /**
     * Menyimpan buku baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'origin' => 'nullable|string|max:150',
            'cover' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:2100',
            'location_id' => 'nullable|integer|exists:locations,location_id',
            'status' => 'required|in:public,arsip',
            'description' => 'nullable|string',

            'authors' => 'nullable|array',
            'authors.*' => 'integer|exists:authors,author_id',

            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,category_id',
        ]);

        DB::transaction(function () use ($validated) {

            $book = Book::create([
                'title' => $validated['title'],
                'origin' => $validated['origin'] ?? null,
                'cover' => $validated['cover'] ?? null,
                'publication_year' => $validated['publication_year'] ?? null,
                'location_id' => $validated['location_id'] ?? null,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
            ]);

            if (!empty($validated['authors'])) {
                $book->authors()->sync($validated['authors']);
            }

            if (!empty($validated['categories'])) {
                $book->categories()->sync($validated['categories']);
            }
        });

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail buku.
     */
    public function show(string $id)
    {
        $book = Book::with('copies')->findOrFail($id);

        $user = auth()->user();

        return view('books.show', compact('book', 'user'));
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit(Book $book)
    {
        $authors = Author::orderBy('author_name')->get();

        $categories = Category::orderBy('category_name')->get();

        $locations = Location::orderBy('location_name')->get();

        $book->load([
            'authors',
            'categories'
        ]);

        return view('books.edit', compact(
            'book',
            'authors',
            'categories',
            'locations'
        ));
    }

    /**
     * Memperbarui buku.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'origin' => 'nullable|string|max:150',
            'cover' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:2100',
            'location_id' => 'nullable|integer|exists:locations,location_id',
            'status' => 'required|in:public,arsip',
            'description' => 'nullable|string',

            'authors' => 'nullable|array',
            'authors.*' => 'integer|exists:authors,author_id',

            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,category_id',
        ]);

        DB::transaction(function () use ($validated, $book) {

            $book->update([
                'title' => $validated['title'],
                'origin' => $validated['origin'] ?? null,
                'cover' => $validated['cover'] ?? null,
                'publication_year' => $validated['publication_year'] ?? null,
                'location_id' => $validated['location_id'] ?? null,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
            ]);

            $book->authors()->sync(
                $validated['authors'] ?? []
            );

            $book->categories()->sync(
                $validated['categories'] ?? []
            );
        });

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Menghapus buku.
     */
    public function destroy(Book $book)
    {
        DB::transaction(function () use ($book) {

            $book->authors()->detach();

            $book->categories()->detach();

            $book->delete();
        });

        return redirect()
            ->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }


    /*
    |--------------------------------------------------------------------------
    | AUTOCOMPLETE AUTHOR
    |--------------------------------------------------------------------------
    */

    /**
     * Mencari penulis berdasarkan input.
     */
    public function searchAuthors(Request $request)
    {
        $query = trim($request->get('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $authors = Author::where(
                'author_name',
                'like',
                '%' . $query . '%'
            )
            ->orderBy('author_name')
            ->limit(10)
            ->get([
                'author_id',
                'author_name'
            ]);

        return response()->json($authors);
    }


    /**
     * Membuat penulis baru.
     */
    public function storeAuthor(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
        ]);

        $author = Author::whereRaw(
            'LOWER(author_name) = ?',
            [strtolower(trim($validated['author_name']))]
        )->first();

        if (!$author) {
            $author = Author::create([
                'author_name' => trim($validated['author_name']),
            ]);
        }

        return response()->json([
            'success' => true,
            'author' => [
                'author_id' => $author->author_id,
                'author_name' => $author->author_name,
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | AUTOCOMPLETE CATEGORY
    |--------------------------------------------------------------------------
    */

    /**
     * Mencari kategori berdasarkan input.
     */
    public function searchCategories(Request $request)
    {
        $query = trim($request->get('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $categories = Category::where(
                'category_name',
                'like',
                '%' . $query . '%'
            )
            ->orderBy('category_name')
            ->limit(10)
            ->get([
                'category_id',
                'category_name'
            ]);

        return response()->json($categories);
    }


    /**
     * Membuat kategori baru.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = Category::whereRaw(
            'LOWER(category_name) = ?',
            [strtolower(trim($validated['category_name']))]
        )->first();

        if (!$category) {
            $category = Category::create([
                'category_name' => trim($validated['category_name']),
            ]);
        }

        return response()->json([
            'success' => true,
            'category' => [
                'category_id' => $category->category_id,
                'category_name' => $category->category_name,
            ],
        ]);
    }
}