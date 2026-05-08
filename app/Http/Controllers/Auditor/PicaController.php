<?php
namespace App\Http\Controllers\Auditor;
use App\Http\Controllers\Controller;
use App\Models\Pica;
use App\Models\Dealer;
use App\Models\GenbaSession;


class PicaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $sessions = GenbaSession::with(['dealer', 'role', 'picas'])
            ->where('user_id', $user->id)
            ->whereHas('picas')
            ->latest()
            ->paginate(15);

        return view('auditor.pica.index', compact('sessions'));
    }

    public function show(GenbaSession $session)
    {
        if ($session->user_id !== auth()->id()) abort(403);
        $picas = Pica::where('session_id', $session->id)->get();
        return view('auditor.pica.show', compact('session', 'picas'));
    }

    public function create()
    {
        $dealers = Dealer::where('is_active', true)->get();
        return view('auditor.pica.create', compact('dealers'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'dealer_id' => 'required|exists:dealers,id',
            'masalah' => 'required|string',
            'analisa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'target_date' => 'nullable|date',
            'status' => 'required|in:open,on_progress,closed',
            'keterangan' => 'nullable|string',
        ]);

        Pica::create([
            ...$request->all(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('auditor.pica.index')
            ->with('success', 'PICA berhasil ditambahkan!');
    }

    public function edit(GenbaSession $session, Pica $pica)
    {
        if ($pica->user_id !== auth()->id()) abort(403);
        $dealers = Dealer::where('is_active', true)->get();
        return view('auditor.pica.edit', compact('pica', 'dealers', 'session'));
    }

    public function update(\Illuminate\Http\Request $request, Pica $pica)
    {
        if ($pica->user_id !== auth()->id()) abort(403);

        $request->validate([
            'pic' => 'nullable|string',
            'analisa' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'target_date' => 'nullable|date',
            'status' => 'required|in:open,on_progress,closed',
            'keterangan' => 'nullable|string',
        ]);

        $pica->update($request->all());

        return redirect()->route('auditor.pica.show', $pica->session_id)
            ->with('success', 'PICA berhasil diupdate!');
    }

    public function destroy(Pica $pica)
    {
        if ($pica->user_id !== auth()->id()) abort(403);
        $pica->delete();
        return redirect()->route('auditor.pica.index')
            ->with('success', 'PICA berhasil dihapus!');
    }
}