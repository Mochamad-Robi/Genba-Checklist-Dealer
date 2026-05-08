<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('order')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:question,program',
            'order' => 'nullable|integer',
        ]);

        Role::create($request->all());

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan!');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:question,program',
            'order' => 'nullable|integer',
        ]);

        $role->update($request->all());

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diupdate!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus!');
    }

    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }
}