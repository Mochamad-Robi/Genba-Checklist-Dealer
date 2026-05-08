<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pica;
use App\Models\Dealer;
use App\Models\User;
use App\Models\GenbaSession;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PicaController extends Controller
{
    public function index()
    {
        $dealers = Dealer::where('is_active', true)->get();
        $users = User::where('user_type', 'auditor')->get();
        $dealerId = request('dealer_id');
        $userId = request('user_id');
        $status = request('status');

        $sessions = GenbaSession::with(['dealer', 'role', 'user'])
            ->whereHas('picas')
            ->when($dealerId, fn($q) => $q->where('dealer_id', $dealerId))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->latest()
            ->paginate(20);

        $totalOpen = Pica::where('status', 'open')->count();
        $totalOnProgress = Pica::where('status', 'on_progress')->count();
        $totalClosed = Pica::where('status', 'closed')->count();

        return view('admin.pica.index', compact(
            'sessions', 'dealers', 'users',
            'dealerId', 'userId', 'status',
            'totalOpen', 'totalOnProgress', 'totalClosed'
        ));
    }

    public function show(GenbaSession $session)
    {
        $session->load(['dealer', 'role', 'user']);
        $picas = Pica::with(['question'])
            ->where('session_id', $session->id)
            ->get();

        return view('admin.pica.show', compact('session', 'picas'));
    }

    public function export(Request $request)
    {
        $sessions = GenbaSession::with(['dealer', 'role', 'user', 'picas'])
            ->whereHas('picas')
            ->when($request->dealer_id, fn($q) => $q->where('dealer_id', $request->dealer_id))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('PICA Report');

        // Header styling
        $headers = [
            'A1' => 'No', 'B1' => 'Dealer', 'C1' => 'Role',
            'D1' => 'Auditor MD', 'E1' => 'Tanggal Genba',
            'F1' => 'Masalah/Temuan', 'G1' => 'Indikator',
            'H1' => 'Keterangan', 'I1' => 'PIC', 'J1' => 'Analisa',
            'K1' => 'Tindakan', 'L1' => 'Target Date', 'M1' => 'Status'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style header
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2D6A9F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Data
        $no = 1;
        $row = 2;
        foreach ($sessions as $session) {
            foreach ($session->picas as $pica) {
                if ($request->status && $pica->status !== $request->status) continue;

                $sheet->fromArray([
                    $no++,
                    $session->dealer->name ?? '-',
                    $session->role->name ?? '-',
                    $session->user->name ?? '-',
                    $session->submitted_at?->format('d/m/Y H:i') ?? '-',
                    $pica->masalah ?? '-',
                    $pica->indikator ?? '-',
                    $pica->keterangan ?? '-',
                    $pica->pic ?? '-',
                    $pica->analisa ?? '-',
                    $pica->tindakan ?? '-',
                    $pica->target_date?->format('d/m/Y') ?? '-',
                    strtoupper($pica->status),
                ], null, 'A' . $row);

                $row++;
            }
        }

        // Auto size semua kolom
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'PICA_Report_' . now()->format('d-m-Y_H-i') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}