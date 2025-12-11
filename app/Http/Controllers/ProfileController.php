<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
    {
        $role = $request->user()->role->name;

        $viewMap = [
            'mahasiswa' => 'mahasiswa.akun',
            'admin' => 'admin.akun',
            'kaprodi' => 'kaprodi.akun',
            'dospem' => 'dospem.profile',
            'koordinator_ta' => 'koordinator_ta.profile',
            'koordinator_tefa' => 'koordinator_tefa.profile',
            'koordinator_story_conference' => 'koordinator_story_conference.profile',
            'dosen_penguji' => 'dosen_penguji.profile',
        ];

        $view = $viewMap[$role] ?? 'profile'; // Default to a generic profile view if role not found

        return view($view);
    }

    /**
     * Update the authenticated user's profile (name, email, nim) and optionally password.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nim' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'birthdate' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update basic fields
        $user->name = $data['name'];

        if ($user->email !== $data['email']) {
            $user->email = $data['email'];
            $user->email_verified_at = null;
        }

        // If NIM is a related mahasiswa model, try to save it there; otherwise ignore.
        if (isset($data['nim']) && (method_exists($user, 'mahasiswa') || $user->relationLoaded('mahasiswa'))) {
            try {
                if ($user->mahasiswa) {
                    $user->mahasiswa->nim = $data['nim'];
                    $user->mahasiswa->save();
                }
            } catch (\Throwable $e) {
                // ignore if mahasiswa relation doesn't exist or can't be saved
            }
        }

        // Update additional profile fields
        if (array_key_exists('phone', $data)) {
            $user->phone = $data['phone'];
        }
        if (array_key_exists('birthdate', $data)) {
            $user->birthdate = $data['birthdate'];
        }
        if (array_key_exists('address', $data)) {
            $user->address = $data['address'];
        }

        // Update password if provided: require current password to match
        if (!empty($data['new_password'])) {
            if (empty($data['current_password']) || !Hash::check($data['current_password'], $user->password)) {
                return Redirect::back()
                    ->withErrors(['current_password' => 'Password lama tidak cocok.'])
                    ->withInput();
            }

            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        return Redirect::back()->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Upload and save profile photo for authenticated user.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = Auth::user();

        $path = $request->file('avatar')->store('avatars', 'public');

        // delete previous avatar if exists
        try {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $user->avatar = $path;
        $user->save();

        return Redirect::back()->with('status', 'Foto profil berhasil diunggah.');
    }

    /**
     * Save theme preference to user profile (server-side) if user authenticated.
     */
    public function saveTheme(Request $request)
    {
        $request->validate(['theme' => ['required', 'in:light,dark']]);

        $user = Auth::user();
        if ($user) {
            $user->theme = $request->input('theme');
            $user->save();
        }

        return response()->json(['status' => 'ok']);
    }
}
