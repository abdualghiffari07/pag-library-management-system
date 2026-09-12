<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\VisitorCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class VisitorGuestController extends Controller
{
    // Pendaftaran pengunjung
    public function store(Request $request)
    {
        $request->session()->flash('active_form', 'register');

        $category = $request->input('visitor_category');
        $name = $request->input('visitor_name');
        $number = $request->input('employee_number');
        $phone = $request->input('phone_number');

        $request->merge([
            'visitor_category' => is_string($category)
                ? strtolower(trim($category))
                : $category,
            'visitor_name' => is_string($name)
                ? $this->cleanName($name)
                : $name,
            'employee_number' => is_string($number)
                ? trim($number)
                : $number,
            'phone_number' => is_string($phone)
                ? preg_replace('/[\s().-]+/', '', trim($phone))
                : $phone,
        ]);

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
            'phone_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^\+?[0-9]{8,15}$/',
            ],
            'employee_number' => [
                'required',
                'string',
                'max:100',
                Rule::when(
                    $request->input('visitor_category') === 'tamu',
                    ['regex:/^[0-9]{16}$/']
                ),
                Rule::unique('visitors', 'employee_number')
                    ->where(function ($query) use ($request) {
                        $query->where(
                            'visitor_category',
                            $request->input('visitor_category')
                        );
                    }),
            ],
            'profile_photo' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'visitor_category.required' => 'Kategori pengunjung wajib dipilih.',
            'visitor_category.in' => 'Kategori pengunjung tidak valid.',
            'visitor_name.required' => 'Nama lengkap wajib diisi.',
            'visitor_name.min' => 'Nama lengkap minimal 3 karakter.',
            'visitor_name.max' => 'Nama lengkap maksimal 255 karakter.',
            'phone_number.required' => 'Nomor HP wajib diisi.',
            'phone_number.regex' => 'Nomor HP harus terdiri dari 8–15 digit dan boleh diawali +.',
            'phone_number.max' => 'Nomor HP terlalu panjang.',
            'employee_number.required' => 'Nomor identitas wajib diisi.',
            'employee_number.max' => 'Nomor identitas maksimal 100 karakter.',
            'employee_number.regex' => 'No KTP harus terdiri dari tepat 16 digit.',
            'employee_number.unique' => 'Nomor identitas tersebut sudah digunakan pada kategori yang sama.',
            'profile_photo.required' => 'Foto profil wajib diunggah.',
            'profile_photo.file' => 'Foto profil harus berupa file.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WebP.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 5 MB.',
        ]);

        $profilePath = null;

        try {
            // Simpan foto pada disk privat
            $profilePath = $request->file('profile_photo')
                ->store('visitors/profiles', 'local');

            if (!$profilePath) {
                throw new \RuntimeException('Foto profil gagal disimpan.');
            }

            // Simpan data master pengunjung
            DB::transaction(function () use ($validated, $profilePath) {
                Visitor::create([
                    'visitor_category' => $validated['visitor_category'],
                    'visitor_name' => $validated['visitor_name'],
                    'employee_number' => $validated['employee_number'],
                    'phone_number' => $validated['phone_number'],
                    'profile_photo' => $profilePath,
                ]);
            });

            return redirect()
                ->route('visitors.register')
                ->with(
                    'register_success',
                    'Pendaftaran berhasil. Silakan gunakan menu Masuk Pengunjung untuk melakukan kunjungan.'
                )
                ->with('active_form', 'checkin')
                ->with('registered_visitor', [
                    'visitor_category' => $validated['visitor_category'],
                    'employee_number' => $validated['employee_number'],
                ]);

        } catch (QueryException $e) {
            $this->deleteUploadedFile($profilePath);

            // Duplikasi nomor identitas pada SQL Server
            if (in_array((int) ($e->errorInfo[1] ?? 0), [2601, 2627], true)) {
                return redirect()
                    ->route('visitors.register')
                    ->withInput($request->only([
                        'visitor_category',
                        'visitor_name',
                        'employee_number',
                        'phone_number',
                    ]))
                    ->withErrors([
                        'employee_number' => 'Nomor identitas tersebut sudah terdaftar.',
                    ], 'register')
                    ->with('active_form', 'register');
            }

            report($e);

            return redirect()
                ->route('visitors.register')
                ->withInput($request->only([
                    'visitor_category',
                    'visitor_name',
                    'employee_number',
                    'phone_number',
                ]))
                ->withErrors([
                    'profile_photo' => 'Pendaftaran gagal diproses. Silakan coba kembali.',
                ], 'register')
                ->with('active_form', 'register');

        } catch (\Throwable $e) {
            $this->deleteUploadedFile($profilePath);
            report($e);

            return redirect()
                ->route('visitors.register')
                ->withInput($request->only([
                    'visitor_category',
                    'visitor_name',
                    'employee_number',
                    'phone_number',
                ]))
                ->withErrors([
                    'profile_photo' => 'Pendaftaran gagal diproses. Silakan coba kembali.',
                ], 'register')
                ->with('active_form', 'register');
        }
    }

    // Check-in pengunjung
    public function checkIn(Request $request)
    {
        $request->session()->flash('active_form', 'checkin');

        $category = $request->input('checkin_category');
        $number = $request->input('checkin_number');

        $request->merge([
            'checkin_category' => is_string($category)
                ? strtolower(trim($category))
                : $category,
            'checkin_number' => is_string($number)
                ? trim($number)
                : $number,
        ]);

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
            'selfie' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'checkin_category.required' => 'Kategori pengunjung wajib dipilih.',
            'checkin_category.in' => 'Kategori pengunjung tidak valid.',
            'checkin_number.required' => 'Nomor identitas wajib diisi.',
            'checkin_number.max' => 'Nomor identitas maksimal 100 karakter.',
            'selfie.required' => 'Foto selfie wajib diambil atau diunggah.',
            'selfie.file' => 'Selfie harus berupa file.',
            'selfie.image' => 'File selfie harus berupa gambar.',
            'selfie.mimes' => 'Selfie harus berformat JPG, JPEG, PNG, atau WebP.',
            'selfie.max' => 'Ukuran selfie maksimal 5 MB.',
        ]);

        $category = $validated['checkin_category'];
        $number = $validated['checkin_number'];

        // Cari identitas pada data master
        $visitor = Visitor::where('visitor_category', $category)
            ->where('employee_number', $number)
            ->first();

        if (!$visitor) {
            return redirect()
                ->route('visitors.register')
                ->withInput($request->only([
                    'checkin_category',
                    'checkin_number',
                ]))
                ->withErrors([
                    'checkin_number' => 'Nomor identitas atau kategori tidak sesuai dengan data yang terdaftar. Silakan periksa kembali atau lakukan pendaftaran terlebih dahulu.',
                ], 'checkin')
                ->with('active_form', 'checkin');
        }

        $selfiePath = null;

        try {
            // Simpan selfie pada disk privat
            $selfiePath = $request->file('selfie')
                ->store('visitors/checkins', 'local');

            if (!$selfiePath) {
                throw new \RuntimeException('Foto selfie gagal disimpan.');
            }

        // Simpan riwayat kunjungan
        $checkedInAt = now();

        DB::transaction(function () use ($visitor, $selfiePath, $checkedInAt) {
            VisitorCheckin::create([
                'visitor_id' => $visitor->visitor_id,
                'selfie_path' => $selfiePath,
                'checked_in_at' => $checkedInAt,
            ]);
        });

        return redirect()
            ->route('visitors.register')
            ->with('checkin_success', 'Kunjungan berhasil dicatat.')
            ->with('checkin_receipt', [
                'name' => $visitor->visitor_name,
                'date' => $checkedInAt->locale('id')->translatedFormat('l, d F Y'),
                'time' => $checkedInAt->format('H:i'),
            ])
            ->with('active_form', 'checkin');

        } catch (\Throwable $e) {
            $this->deleteUploadedFile($selfiePath);
            report($e);

            return redirect()
                ->route('visitors.register')
                ->withInput($request->only([
                    'checkin_category',
                    'checkin_number',
                ]))
                ->withErrors([
                    'selfie' => 'Check-in gagal diproses. Silakan coba kembali.',
                ], 'checkin')
                ->with('active_form', 'checkin');
        }
    }

    // Rapikan nama
    private function cleanName(string $name): string
    {
        return preg_replace('/\s+/u', ' ', trim($name)) ?? trim($name);
    }

    // Bersihkan file jika penyimpanan database gagal
    private function deleteUploadedFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        try {
            Storage::disk('local')->delete($path);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}