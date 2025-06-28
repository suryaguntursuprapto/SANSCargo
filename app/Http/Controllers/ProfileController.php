<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        return view('pengaturan.profile', compact('user'));
    }
    
    /**
     * Menampilkan halaman edit profil
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        return view('pengaturan.edit', compact('user'));
    }

    /**
     * Update profil pengguna
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'nomor_telepon' => 'required|string|max:20',
                'alamat' => 'nullable|string',
                'branch' => 'nullable|string',
            ]);

            // Ambil user yang sedang login
            $user = Auth::user();

            // Update data user
            $user->nama_lengkap = $validated['nama_lengkap'];
            $user->email = $validated['email'];
            $user->nomor_telepon = $validated['nomor_telepon'];

            // Opsional fields
            if (isset($validated['alamat'])) {
                $user->alamat = $validated['alamat'];
            }
            
            if (isset($validated['branch'])) {
                $user->branch = $validated['branch'];
            }
            
            $user->save();

            // Redirect kembali ke halaman profil dengan pesan sukses
            return redirect()->route('pengaturan.profile')
                ->with('success', 'Profil pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error saving profile: ' . $e->getMessage());
            return redirect()->route('pengaturan.edit')
                ->with('error', 'Terjadi kesalahan saat menyimpan profil: ' . $e->getMessage());
        }
    }
    
    /**
     * Update foto profil pengguna
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfileImage(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Jika request berisi perintah untuk menghapus foto
            if ($request->has('remove_image')) {
                // Hapus file lama jika ada
                if ($user->profile_image && Storage::disk('public')->exists('profile_images/' . $user->profile_image)) {
                    Storage::disk('public')->delete('profile_images/' . $user->profile_image);
                }
                
                // Set profile_image menjadi null
                $user->profile_image = null;
                $user->save();
                
                return redirect()->back()
                    ->with('success', 'Foto profil berhasil dihapus.');
            }
            
            // Validasi file
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,gif|max:2048',
            ]);
            
            // Hapus file lama jika ada
            if ($user->profile_image && Storage::disk('public')->exists('profile_images/' . $user->profile_image)) {
                Storage::disk('public')->delete('profile_images/' . $user->profile_image);
            }
            
            // Generate nama file unik
            $fileName = Str::uuid() . '.' . $request->file('profile_image')->getClientOriginalExtension();
            
            // Simpan file baru
            $request->file('profile_image')->storeAs('profile_images', $fileName, 'public');
            
            // Update database
            $user->profile_image = $fileName;
            $user->save();
            
            return redirect()->back()
                ->with('success', 'Foto profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating profile image: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload foto profil: ' . $e->getMessage());
        }
    }
}