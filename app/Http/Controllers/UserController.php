<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sppg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('sppg')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $sppgs = Sppg::orderBy('nama_sppg')->get();
        return view('admin.users.create', compact('sppgs'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:admin,kepala_sppg'],
            'sppg_id'  => ['nullable', 'required_if:role,kepala_sppg', 'exists:sppg,id'],
        ], [
            'name.required'        => 'Nama wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.unique'         => 'Email sudah terdaftar.',
            'password.required'    => 'Password wajib diisi.',
            'password.min'         => 'Password minimal 8 karakter.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
            'role.required'        => 'Role wajib dipilih.',
            'sppg_id.required_if'  => 'Unit SPPG wajib dipilih untuk akun Kepala SPPG.',
            'sppg_id.exists'       => 'Pilihan SPPG tidak valid.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'sppg_id'  => $validated['role'] === 'kepala_sppg' ? $validated['sppg_id'] : null,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $sppgs = Sppg::orderBy('nama_sppg')->get();
        return view('admin.users.edit', compact('user', 'sppgs'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:admin,kepala_sppg'],
            'sppg_id'  => ['nullable', 'required_if:role,kepala_sppg', 'exists:sppg,id'],
        ], [
            'name.required'        => 'Nama wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.unique'         => 'Email sudah digunakan oleh akun lain.',
            'password.min'         => 'Password minimal 8 karakter.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
            'role.required'        => 'Role wajib dipilih.',
            'sppg_id.required_if'  => 'Unit SPPG wajib dipilih untuk akun Kepala SPPG.',
            'sppg_id.exists'       => 'Pilihan SPPG tidak valid.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->sppg_id = $validated['role'] === 'kepala_sppg' ? $validated['sppg_id'] : null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
