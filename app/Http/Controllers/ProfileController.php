<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show form for changing password.
     */
    public function editPassword()
    {
        if (auth()->user()->isKepalaSppg()) {
            return view('kepala-sppg.profile.change-password');
        }
        return view('admin.profile.change-password');
    }

    /**
     * Update the logged in user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
            'password.different'        => 'Password baru tidak boleh sama dengan password saat ini.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password Anda berhasil diperbarui!');
    }
}
