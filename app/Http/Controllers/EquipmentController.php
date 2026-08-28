<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    // Daftar equipment
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));

        $equipments = Equipment::query()
            ->withCount('books')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(
                    'equipment_name',
                    'like',
                    "%{$search}%"
                );
            })
            ->orderBy('equipment_name')
            ->paginate(10)
            ->withQueryString();

        return view('components.tables.basic-tables.Equipment.equipment', [
            'title' => 'Data Equipment',
            'equipments' => $equipments,
            'search' => $search,
        ]);
    }

    // Form tambah
    public function create()
    {
        return view('components.tables.basic-tables.Equipment.add-equipment', [
            'title' => 'Tambah Equipment',
        ]);
    }

    // Simpan equipment
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'equipment_name' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:equipment,equipment_name',
                ],
            ],
            [
                'equipment_name.required' => 'Nama equipment wajib diisi.',
                'equipment_name.string' => 'Nama equipment harus berupa teks.',
                'equipment_name.max' => 'Nama equipment terlalu panjang.',
                'equipment_name.unique' => 'Equipment tersebut sudah tersedia.',
            ]
        );

        try {
            Equipment::create([
                'equipment_name' => trim($validated['equipment_name']),
            ]);

            return redirect()
                ->route('equipment.index')
                ->with('success', 'Equipment berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal menambahkan equipment: ' . $e->getMessage()
                );
        }
    }

    // Form edit
    public function edit(string $equipment_id)
    {
        $equipment = Equipment::find($equipment_id);

        if (!$equipment) {
            return redirect()
                ->route('equipment.index')
                ->with('error', 'Equipment tidak ditemukan.');
        }

        return view('components.tables.basic-tables.Equipment.edit-equipment', [
            'title' => 'Edit Equipment',
            'equipment' => $equipment,
        ]);
    }

    // Update equipment
    public function update(
        Request $request,
        string $equipment_id
    ) {
        $equipment = Equipment::find($equipment_id);

        if (!$equipment) {
            return redirect()
                ->route('equipment.index')
                ->with('error', 'Equipment tidak ditemukan.');
        }

        $validated = $request->validate(
            [
                'equipment_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique(
                        'equipment',
                        'equipment_name'
                    )->ignore(
                        $equipment->equipment_id,
                        'equipment_id'
                    ),
                ],
            ],
            [
                'equipment_name.required' => 'Nama equipment wajib diisi.',
                'equipment_name.string' => 'Nama equipment harus berupa teks.',
                'equipment_name.max' => 'Nama equipment terlalu panjang.',
                'equipment_name.unique' => 'Equipment tersebut sudah tersedia.',
            ]
        );

        try {
            $equipment->update([
                'equipment_name' => trim($validated['equipment_name']),
            ]);

            return redirect()
                ->route('equipment.index')
                ->with(
                    'success',
                    'Equipment berhasil diperbarui.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal memperbarui equipment: ' . $e->getMessage()
                );
        }
    }

    // Hapus equipment
    public function destroy(string $equipment_id)
    {
        $equipment = Equipment::withCount('books')
            ->find($equipment_id);

        if (!$equipment) {
            return redirect()
                ->route('equipment.index')
                ->with('error', 'Equipment tidak ditemukan.');
        }

        // Cegah hapus jika masih digunakan buku
        if ($equipment->books_count > 0) {
            return redirect()
                ->route('equipment.index')
                ->with(
                    'error',
                    'Equipment "' .
                    $equipment->equipment_name .
                    '" tidak dapat dihapus karena masih digunakan oleh ' .
                    $equipment->books_count .
                    ' buku.'
                );
        }

        try {
            $equipmentName = $equipment->equipment_name;

            $equipment->delete();

            return redirect()
                ->route('equipment.index')
                ->with(
                    'success',
                    'Equipment "' .
                    $equipmentName .
                    '" berhasil dihapus.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->route('equipment.index')
                ->with(
                    'error',
                    'Gagal menghapus equipment: ' .
                    $e->getMessage()
                );
        }
    }
}