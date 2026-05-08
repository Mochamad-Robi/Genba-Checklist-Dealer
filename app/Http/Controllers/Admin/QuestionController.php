<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Role;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $roles = Role::with(['questions' => function($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get(); 

        return view('admin.questions.index', compact('roles'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->orderBy('order')->get();
        return view('admin.questions.create', compact('roles'));
    }

    public function store(Request $request)
{
    $role = Role::find($request->role_id);

    if ($role->type === 'program') {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'menu_program' => 'required|string',
            'proses' => 'nullable|string',
            'prog_id' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);
    } else {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'question' => 'required|string',
            'order' => 'nullable|integer',
        ]);
    }

    Question::create([
        'role_id' => $request->role_id,
        'question' => $request->question,
        'menu_program' => $request->menu_program,
        'proses' => $request->proses,
        'prog_id' => $request->prog_id,
        'order' => $request->order ?? 0,
        'is_active' => true,
    ]);

    return redirect()->route('admin.questions.index')
        ->with('success', 'Pertanyaan berhasil ditambahkan!');
}

    public function edit(Question $question)
    {
        $roles = Role::where('is_active', true)->orderBy('order')->get();
        return view('admin.questions.edit', compact('question', 'roles'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'question' => 'required|string',
            'menu_program' => 'nullable|string',
            'proses' => 'nullable|string',
            'prog_id' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $question->update($request->all());

        return redirect()->route('admin.questions.index')
            ->with('success', 'Pertanyaan berhasil diupdate!');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }

    public function show(Question $question)
    {
        return view('admin.questions.show', compact('question'));
    }

    public function reorder(Request $request)
    {
        foreach ($request->orders as $item) {
            Question::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        return response()->json(['success' => true]);
    }
}