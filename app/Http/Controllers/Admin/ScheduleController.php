<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\GenbaSchedule;
use App\Models\Dealer;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = GenbaSchedule::with(['dealer', 'user'])
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        $dealers = Dealer::where('is_active', true)->get();
        $users = User::where('user_type', 'auditor')->where('is_active', true)->get();

        return view('admin.schedules.index', compact('schedules', 'dealers', 'users'));
    }

    public function store(Request $request)
{
    $request->validate([
        'dealer_id' => 'required|exists:dealers,id',
        'user_id' => 'required|array',
        'user_id.*' => 'exists:users,id',
        'tanggal' => 'required|date',
        'catatan' => 'nullable|string',
    ]);

    foreach ($request->user_id as $uid) {

        GenbaSchedule::create([
            'dealer_id' => $request->dealer_id,
            'user_id'   => $uid,
            'tanggal'   => $request->tanggal,
            'catatan'   => $request->catatan,
        ]);

    }

    return redirect()->route('admin.schedules.index')
        ->with('success', 'Jadwal berhasil ditambahkan!');
}

    public function destroy(GenbaSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}