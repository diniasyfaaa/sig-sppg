<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuHarian;
use App\Models\Sppg;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuHarianController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | KEPALA SPPG METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Tampilkan kelola menu harian oleh Kepala SPPG.
     */
    public function kepalaIndex()
    {
        $user = Auth::user();
        $sppg = $user->sppg;

        if (!$sppg) {
            return redirect()
                ->route('kepala.dashboard')
                ->with('error', 'Akun Anda belum ditautkan ke unit SPPG.');
        }

        $menus = MenuHarian::where('sppg_id', $sppg->id)
            ->orderBy('siklus_ke')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->get()
            ->groupBy('siklus_ke');

        return view('kepala-sppg.menu.index', compact('sppg', 'menus'));
    }

    /**
     * Form tambah menu harian baru oleh Kepala SPPG.
     */
    public function kepalaCreate()
    {
        $user = Auth::user();
        $sppg = $user->sppg;

        if (!$sppg) {
            return redirect()->route('kepala.dashboard')->with('error', 'Akun belum ditautkan ke unit SPPG.');
        }

        return view('kepala-sppg.menu.create', compact('sppg'));
    }

    /**
     * Simpan menu harian baru oleh Kepala SPPG.
     */
    public function kepalaStore(Request $request)
    {
        $user = Auth::user();
        $sppg = $user->sppg;

        if (!$sppg) {
            return redirect()->route('kepala.dashboard')->with('error', 'Akun belum ditautkan ke unit SPPG.');
        }

        $request->validate([
            'siklus_ke'    => 'required|integer|min:1|max:4',
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'menu_makanan' => 'required|string|max:255',
            'standar_gizi' => 'nullable|string|max:1000',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'siklus_ke.required'    => 'Pilih siklus minggu (Minggu ke-).',
            'hari.required'         => 'Pilih hari.',
            'menu_makanan.required' => 'Nama menu makanan wajib diisi.',
            'foto.image'            => 'File foto harus berupa gambar (JPG, JPEG, PNG).',
            'foto.max'              => 'Ukuran foto maksimal 3 MB.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('menu_harian', 'public');
        }

        MenuHarian::create([
            'sppg_id'      => $sppg->id,
            'siklus_ke'    => $request->siklus_ke,
            'hari'         => $request->hari,
            'menu_makanan' => $request->menu_makanan,
            'standar_gizi' => $request->standar_gizi,
            'foto'         => $fotoPath,
            'status'       => 'pending',
        ]);

        return redirect()
            ->route('kepala.menu.index')
            ->with('success', 'Menu harian berhasil diajukan! Menunggu peninjauan/persetujuan dari Administrator.');
    }

    /**
     * Form edit menu harian oleh Kepala SPPG.
     */
    public function kepalaEdit($id)
    {
        $user = Auth::user();
        $menu = MenuHarian::where('sppg_id', $user->sppg_id)->findOrFail($id);

        return view('kepala-sppg.menu.edit', compact('menu'));
    }

    /**
     * Update menu harian oleh Kepala SPPG (mengubah status ke pending untuk re-approval).
     */
    public function kepalaUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $menu = MenuHarian::where('sppg_id', $user->sppg_id)->findOrFail($id);

        $request->validate([
            'siklus_ke'    => 'required|integer|min:1|max:4',
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'menu_makanan' => 'required|string|max:255',
            'standar_gizi' => 'nullable|string|max:1000',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $fotoPath = $menu->foto;
        if ($request->hasFile('foto')) {
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }
            $fotoPath = $request->file('foto')->store('menu_harian', 'public');
        }

        $menu->update([
            'siklus_ke'       => $request->siklus_ke,
            'hari'            => $request->hari,
            'menu_makanan'    => $request->menu_makanan,
            'standar_gizi'    => $request->standar_gizi,
            'foto'            => $fotoPath,
            'status'          => 'pending',
            'alasan_penolakan'=> null,
        ]);

        return redirect()
            ->route('kepala.menu.index')
            ->with('success', 'Perubahan menu harian berhasil diajukan! Menunggu persetujuan Administrator.');
    }

    /**
     * Hapus menu harian oleh Kepala SPPG.
     */
    public function kepalaDestroy($id)
    {
        $user = Auth::user();
        $menu = MenuHarian::where('sppg_id', $user->sppg_id)->findOrFail($id);

        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();

        return redirect()
            ->route('kepala.menu.index')
            ->with('success', 'Data menu harian berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Daftar kelola menu harian admin.
     */
    public function adminIndex(Request $request, $sppg_id = null)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        $sppgIdFilter = $sppg_id ?? $request->get('sppg_id');

        $sppgs = Sppg::orderBy('nama_sppg')->get();

        $menus = MenuHarian::with('sppg.desa')
            ->when($sppgIdFilter, function ($q) use ($sppgIdFilter) {
                return $q->where('sppg_id', $sppgIdFilter);
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($search, function ($q) use ($search) {
                return $q->where('menu_makanan', 'like', "%{$search}%")
                         ->orWhereHas('sppg', function ($sq) use ($search) {
                             $sq->where('nama_sppg', 'like', "%{$search}%");
                         });
            })
            ->orderBy('sppg_id')
            ->orderBy('siklus_ke')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->paginate(15)
            ->withQueryString();

        $pendingCount = MenuHarian::where('status', 'pending')->count();
        $approvedCount = MenuHarian::where('status', 'disetujui')->count();

        return view('admin.menu.index', compact('menus', 'sppgs', 'sppgIdFilter', 'status', 'search', 'pendingCount', 'approvedCount'));
    }

    /**
     * Admin Approve menu harian.
     */
    public function adminApprove($id)
    {
        $menu = MenuHarian::findOrFail($id);
        $menu->update([
            'status' => 'disetujui',
            'alasan_penolakan' => null,
        ]);

        return back()->with('success', 'Menu harian berhasil DISETUJUI dan langsung tampil di halaman publik!');
    }

    /**
     * Admin Reject menu harian.
     */
    public function adminReject(Request $request, $id)
    {
        $menu = MenuHarian::findOrFail($id);

        $request->validate([
            'alasan_penolakan' => 'required|string|min:3|max:1000',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $menu->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Menu harian telah DITOLAK.');
    }

    /**
     * Form tambah menu harian langsung oleh Admin.
     */
    public function adminCreate(Request $request)
    {
        $sppg_id = $request->get('sppg_id');
        $sppgs = Sppg::orderBy('nama_sppg')->get();

        return view('admin.menu.create', compact('sppgs', 'sppg_id'));
    }

    /**
     * Simpan menu harian oleh Admin (langsung disetujui).
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'sppg_id'      => 'required|exists:sppg,id',
            'siklus_ke'    => 'required|integer|min:1|max:4',
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'menu_makanan' => 'required|string|max:255',
            'standar_gizi' => 'nullable|string|max:1000',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('menu_harian', 'public');
        }

        MenuHarian::create([
            'sppg_id'      => $request->sppg_id,
            'siklus_ke'    => $request->siklus_ke,
            'hari'         => $request->hari,
            'menu_makanan' => $request->menu_makanan,
            'standar_gizi' => $request->standar_gizi,
            'foto'         => $fotoPath,
            'status'       => 'disetujui',
        ]);

        return redirect()
            ->route('admin.menu.index', ['sppg_id' => $request->sppg_id])
            ->with('success', 'Menu harian berhasil ditambahkan dan langsung dipublikasikan!');
    }

    /**
     * Form edit menu harian oleh Admin.
     */
    public function adminEdit($id)
    {
        $menu = MenuHarian::with('sppg')->findOrFail($id);
        $sppgs = Sppg::orderBy('nama_sppg')->get();

        return view('admin.menu.edit', compact('menu', 'sppgs'));
    }

    /**
     * Update menu harian oleh Admin (langsung disetujui).
     */
    public function adminUpdate(Request $request, $id)
    {
        $menu = MenuHarian::findOrFail($id);

        $request->validate([
            'sppg_id'      => 'required|exists:sppg,id',
            'siklus_ke'    => 'required|integer|min:1|max:4',
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'menu_makanan' => 'required|string|max:255',
            'standar_gizi' => 'nullable|string|max:1000',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $fotoPath = $menu->foto;
        if ($request->hasFile('foto')) {
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }
            $fotoPath = $request->file('foto')->store('menu_harian', 'public');
        }

        $menu->update([
            'sppg_id'      => $request->sppg_id,
            'siklus_ke'    => $request->siklus_ke,
            'hari'         => $request->hari,
            'menu_makanan' => $request->menu_makanan,
            'standar_gizi' => $request->standar_gizi,
            'foto'         => $fotoPath,
            'status'       => 'disetujui',
            'alasan_penolakan' => null,
        ]);

        return redirect()
            ->route('admin.menu.index', ['sppg_id' => $request->sppg_id])
            ->with('success', 'Menu harian berhasil diperbarui oleh Administrator!');
    }

    /**
     * Hapus menu harian oleh Admin.
     */
    public function adminDestroy($id)
    {
        $menu = MenuHarian::findOrFail($id);

        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();

        return back()->with('success', 'Data menu harian berhasil dihapus oleh Administrator.');
    }
}
