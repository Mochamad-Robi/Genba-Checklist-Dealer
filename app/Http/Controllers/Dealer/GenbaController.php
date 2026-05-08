<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\GenbaSession;
use App\Models\GenbaAnswer;
use App\Models\Question;
use Illuminate\Http\Request;

class GenbaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sessions = GenbaSession::with(['role', 'answers'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dealer.genba.index', compact('sessions'));
    }

    public function start()
    {
        $user = auth()->user();
        return view('dealer.genba.start', compact('user'));
    }

    public function createSession(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'auditee_name' => 'required|string',
            'honda_id' => 'nullable|string',
        ]);

        // Cek apakah sudah ada session draft
        $existing = GenbaSession::where('user_id', $user->id)
            ->where('status', 'draft')
            ->first();

        if ($existing) {
            return redirect()->route('dealer.genba.fill', $existing);
        }

        $session = GenbaSession::create([
            'dealer_id' => $user->dealer_id,
            'role_id' => $user->role_id,
            'user_id' => $user->id,
            'filled_by' => $user->id,
            'auditee_name' => $request->auditee_name,
            'honda_id' => $request->honda_id,
            'is_behalf' => false,
            'status' => 'draft',
        ]);

        return redirect()->route('dealer.genba.fill', $session);
    }

    public function fill(GenbaSession $session)
    {
        // Pastikan session milik user ini
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        $questions = Question::where('role_id', $session->role_id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $answers = $session->answers->keyBy('question_id');
        $role = $session->role;

        return view('dealer.genba.fill', compact('session', 'questions', 'answers', 'role'));
    }

    public function save(Request $request, GenbaSession $session)
    {
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($request->answers as $questionId => $data) {
            GenbaAnswer::updateOrCreate(
                ['session_id' => $session->id, 'question_id' => $questionId],
                [
                    'indicator' => $data['indicator'] ?? null,
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Jawaban tersimpan!');
    }

    public function submit(Request $request, GenbaSession $session)
    {
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

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

        return redirect()->route('dealer.genba.result', $session)
            ->with('success', 'Checklist berhasil disubmit!');
    }

    public function result(GenbaSession $session)
    {
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        $session->load(['answers.question', 'role', 'dealer']);

        $paham = $session->answers->where('indicator', '1')->count();
        $tidakPaham = $session->answers->where('indicator', '2')->count();
        $tidakDipakai = $session->answers->where('indicator', '3')->count();
        $total = $session->answers->count();
        $score = $total > 0 ? round(($paham / $total) * 100) : 0;

        return view('dealer.genba.result', compact(
            'session', 'paham', 'tidakPaham',
            'tidakDipakai', 'total', 'score'
        ));
    }
}