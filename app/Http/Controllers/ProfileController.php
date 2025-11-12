<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
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
}
