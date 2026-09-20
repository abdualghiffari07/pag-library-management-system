<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Profile
    public function index()
    {
        $user = auth()->user();

        $user->loadMissing('role');

        return view('pages.profile.index', [
            'title' => 'Profile Administrator',
            'user' => $user,
        ]);
    }

    // Update profile
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update(
            $request->validated()
        );

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Informasi profile berhasil diperbarui.'
            );
    }

    // Update photo
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $user = $request->user();

        if (
            $user->profile_photo
            && Storage::disk('local')->exists(
                $user->profile_photo
            )
        ) {
            Storage::disk('local')->delete(
                $user->profile_photo
            );
        }

        $path = $request
            ->file('profile_photo')
            ->store(
                'profile/admin',
                'local'
            );

        $user->update([
            'profile_photo' => $path,
        ]);

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Foto profile berhasil diperbarui.'
            );
    }

    // Show photo
    public function photo(Request $request)
    {
        $user = $request->user();

        abort_if(
            !$user->profile_photo,
            404
        );

        abort_unless(
            Storage::disk('local')->exists(
                $user->profile_photo
            ),
            404
        );

        return response()->file(
            Storage::disk('local')->path(
                $user->profile_photo
            ),
            [
                'Cache-Control' =>
                    'private, max-age=3600',
            ]
        );
    }

    // Delete photo
    public function deletePhoto(Request $request)
    {
        $user = $request->user();

        if (
            $user->profile_photo
            && Storage::disk('local')->exists(
                $user->profile_photo
            )
        ) {
            Storage::disk('local')->delete(
                $user->profile_photo
            );
        }

        $user->update([
            'profile_photo' => null,
        ]);

        return redirect()
            ->route('profile')
            ->with(
                'success',
                'Foto profile berhasil dihapus.'
            );
    }

    // Update password
    public function updatePassword(
        UpdatePasswordRequest $request
    ) {
        $user = $request->user();

        if (
            !Hash::check(
                $request->current_password,
                $user->password_hash
            )
        ) {
            return back()
                ->withErrors(
                    [
                        'current_password' =>
                            'Password saat ini tidak sesuai.',
                    ],
                    'passwordUpdate'
                )
                ->withInput();
        }

        if (
            Hash::check(
                $request->password,
                $user->password_hash
            )
        ) {
            return back()
                ->withErrors(
                    [
                        'password' =>
                            'Password baru harus berbeda dari password saat ini.',
                    ],
                    'passwordUpdate'
                );
        }

        $user->update([
            'password_hash' => Hash::make(
                $request->password
            ),
            'must_change_password' => false,
        ]);

        return redirect()
            ->route('profile')
            ->with(
                'password_success',
                'Password berhasil diperbarui.'
            );
    }
}