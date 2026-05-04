<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('permissions')->orderBy('id')->paginate(10);
        foreach ($users as $u) { $u->perm_slugs = $u->getPermissionSlugs(); }
        $availablePages = $this->getAvailablePages();
        return view('admin.users.index', compact('users', 'availablePages'))->with('activeMenu', 'master-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:3',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
            'role' => $request->role,
            'bagian' => $request->bagian,
            'is_active' => true,
        ]);

        if ($request->role === 'user' && $request->filled('permissions')) {
            $this->syncPermissions($user->id, $request);
        }

        return redirect()->route('admin.users.index')->with('success', "User '{$request->username}' berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'role' => 'required|in:admin,user',
        ]);

        $user = User::findOrFail($id);
        $data = ['username' => $request->username, 'role' => $request->role, 'bagian' => $request->bagian];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $data['plain_password'] = $request->password;
        }

        $user->update($data);

        // Sync permissions
        UserPermission::where('user_id', $id)->delete();
        if ($request->role === 'user' && $request->filled('permissions')) {
            foreach ($request->permissions as $slug) {
                UserPermission::create(['user_id' => $id, 'page_slug' => $slug]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        return back()->with('success', 'Status user berhasil diubah.');
    }

    public function syncPermissions($id, Request $request)
    {
        UserPermission::where('user_id', $id)->delete();
        if ($request->filled('permissions')) {
            foreach ($request->permissions as $slug) {
                UserPermission::create(['user_id' => $id, 'page_slug' => $slug]);
            }
        }
        return back()->with('success', 'Permission berhasil diupdate.');
    }

    private function getAvailablePages(): array
    {
        return [
            'data-jadwal' => ['name' => 'Data Jadwal', 'icon' => '📅'],
            'booking-zoom' => ['name' => 'Booking Zoom', 'icon' => '🎥'],
            'data-server' => ['name' => 'Data Server', 'icon' => '🖥️'],
            'it-support-jateng' => ['name' => 'IT Support Jateng', 'icon' => '👨‍💻'],
            'stock-perangkat' => ['name' => 'Stock Perangkat IT', 'icon' => '📦'],
            'perangkat-aplikasi' => ['name' => 'Perangkat Aplikasi', 'icon' => '🗂️'],
        ];
    }
}
