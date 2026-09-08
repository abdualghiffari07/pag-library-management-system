<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    // Daftar location
    public function index()
    {
        $locations = Location::query()
            ->select('locations.*')
            ->selectSub(function ($query) {
                $query->from('books')
                    ->join(
                        'book_copies',
                        'book_copies.book_id',
                        '=',
                        'books.book_id'
                    )
                    ->selectRaw('COUNT(*)')
                    ->whereColumn(
                        'books.rack',
                        'locations.location_name'
                    )
                    ->where('books.status', 'public');
            }, 'books_count')
            ->orderBy('location_name')
            ->get();

        return view('pages.tables.locations.index', [
            'title' => 'Location',
            'locations' => $locations,
        ]);
    }

    // Form tambah location
    public function create()
    {
        return view('pages.tables.locations.create', [
            'title' => 'Tambah Location',
        ]);
    }

    // Simpan location
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('locations', 'location_name'),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ], [
            'location_name.required' => 'Location wajib diisi.',
            'location_name.max' => 'Location maksimal 150 karakter.',
            'location_name.unique' => 'Location tersebut sudah digunakan.',
        ]);

        Location::create([
            'location_name' => trim($validated['location_name']),
            'description' => !empty($validated['description'])
                ? trim($validated['description'])
                : null,
        ]);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil ditambahkan.');
    }

    // Form edit location
    public function edit(Location $location)
    {
        return view('pages.tables.locations.edit', [
            'title' => 'Edit Location',
            'location' => $location,
        ]);
    }

    // Update location
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'location_name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('locations', 'location_name')
                    ->ignore(
                        $location->location_id,
                        'location_id'
                    ),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ], [
            'location_name.required' => 'Location wajib diisi.',
            'location_name.max' => 'Location maksimal 150 karakter.',
            'location_name.unique' => 'Location tersebut sudah digunakan.',
        ]);

        $location->update([
            'location_name' => trim($validated['location_name']),
            'description' => !empty($validated['description'])
                ? trim($validated['description'])
                : null,
        ]);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil diperbarui.');
    }

    // Hapus location
    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil dihapus.');
    }
}