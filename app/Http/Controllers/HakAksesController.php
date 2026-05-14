<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HakAksesController extends Controller
{
    /**
     * Daftar semua menu yang bisa diatur aksesnya.
     * Format: 'menu_key' => ['label' => '...', 'group' => '...']
     */
    public static function menuList(): array
    {
        return [
            'barang'              => ['label' => 'Nama Barang',      'group' => 'Data Master'],
            'jenis-barang'        => ['label' => 'Jenis Barang',     'group' => 'Data Master'],
            'supplier'            => ['label' => 'Supplier',         'group' => 'Data Master'],
            'customer'            => ['label' => 'Customer',         'group' => 'Data Master'],
            'barang-masuk'        => ['label' => 'Barang Masuk',     'group' => 'Transaksi'],
            'barang-keluar'       => ['label' => 'Barang Keluar',    'group' => 'Transaksi'],
            'laporan-stok'        => ['label' => 'Laporan Stok',     'group' => 'Laporan'],
            'laporan-barang-masuk'  => ['label' => 'Laporan Barang Masuk',  'group' => 'Laporan'],
            'laporan-barang-keluar' => ['label' => 'Laporan Barang Keluar', 'group' => 'Laporan'],
            'data-pengguna'       => ['label' => 'Data Pengguna',    'group' => 'Manajemen User'],
            'hak-akses'           => ['label' => 'Hak Akses / Role', 'group' => 'Manajemen User'],
            'aktivitas-user'      => ['label' => 'Aktivitas User',   'group' => 'Manajemen User'],
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('hak-akses.index');
    }

    public function getDataRole()
    {
        $roles = Role::all();
        return response()->json([
            'success' => true,
            'data'    => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hak-akses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role'      => 'required',
            'deskripsi' => 'required'
        ], [
            'role.required'      => 'Form Role Wajib Di Isi !',
            'deskripsi.required' => 'Form Deskripsi Wajib Di Isi !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::create([
            'role'      => $request->role,
            'deskripsi' => $request->deskripsi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Tersimpan',
            'data'    => $role
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Edit Data Role',
            'data'    => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        // Proteksi: role pertama (superadmin) tidak bisa diubah
        if ($role->id === 1) {
            return response()->json(['message' => 'Role ini tidak dapat diubah.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'role'      => 'required',
            'deskripsi' => 'required'
        ], [
            'role.required'      => 'Form Role Wajib Di Isi !',
            'deskripsi.required' => 'Form Deskripsi Wajib Di Isi !'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role->update([
            'role'      => $request->role,
            'deskripsi' => $request->deskripsi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Terupdate',
            'data'    => $role
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Proteksi: role pertama (superadmin) tidak bisa dihapus
        if ((int)$id === 1) {
            return response()->json(['message' => 'Role ini tidak dapat dihapus.'], 403);
        }

        Role::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }

    // =============================================
    // PERMISSION MANAGEMENT
    // =============================================

    /**
     * Tampilkan halaman atur akses (checklist menu) untuk role tertentu
     */
    public function permissions($id)
    {
        // Proteksi: role pertama (superadmin) tidak bisa diatur permission-nya
        if ((int)$id === 1) {
            return redirect()->route('hak-akses.index')
                ->with('error', 'Hak akses role ini tidak dapat diubah.');
        }

        $role = Role::with('permissions')->findOrFail($id);
        $menuList = self::menuList();

        // Buat map: menu_key => ['can_view' => bool, 'can_add' => bool]
        $currentPermissions = [];
        foreach ($role->permissions as $perm) {
            $currentPermissions[$perm->menu_key] = [
                'can_view' => $perm->can_view,
                'can_add'  => $perm->can_add,
            ];
        }

        return view('hak-akses.permissions', compact('role', 'menuList', 'currentPermissions'));
    }

    /**
     * Simpan/update permission untuk role tertentu
     */
    public function savePermissions(Request $request, $id)
    {
        // Proteksi: role pertama (superadmin) tidak bisa diubah permission-nya
        if ((int)$id === 1) {
            return redirect()->route('hak-akses.index')
                ->with('error', 'Hak akses role ini tidak dapat diubah.');
        }

        $role = Role::findOrFail($id);
        $menuList = self::menuList();

        foreach (array_keys($menuList) as $menuKey) {
            $canView = $request->has("permissions.{$menuKey}.can_view");
            $canAdd  = $request->has("permissions.{$menuKey}.can_add");

            // Jika can_add dicentang, otomatis can_view juga true
            if ($canAdd) {
                $canView = true;
            }

            RolePermission::updateOrCreate(
                ['role_id' => $role->id, 'menu_key' => $menuKey],
                ['can_view' => $canView, 'can_add' => $canAdd]
            );
        }

        return redirect()->route('hak-akses.index')
            ->with('success', "Hak akses untuk role '{$role->role}' berhasil disimpan.");
    }

    /**
     * API: ambil data permission untuk role (dipakai AJAX)
     */
    public function getPermissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = $role->permissions->keyBy('menu_key');

        return response()->json([
            'success' => true,
            'data'    => $permissions
        ]);
    }
}
