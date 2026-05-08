<?php
namespace App\Http\Controllers\Auditor;
use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\Role;
use App\Models\GenbaSession;
use App\Models\GenbaAnswer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\User;

class GenbaController extends Controller
{
 public function index()
{
    $user = auth()->user();
    
    $sessions = GenbaSession::with(['dealer', 'role'])
        ->where('user_id', $user->id)
        ->latest()
        ->paginate(10);

    $totalSesi = GenbaSession::where('user_id', $user->id)->count();
    $totalSubmitted = GenbaSession::where('user_id', $user->id)->where('status', 'submitted')->count();
    $totalDraft = GenbaSession::where('user_id', $user->id)->where('status', 'draft')->count();

    return view('auditor.genba.index', compact(
        'sessions', 'totalSesi', 'totalSubmitted', 'totalDraft'
    ));
}

public function create()
{
    $user = auth()->user();
    
    // Cek jadwal hari ini
    $todaySchedules = \App\Models\GenbaSchedule::with('dealer')
        ->where('user_id', $user->id)
        ->where('tanggal', today())
        ->where('is_done', false)
        ->get();

    $roles = $user->roles()->where('is_active', true)->orderBy('order')->get();
    $auditors = User::where('user_type', 'auditor')
        ->where('id', '!=', $user->id)
        ->where('is_active', true)
        ->get();

    // Kalau tidak ada jadwal, load semua dealer
    $dealers = $todaySchedules->isEmpty()
        ? \App\Models\Dealer::where('is_active', true)->get()
        : collect();

    return view('auditor.genba.create', compact(
        'dealers', 'roles', 'auditors', 'todaySchedules'
    ));
}

   public function store(Request $request)
{
    $request->validate([
        'dealer_id' => 'required|exists:dealers,id',
        'role_id' => 'required|exists:roles,id',
        'auditee_name' => 'required|string',
        'honda_id' => 'nullable|string',
        'behalf_user_id' => 'nullable|exists:users,id',
    ]);

    $isBehalf = $request->boolean('is_behalf');
    $behalfUserId = $isBehalf ? $request->behalf_user_id : null;

    $session = GenbaSession::create([
        'dealer_id' => $request->dealer_id,
        'role_id' => $request->role_id,
        'user_id' => auth()->id(), // yang ngisi
        'auditee_name' => $request->auditee_name,
        'honda_id' => $request->honda_id,
        'is_behalf' => $isBehalf,
        'behalf_user_id' => $behalfUserId,
        'status' => 'draft',
    ]);

    return redirect()->route('auditor.genba.fill', $session);
}

   public function fill(GenbaSession $session)
{
    if ($session->user_id !== auth()->id()) abort(403);
    if ($session->status === 'submitted') {
        return redirect()->route('auditor.genba.result', $session);
    }

    $questions = Question::where('role_id', $session->role_id)
        ->where('is_active', true)
        ->orderBy('order')->get();

    // Pastikan answers di-load fresh dari database
    $answers = GenbaAnswer::where('session_id', $session->id)
        ->get()->keyBy('question_id');

    $role = $session->role;

    return view('auditor.genba.fill', compact('session', 'questions', 'answers', 'role'));
}

  public function submit(Request $request, GenbaSession $session)
{
    if ($session->user_id !== auth()->id()) abort(403);

    // Simpan semua jawaban
    if ($request->answers) {
        foreach ($request->answers as $questionId => $data) {
            GenbaAnswer::updateOrCreate(
                [
                    'session_id' => $session->id,
                    'question_id' => $questionId
                ],
                [
                    'indicator' => $data['indicator'] ?? null,
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }
    }

    // Kalau draft, stop di sini
    if ($request->action === 'draft') {
        return redirect()->route('auditor.genba.fill', $session)
            ->with('success', '💾 Draft tersimpan! Kamu bisa lanjutkan kapan saja.');
    }

    // Validasi semua pertanyaan sudah dijawab
    $totalQuestions = Question::where('role_id', $session->role_id)
        ->where('is_active', true)->count();

    $totalAnswered = GenbaAnswer::where('session_id', $session->id)
        ->whereNotNull('indicator')->count();

    if ($totalAnswered < $totalQuestions) {
        $belum = $totalQuestions - $totalAnswered;
        return redirect()->back()
            ->with('error', "❌ Masih ada {$belum} pertanyaan yang belum dijawab!");
    }

    // Submit session
    $session->update([
        'status' => 'submitted',
        'submitted_at' => now(),
    ]);

    // Otomatis buat PICA dari jawaban Tidak Paham & Tidak Dipakai
    $answers = GenbaAnswer::with('question')
        ->where('session_id', $session->id)
        ->whereIn('indicator', ['2', '3'])
        ->get();

    foreach ($answers as $answer) {
        $question = $answer->question;

        // Tentukan masalah dari tipe role
        if ($session->role->type === 'program') {
            $masalah = $question->menu_program;
            if ($question->proses) {
                $masalah .= ' — ' . $question->proses;
            }
        } else {
            $masalah = $question->question;
        }

        // Cek apakah PICA untuk pertanyaan ini sudah ada (hindari duplikat)
        $existing = \App\Models\Pica::where('session_id', $session->id)
            ->where('question_id', $answer->question_id)
            ->first();

        if (!$existing) {
            \App\Models\Pica::create([
                'user_id' => auth()->id(),
                'dealer_id' => $session->dealer_id,
                'session_id' => $session->id,
                'question_id' => $answer->question_id,
                'masalah' => $masalah,
                'keterangan' => $answer->keterangan,
                'indikator' => $answer->indicator === '2' ? 'Tidak Paham' : 'Tidak Dipakai',
                'status' => 'open',
            ]);
        }
    }

    $totalPica = $answers->count();

    return redirect()->route('auditor.genba.result', $session)
        ->with('success', "Checklist berhasil disubmit! {$totalPica} temuan otomatis masuk ke PICA.");
}

    public function result(GenbaSession $session)
    {
        if ($session->user_id !== auth()->id()) abort(403);
        $session->load(['answers.question', 'role', 'dealer']);

        $paham = $session->answers->where('indicator', '1')->count();
        $tidakPaham = $session->answers->where('indicator', '2')->count();
        $tidakDipakai = $session->answers->where('indicator', '3')->count();
        $total = $session->answers->count();
        $score = $total > 0 ? round(($paham / $total) * 100) : 0;

        return view('auditor.genba.result', compact(
            'session', 'paham', 'tidakPaham', 'tidakDipakai', 'total', 'score'
        ));
    }
}