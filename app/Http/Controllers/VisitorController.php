<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Simpan data pengunjung dari landing page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => [
                'required',
                'string',
                'max:255',
            ],

            'employee_number' => [
                'nullable',
                'string',
                'max:100',
            ],
        ], [
            'visitor_name.required' =>
                'Nama pengunjung wajib diisi.',

            'visitor_name.max' =>
                'Nama pengunjung maksimal 255 karakter.',

            'employee_number.max' =>
                'No. pekerja maksimal 100 karakter.',
        ]);


        // Simpan data pengunjung
        Visitor::create($validated);


        // Kembali ke halaman sebelumnya.
        // Posisi scroll akan dipertahankan oleh JavaScript.
        return back()->with([
            'success' => 'Data pengunjung berhasil didaftarkan.',
            'visitor_form_submitted' => true,
        ]);
    }
}