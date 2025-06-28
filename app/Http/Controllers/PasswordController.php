<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    /**
     * Menampilkan halaman ubah password
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('pengaturan.password');
    }

    /**
     * Update password pengguna
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ], [
                'current_password.required' => 'Password saat ini harus diisi',
                'password.required' => 'Password baru harus diisi',
                'password.min' => 'Password baru minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'password_confirmation.required' => 'Konfirmasi password harus diisi',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Ambil pengguna yang sedang login
            $user = Auth::user();

            // Verifikasi password saat ini
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Password saat ini tidak cocok'])
                    ->withInput();
            }

            // Update password
            $user->password = Hash::make($request->password);
            $user->save();

            // Redirect dengan pesan sukses
            return redirect()->route('pengaturan.password.edit')
                ->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah password: ' . $e->getMessage());
        }
    }
}