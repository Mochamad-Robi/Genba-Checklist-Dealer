<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\Role;
use App\Models\User;
use App\Models\GenbaSession;
use App\Models\GenbaAnswer;
use App\Models\Question;
use Illuminate\Http\Request;

class GenbaController extends Controller
{
    public function index()
    {
        $sessions = GenbaSession::with(['dealer', 'role', 'user', 'filledBy'])
            ->latest()
            ->paginate(20);
        return view('admin.genba.index', compact('sessions'));
    }

    public function create()
    {
        $dealers = Dealer::where('is_active', true)->get();
        $roles = Role::where('is_active', true)->orderBy('order')->get();
        return view('admin.genba.create', compact('dealers', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dealer_id' => 'required|exists:dealers,id',
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
            'auditee_name' => 'required|string',
            'honda_id' => 'nullable|string',
        ]);

        $session = GenbaSession::create([
            'dealer_id' => $request->dealer_id,
            'role_id' => $request->role_id,
            'user_id' => $request->user_id,
            'filled_by' => auth()->id(),
            'auditee_name' => $request->auditee_name,
            'honda_id' => $request->honda_id,
            'is_behalf' => true,
            'status' => 'draft',
        ]);

        return redirect()->route('admin.genba.fill', $session);
    }

    public function fill(GenbaSession $session)
    {
        $questions = Question::where('role_id', $session->role_id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $answers = $session->answers->keyBy('question_id');

        return view('admin.genba.fill', compact('session', 'questions', 'answers'));
    }

    public function submit(Request $request, GenbaSession $session)
    {
        foreach ($request->answers as $questionId => $data) {
            GenbaAnswer::updateOrCreate(
                ['session_id' => $session->id, 'question_id' => $questionId],
                [
                    'indicator' => $data['indicator'] ?? null,
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        $session->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('admin.genba.show', $session)
            ->with('success', 'Genba berhasil disubmit!');
    }

    public function show(GenbaSession $session)
    {
        $session->load(['answers.question', 'dealer', 'role', 'user', 'filledBy']);
        return view('admin.genba.show', compact('session'));
    }
}