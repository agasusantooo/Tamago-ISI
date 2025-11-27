<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Tampilkan halaman register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nim' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Tentukan role berdasarkan domain email
        $email = $request->email;
        $roleName = $this->determineRoleFromEmail($email);

        // Validasi NIM untuk mahasiswa
        if ($roleName === 'mahasiswa') {
            $nim = $request->input('nim');
            if (!$nim) {
                return back()->withErrors(['nim' => 'NIM harus diisi untuk mahasiswa'])->withInput();
            }

            // Cek apakah NIM ada di tabel mahasiswa
            $existingMahasiswa = \App\Models\Mahasiswa::where('nim', $nim)->first();
            if (!$existingMahasiswa) {
                // Jika tidak ditemukan, buatkan record Mahasiswa baru (user_id diset null).
                // Record ini nanti akan di-link ke user setelah user dibuat di bawah.
                $existingMahasiswa = \App\Models\Mahasiswa::create([
                    'nim' => $nim,
                    'nama' => $request->name,
                    'email' => $request->email,
                    'user_id' => null,
                ]);
            }

            // Cek apakah NIM sudah ter-link dengan user lain
            if ($existingMahasiswa->user_id !== null) {
                return back()->withErrors(['nim' => 'NIM sudah terdaftar dalam sistem.'])->withInput();
            }
        }

        // Ambil role dari database
        $role = \App\Models\Role::where('name', $roleName)->first();
        if (!$role) {
            return back()->withErrors(['email' => 'Role untuk domain email ini tidak ditemukan.'])->withInput();
        }

        // Buat user baru dengan role_id
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        // Buat record sesuai role
        $this->createRoleSpecificRecord($user, $roleName, $request);

        // Auto login setelah register
        Auth::login($user);

        return redirect('dashboard')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Tentukan role berdasarkan domain email
     */
    private function determineRoleFromEmail($email)
    {
        if (str_ends_with($email, '@student.isi.ac.id')) {
            return 'mahasiswa';
        } elseif (str_ends_with($email, '@lecturer.isi.ac.id')) {
            return 'dospem';
        } elseif (str_ends_with($email, '@kaprodi.isi.ac.id')) {
            return 'kaprodi';
        } elseif (str_ends_with($email, '@koordinator.isi.ac.id')) {
            return 'koordinator_ta';
        } elseif (str_ends_with($email, '@penguji.isi.ac.id')) {
            return 'dosen_penguji';
        } else {
            return 'admin'; // default
        }
    }

    /**
     * Buat record sesuai role
     */
    private function createRoleSpecificRecord($user, $roleName, $request)
    {
        switch ($roleName) {
            case 'mahasiswa':
                $nim = $request->input('nim');
                // Link existing mahasiswa record dengan user
                if ($nim) {
                    $mahasiswa = \App\Models\Mahasiswa::where('nim', $nim)->first();
                    if ($mahasiswa) {
                        $mahasiswa->update([
                            'user_id' => $user->id,
                            'nama' => $user->name, // Update nama jika berbeda
                        ]);
                    }
                }
                break;
            case 'dospem':
                $emailParts = explode('@', $user->email);
                $identifier = $emailParts[0];
                \App\Models\Dosen::create([
                    'nidn' => $identifier,
                    'nama' => $user->name,
                    'jabatan' => 'Dosen Pembimbing',
                    'status' => 'Aktif',
                ]);
                break;
            case 'kaprodi':
                $emailParts = explode('@', $user->email);
                $identifier = $emailParts[0];
                \App\Models\Dosen::create([
                    'nidn' => $identifier,
                    'nama' => $user->name,
                    'jabatan' => 'Kaprodi',
                    'status' => 'Aktif',
                ]);
                break;
            case 'koordinator_ta':
                $emailParts = explode('@', $user->email);
                $identifier = $emailParts[0];
                \App\Models\Dosen::create([
                    'nidn' => $identifier,
                    'nama' => $user->name,
                    'jabatan' => 'Koordinator TA',
                    'status' => 'Aktif',
                ]);
                break;
            case 'dosen_penguji':
                $emailParts = explode('@', $user->email);
                $identifier = $emailParts[0];
                \App\Models\Dosen::create([
                    'nidn' => $identifier,
                    'nama' => $user->name,
                    'jabatan' => 'Dosen Penguji',
                    'status' => 'Aktif',
                ]);
                break;
            default:
                // admin tidak perlu record tambahan
                break;
        }
    }
}