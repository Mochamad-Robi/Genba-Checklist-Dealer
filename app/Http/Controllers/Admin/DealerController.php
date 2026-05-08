<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function index()
    {
        $dealers = Dealer::latest()->paginate(20);
        return view('admin.dealers.index', compact('dealers'));
    }

    public function create()
    {
        return view('admin.dealers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:dealers,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        Dealer::create($request->all());

        return redirect()->route('admin.dealers.index')
            ->with('success', 'Dealer berhasil ditambahkan!');
    }

    public function edit(Dealer $dealer)
    {
        return view('admin.dealers.edit', compact('dealer'));
    }

    public function update(Request $request, Dealer $dealer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:dealers,code,' . $dealer->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $dealer->update($request->all());

        return redirect()->route('admin.dealers.index')
            ->with('success', 'Dealer berhasil diupdate!');
    }

    public function destroy(Dealer $dealer)
    {
        $dealer->delete();
        return redirect()->route('admin.dealers.index')
            ->with('success', 'Dealer berhasil dihapus!');
    }

    public function show(Dealer $dealer)
    {
        return view('admin.dealers.show', compact('dealer'));
    }
}