<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dealer;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   public function index()
{
    $users = User::with('roles')
        ->latest()
        ->paginate(20);
    return view('admin.users.index', compact('users'));
}

   public function create()
{
    $roles = Role::where('is_active', true)->orderBy('order')->get();
    return view('admin.users.create', compact('roles'));
}

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'roles' => 'required|array',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'user_type' => 'auditor',
    ]);

    $user->roles()->sync($request->roles);

    return redirect()->route('admin.users.index')
        ->with('success', 'User MD berhasil ditambahkan!');
}

   public function edit(User $user)
{
    $roles = Role::where('is_active', true)->orderBy('order')->get();
    return view('admin.users.edit', compact('user', 'roles'));
}

    public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
    ]);

    $data = $request->only('name', 'email', 'is_active');
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }
    $data['is_active'] = $request->boolean('is_active');

    $user->update($data);
    $user->roles()->sync($request->roles ?? []);

    return redirect()->route('admin.users.index')
        ->with('success', 'User MD berhasil diupdate!');
}

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function byDealerRole(Request $request)
{
    $users = User::where('dealer_id', $request->dealer_id)
        ->where('role_id', $request->role_id)
        ->where('user_type', 'dealer')
        ->get(['id', 'name']);

    return response()->json($users);
}
}