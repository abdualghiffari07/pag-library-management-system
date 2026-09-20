<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $user->user_id,
                    'user_id'
                ),
            ],

            'nopek' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique(
                    'users',
                    'nopek'
                )->ignore(
                    $user->user_id,
                    'user_id'
                ),
            ],

            'function_name' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>
                'Nama Administrator wajib diisi.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email sudah digunakan oleh akun lain.',

            'nopek.unique' =>
                'No. Pekerja sudah digunakan oleh akun lain.',
        ];
    }
}