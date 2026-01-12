<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $pageTitle = 'Manajemen User';
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Manajemen User']
        ];

        return view('users.index', compact('pageTitle', 'breadcrumbs'));
    }

    /**
     * Get users data for DataTables
     */
    public function getData()
    {
        $users = User::select(['id', 'name', 'email', 'role', 'avatar', 'is_active', 'created_at']);

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('status', function ($user) {
                if ($user->is_active) {
                    return '<span class="badge badge-light-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-light-danger">Tidak Aktif</span>';
                }
            })
            ->addColumn('role_badge', function ($user) {
                $badges = [
                    'admin' => 'badge-light-primary',
                    'staff' => 'badge-light-info',
                    'user' => 'badge-light-secondary',
                ];
                $class = $badges[$user->role] ?? 'badge-light-secondary';
                return '<span class="badge ' . $class . '">' . ucfirst($user->role) . '</span>';
            })
            ->addColumn('avatar_display', function ($user) {
                if ($user->avatar) {
                    return '<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <div class="symbol-label">
                                    <img src="' . asset('storage/' . $user->avatar) . '" alt="' . $user->name . '" class="w-100">
                                </div>
                            </div>';
                } else {
                    return '<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <div class="symbol-label fs-3 bg-light-primary text-primary">
                                    ' . strtoupper(substr($user->name, 0, 1)) . '
                                </div>
                            </div>';
                }
            })
            ->addColumn('action', function ($user) {
                return '
                    <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="editUser(' . $user->id . ')" title="Edit">
                        <i class="ki-duotone ki-pencil fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                    <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" onclick="deleteUser(' . $user->id . ')" title="Hapus">
                        <i class="ki-duotone ki-trash fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </button>
                ';
            })
            ->rawColumns(['status', 'role_badge', 'avatar_display', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,staff,user',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan'
        ]);
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:admin,staff,user',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate'
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}
