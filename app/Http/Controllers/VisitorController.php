<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VisitorController extends Controller
{
    // Pendaftaran pengunjung
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('register', [
            'visitor_category' => [
                'required',
                Rule::in(['pekerja', 'mahasiswa', 'tamu', 'lainnya']),
            ],
            'visitor_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'employee_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('visitors', 'employee_number')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'visitor_category',
                            $request->input('visitor_category')
                        );
                    }),
            ],
        ], [
            'visitor_category.required' => 'Kategori pengunjung wajib dipilih.',
            'visitor_category.in' => 'Kategori pengunjung tidak valid.',
            'visitor_name.required' => 'Nama lengkap wajib diisi.',
            'visitor_name.min' => 'Nama lengkap minimal 3 karakter.',
            'visitor_name.max' => 'Nama lengkap maksimal 255 karakter.',
            'employee_number.required' => 'Nomor identitas wajib diisi.',
            'employee_number.max' => 'Nomor identitas maksimal 100 karakter.',
            'employee_number.unique' => 'Nomor identitas tersebut sudah digunakan pada kategori pengunjung yang sama.',
        ]);

        Visitor::create([
            'visitor_category' => $validated['visitor_category'],
            'visitor_name' => $this->cleanName($validated['visitor_name']),
            'employee_number' => trim($validated['employee_number']),
        ]);

        return redirect()
            ->route('visitors.register')
            ->with(
                'register_success',
                'Pendaftaran berhasil. Silakan gunakan menu Masuk Pengunjung untuk kunjungan berikutnya.'
            )
            ->with('active_form', 'checkin');
    }

    // Verifikasi pengunjung
    public function checkIn(Request $request)
    {
        $validated = $request->validateWithBag('checkin', [
            'checkin_category' => [
                'required',
                Rule::in(['pekerja', 'mahasiswa', 'tamu', 'lainnya']),
            ],
            'checkin_number' => [
                'required',
                'string',
                'max:100',
            ],
            'checkin_name' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'checkin_category.required' => 'Kategori pengunjung wajib dipilih.',
            'checkin_category.in' => 'Kategori pengunjung tidak valid.',
            'checkin_number.required' => 'Nomor identitas wajib diisi.',
            'checkin_number.max' => 'Nomor identitas maksimal 100 karakter.',
            'checkin_name.required' => 'Nama lengkap wajib diisi.',
            'checkin_name.max' => 'Nama lengkap maksimal 255 karakter.',
        ]);

        $category = $validated['checkin_category'];
        $number = trim($validated['checkin_number']);

        $visitor = Visitor::where('visitor_category', $category)
            ->where('employee_number', $number)
            ->first();

        // Nomor ada, tetapi kategori berbeda
        if (!$visitor) {
            $numberExists = Visitor::where('employee_number', $number)->exists();

            if ($numberExists) {
                return redirect()
                    ->route('visitors.register')
                    ->withInput()
                    ->withErrors([
                        'checkin_category' =>
                            'Kategori pengunjung tidak sesuai dengan nomor pekerja atau nomor identitas yang terdaftar.',
                    ], 'checkin')
                    ->with('active_form', 'checkin');
            }

            return redirect()
                ->route('visitors.register')
                ->withInput()
                ->withErrors([
                    'checkin_number' =>
                        'Nomor pekerja atau nomor identitas belum terdaftar. Silakan lakukan pendaftaran terlebih dahulu.',
                ], 'checkin')
                ->with('active_form', 'checkin');
        }

        $registeredName = $this->normalizeName($visitor->visitor_name);
        $enteredName = $this->normalizeName($validated['checkin_name']);

        // Nama harus sesuai
        if ($registeredName !== $enteredName) {
            return redirect()
                ->route('visitors.register')
                ->withInput()
                ->withErrors([
                    'checkin_name' =>
                        'Nama pengunjung tidak sesuai dengan nomor pekerja atau nomor identitas yang terdaftar.',
                ], 'checkin')
                ->with('active_form', 'checkin');
        }

        return redirect()
            ->route('visitors.register')
            ->with(
                'checkin_success',
                'Selamat datang, ' . $visitor->visitor_name . '. Data Anda berhasil diverifikasi.'
            )
            ->with('active_form', 'checkin');
    }

    // Rapikan nama
    private function cleanName(string $name): string
    {
        return preg_replace('/\s+/', ' ', trim($name));
    }

    // Normalisasi nama
    private function normalizeName(string $name): string
    {
        return mb_strtolower(
            $this->cleanName($name),
            'UTF-8'
        );
    }
}