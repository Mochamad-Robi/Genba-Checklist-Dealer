<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GenbaEvidence;
use App\Models\GenbaSession;
use App\Models\Dealer;
use Illuminate\Http\Request;

class EvidenceController extends Controller
{
    public function index(Request $request)
    {
        $dealers = Dealer::where('is_active', true)->get();
        $dealerId = $request->dealer_id;
        $tanggal = $request->tanggal;

        $kunjungan = GenbaSession::with(['dealer'])
            ->where('status', 'submitted')
            ->when($dealerId, fn($q) => $q->where('dealer_id', $dealerId))
            ->when($tanggal, fn($q) => $q->whereDate('submitted_at', $tanggal))
            ->selectRaw('dealer_id, DATE(submitted_at) as tanggal, COUNT(*) as total_sesi')
            ->groupBy('dealer_id', 'tanggal')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('admin.evidence.index', compact('kunjungan', 'dealers', 'dealerId', 'tanggal'));
    }

    public function show(Request $request, $dealerId, $tanggal)
    {
        $dealer = Dealer::findOrFail($dealerId);

        // Ambil semua session per role untuk dealer + tanggal ini
        $sessions = GenbaSession::with(['role', 'user', 'evidences.uploader'])
            ->where('dealer_id', $dealerId)
            ->where('status', 'submitted')
            ->whereDate('submitted_at', $tanggal)
            ->get();

        return view('admin.evidence.show', compact('dealer', 'tanggal', 'sessions'));
    }

    public function upload(Request $request, $dealerId, $tanggal)
    {
        $request->validate([
            'session_id' => 'required|exists:genba_sessions,id',
            'foto.*'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $session = GenbaSession::findOrFail($request->session_id);

        // Cek kuota 2 foto per session
        $existing = GenbaEvidence::where('session_id', $session->id)->count();
        $sisa = 2 - $existing;

        if ($sisa <= 0) {
            return back()->with('error', 'Maksimal 2 foto per role!');
        }

        $files = array_slice($request->file('foto') ?? [], 0, $sisa);

        foreach ($files as $file) {
            $path = $file->store('evidence', 'public');
            GenbaEvidence::create([
                'dealer_id'         => $dealerId,
                'session_id'        => $session->id,
                'tanggal_kunjungan' => $tanggal,
                'foto'              => $path,
                'keterangan'        => $request->keterangan,
                'uploaded_by'       => auth()->id(),
            ]);
        }

        return back()->with('success', count($files) . ' foto berhasil diupload!');
    }

    public function destroy(GenbaEvidence $evidence)
    {
        if (\Storage::disk('public')->exists($evidence->foto)) {
            \Storage::disk('public')->delete($evidence->foto);
        }

        $evidence->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }
    
    public function uploadFree(Request $request, $dealerId, $tanggal)
{
    $request->validate([
        'foto.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Cek kuota 4 foto bebas (session_id null)
    $existing = GenbaEvidence::where('dealer_id', $dealerId)
        ->where('tanggal_kunjungan', $tanggal)
        ->whereNull('session_id')
        ->count();

    $sisa = 4 - $existing;

    if ($sisa <= 0) {
        return back()->with('error', 'Maksimal 4 foto bebas per kunjungan!');
    }

    $files = array_slice($request->file('foto') ?? [], 0, $sisa);

    foreach ($files as $file) {
        $path = $file->store('evidence', 'public');
        GenbaEvidence::create([
            'dealer_id'         => $dealerId,
            'session_id'        => null,
            'tanggal_kunjungan' => $tanggal,
            'foto'              => $path,
            'keterangan'        => $request->keterangan,
            'uploaded_by'       => auth()->id(),
        ]);
    }

    return back()->with('success', count($files) . ' foto bebas berhasil diupload!');
}
}