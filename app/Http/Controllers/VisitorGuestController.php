<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\VisitorCheckin;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VisitorGuestController extends Controller
{
    // Pendaftaran pengunjung
    public function store(Request $request)
    {
        $request->session()->flash('active_form', 'register');

        $this->normalizeRegistrationInput($request);

        $validated = $request->validateWithBag(
            'register',
            [
                'visitor_category' => [
                    'required',
                    Rule::in([
                        'pekerja',
                        'mahasiswa',
                        'tamu',
                        'lainnya',
                    ]),
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
                        [
                            'regex:/^[0-9]{16}$/',
                        ]
                    ),

                    Rule::unique(
                        'visitors',
                        'employee_number'
                    )->where(function ($query) use ($request) {
                        $query->where(
                            'visitor_category',
                            $request->input('visitor_category')
                        );
                    }),

                    Rule::when(
                        $request->input('visitor_category') === 'pekerja',
                        [
                            Rule::unique('users', 'nopek'),
                        ]
                    ),
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',

                    Rule::unique(
                        'visitors',
                        'email'
                    ),

                    Rule::when(
                        $request->input('visitor_category') === 'pekerja',
                        [
                            Rule::unique('users', 'email'),
                        ]
                    ),
                ],
                'profile_photo' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
            ],
            [
                'visitor_category.required' =>
                    'Kategori pengunjung wajib dipilih.',

                'visitor_category.in' =>
                    'Kategori pengunjung tidak valid.',

                'visitor_name.required' =>
                    'Nama lengkap wajib diisi.',

                'visitor_name.min' =>
                    'Nama lengkap minimal 3 karakter.',

                'visitor_name.max' =>
                    'Nama lengkap maksimal 255 karakter.',

                'phone_number.required' =>
                    'Nomor HP wajib diisi.',

                'phone_number.regex' =>
                    'Nomor HP harus terdiri dari 8–15 digit dan boleh diawali +.',

                'phone_number.max' =>
                    'Nomor HP terlalu panjang.',

                'employee_number.required' =>
                    'Nomor identitas wajib diisi.',

                'employee_number.max' =>
                    'Nomor identitas maksimal 100 karakter.',

                'employee_number.regex' =>
                    'No KTP harus terdiri dari tepat 16 digit.',

                'employee_number.unique' =>
                    'Nomor identitas tersebut sudah terdaftar.',

                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'email.max' =>
                    'Email maksimal 255 karakter.',

                'email.unique' =>
                    'Email tersebut sudah terdaftar.',

                'profile_photo.required' =>
                    'Foto profil wajib diunggah.',

                'profile_photo.file' =>
                    'Foto profil harus berupa file.',

                'profile_photo.image' =>
                    'File harus berupa gambar.',

                'profile_photo.mimes' =>
                    'Foto profil harus berformat JPG, JPEG, PNG, atau WebP.',

                'profile_photo.max' =>
                    'Ukuran foto profil maksimal 5 MB.',
            ]
        );

        $profilePath = null;

        try {
            // Simpan foto
            $profilePath = $request
                ->file('profile_photo')
                ->store(
                    'visitors/profiles',
                    'local'
                );

            if (!$profilePath) {
                throw new \RuntimeException(
                    'Foto profil gagal disimpan.'
                );
            }

            DB::transaction(function () use (
                $validated,
                $profilePath
            ) {
                // Simpan pengunjung
                $visitor = Visitor::create([
                    'visitor_category' =>
                        $validated['visitor_category'],

                    'visitor_name' =>
                        $validated['visitor_name'],

                    'employee_number' =>
                        $validated['employee_number'],

                    'email' =>
                        $validated['email'],

                    'phone_number' =>
                        $validated['phone_number'],

                    'profile_photo' =>
                        $profilePath,

                    'is_active' =>
                        true,
                ]);

                // Buat akun pekerja
                if (
                    $validated['visitor_category']
                    === 'pekerja'
                ) {
                    $this->createWorkerAccount(
                        $visitor,
                        $validated
                    );
                }
            });

            $message =
                $validated['visitor_category'] === 'pekerja'
                    ? 'Pendaftaran berhasil. Akun pekerja telah dibuat. Gunakan email sebagai akun login dan No. Pekerja sebagai password awal.'
                    : 'Pendaftaran berhasil. Silakan gunakan menu Masuk Pengunjung untuk melakukan kunjungan.';

            return redirect()
                ->route('visitors.register')
                ->with(
                    'register_success',
                    $message
                )
                ->with(
                    'active_form',
                    'checkin'
                )
                ->with(
                    'registered_visitor',
                    [
                        'visitor_category' =>
                            $validated['visitor_category'],

                        'employee_number' =>
                            $validated['employee_number'],

                        'email' =>
                            $validated['email'],
                    ]
                );

        } catch (QueryException $e) {
            $this->deleteUploadedFile(
                $profilePath
            );

            // Duplikasi SQL Server
            if (
                in_array(
                    (int) ($e->errorInfo[1] ?? 0),
                    [2601, 2627],
                    true
                )
            ) {
                return redirect()
                    ->route('visitors.register')
                    ->withInput(
                        $this->registrationInput(
                            $request
                        )
                    )
                    ->withErrors(
                        [
                            'employee_number' =>
                                'Nomor identitas atau email tersebut sudah terdaftar.',
                        ],
                        'register'
                    )
                    ->with(
                        'active_form',
                        'register'
                    );
            }

            report($e);

            return redirect()
                ->route('visitors.register')
                ->withInput(
                    $this->registrationInput(
                        $request
                    )
                )
                ->withErrors(
                    [
                        'profile_photo' =>
                            'Pendaftaran gagal diproses. Silakan coba kembali.',
                    ],
                    'register'
                )
                ->with(
                    'active_form',
                    'register'
                );

        } catch (\Throwable $e) {
            $this->deleteUploadedFile(
                $profilePath
            );

            report($e);

            return redirect()
                ->route('visitors.register')
                ->withInput(
                    $this->registrationInput(
                        $request
                    )
                )
                ->withErrors(
                    [
                        'profile_photo' =>
                            'Pendaftaran gagal diproses. Silakan coba kembali.',
                    ],
                    'register'
                )
                ->with(
                    'active_form',
                    'register'
                );
        }
    }

    // Check-in pengunjung
    public function checkIn(Request $request)
    {
        $request->session()->flash(
            'active_form',
            'checkin'
        );

        $this->normalizeCheckInInput(
            $request
        );

        $validated = $request->validateWithBag(
            'checkin',
            [
                'checkin_category' => [
                    'required',
                    Rule::in([
                        'pekerja',
                        'mahasiswa',
                        'tamu',
                        'lainnya',
                    ]),
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
            ],
            [
                'checkin_category.required' =>
                    'Kategori pengunjung wajib dipilih.',

                'checkin_category.in' =>
                    'Kategori pengunjung tidak valid.',

                'checkin_number.required' =>
                    'Nomor identitas wajib diisi.',

                'checkin_number.max' =>
                    'Nomor identitas maksimal 100 karakter.',

                'selfie.required' =>
                    'Foto selfie wajib diambil atau diunggah.',

                'selfie.file' =>
                    'Selfie harus berupa file.',

                'selfie.image' =>
                    'File selfie harus berupa gambar.',

                'selfie.mimes' =>
                    'Selfie harus berformat JPG, JPEG, PNG, atau WebP.',

                'selfie.max' =>
                    'Ukuran selfie maksimal 5 MB.',
            ]
        );

        // Cari pengunjung
        $visitor = Visitor::where(
            'visitor_category',
            $validated['checkin_category']
        )
            ->where(
                'employee_number',
                $validated['checkin_number']
            )
            ->first();

        if (!$visitor) {
            return redirect()
                ->route('visitors.register')
                ->withInput(
                    $this->checkInInput(
                        $request
                    )
                )
                ->withErrors(
                    [
                        'checkin_number' =>
                            'Nomor identitas atau kategori tidak sesuai dengan data yang terdaftar. Silakan periksa kembali atau lakukan pendaftaran terlebih dahulu.',
                    ],
                    'checkin'
                )
                ->with(
                    'active_form',
                    'checkin'
                );
        }

        // Cek status
        if (!$visitor->is_active) {
            return redirect()
                ->route('visitors.register')
                ->withInput(
                    $this->checkInInput(
                        $request
                    )
                )
                ->withErrors(
                    [
                        'checkin_number' =>
                            'Data pengunjung ini sudah dinonaktifkan dan tidak dapat melakukan check-in. Silakan hubungi administrator.',
                    ],
                    'checkin'
                )
                ->with(
                    'active_form',
                    'checkin'
                );
        }

        $selfiePath = null;

        try {
            // Simpan selfie
            $selfiePath = $request
                ->file('selfie')
                ->store(
                    'visitors/checkins',
                    'local'
                );

            if (!$selfiePath) {
                throw new \RuntimeException(
                    'Foto selfie gagal disimpan.'
                );
            }

            $checkedInAt = now();

            // Simpan kunjungan
            DB::transaction(function () use (
                $visitor,
                $selfiePath,
                $checkedInAt
            ) {
                VisitorCheckin::create([
                    'visitor_id' =>
                        $visitor->visitor_id,

                    'selfie_path' =>
                        $selfiePath,

                    'checked_in_at' =>
                        $checkedInAt,
                ]);
            });

            return redirect()
                ->route('visitors.register')
                ->with(
                    'checkin_success',
                    'Kunjungan berhasil dicatat.'
                )
                ->with(
                    'checkin_receipt',
                    [
                        'name' =>
                            $visitor->visitor_name,

                        'date' =>
                            $checkedInAt
                                ->locale('id')
                                ->translatedFormat(
                                    'l, d F Y'
                                ),

                        'time' =>
                            $checkedInAt
                                ->format('H:i'),
                    ]
                )
                ->with(
                    'active_form',
                    'checkin'
                );

        } catch (\Throwable $e) {
            $this->deleteUploadedFile(
                $selfiePath
            );

            report($e);

            return redirect()
                ->route('visitors.register')
                ->withInput(
                    $this->checkInInput(
                        $request
                    )
                )
                ->withErrors(
                    [
                        'selfie' =>
                            'Check-in gagal diproses. Silakan coba kembali.',
                    ],
                    'checkin'
                )
                ->with(
                    'active_form',
                    'checkin'
                );
        }
    }

    // Buat akun pekerja
    private function createWorkerAccount(
        Visitor $visitor,
        array $validated
    ): void {
        $workerRole = DB::table('roles')
            ->where(
                'role_name',
                'pekerja'
            )
            ->first();

        if (!$workerRole) {
            throw new \RuntimeException(
                'Role pekerja belum tersedia.'
            );
        }

        DB::table('users')->insert([
            'role_id' =>
                $workerRole->role_id,

            'visitor_id' =>
                $visitor->visitor_id,

            'nopek' =>
                $validated['employee_number'],

            'name' =>
                $validated['visitor_name'],

            'email' =>
                $validated['email'],

            'password_hash' =>
                Hash::make(
                    $validated['employee_number']
                ),

            'function_name' =>
                'Pekerja',

            'is_active' =>
                true,

            // Ganti password bersifat opsional
            'must_change_password' =>
                false,

            'created_at' =>
                now(),

            'updated_at' =>
                now(),
        ]);
    }

    // Normalisasi pendaftaran
    private function normalizeRegistrationInput(
        Request $request
    ): void {
        $category = $request->input(
            'visitor_category'
        );

        $name = $request->input(
            'visitor_name'
        );

        $number = $request->input(
            'employee_number'
        );

        $phone = $request->input(
            'phone_number'
        );

        $email = $request->input(
            'email'
        );

        $request->merge([
            'visitor_category' =>
                is_string($category)
                    ? strtolower(trim($category))
                    : $category,

            'visitor_name' =>
                is_string($name)
                    ? $this->cleanName($name)
                    : $name,

            'employee_number' =>
                is_string($number)
                    ? trim($number)
                    : $number,

            'phone_number' =>
                is_string($phone)
                    ? preg_replace(
                        '/[\s().-]+/',
                        '',
                        trim($phone)
                    )
                    : $phone,

            'email' =>
                is_string($email)
                    ? strtolower(trim($email))
                    : $email,
        ]);
    }

    // Normalisasi check-in
    private function normalizeCheckInInput(
        Request $request
    ): void {
        $category = $request->input(
            'checkin_category'
        );

        $number = $request->input(
            'checkin_number'
        );

        $request->merge([
            'checkin_category' =>
                is_string($category)
                    ? strtolower(trim($category))
                    : $category,

            'checkin_number' =>
                is_string($number)
                    ? trim($number)
                    : $number,
        ]);
    }

    // Input pendaftaran
    private function registrationInput(
        Request $request
    ): array {
        return $request->only([
            'visitor_category',
            'visitor_name',
            'employee_number',
            'email',
            'phone_number',
        ]);
    }

    // Input check-in
    private function checkInInput(
        Request $request
    ): array {
        return $request->only([
            'checkin_category',
            'checkin_number',
        ]);
    }

    // Rapikan nama
    private function cleanName(
        string $name
    ): string {
        return preg_replace(
            '/\s+/u',
            ' ',
            trim($name)
        ) ?? trim($name);
    }

    // Hapus file gagal
    private function deleteUploadedFile(
        ?string $path
    ): void {
        if (!$path) {
            return;
        }

        try {
            Storage::disk(
                'local'
            )->delete(
                $path
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }
}