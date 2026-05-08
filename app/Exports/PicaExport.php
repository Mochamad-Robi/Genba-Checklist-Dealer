<?php
namespace App\Exports;

use App\Models\GenbaSession;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PicaExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $dealerId;
    protected $userId;
    protected $status;

    public function __construct($dealerId = null, $userId = null, $status = null)
    {
        $this->dealerId = $dealerId;
        $this->userId = $userId;
        $this->status = $status;
    }

    public function collection()
    {
        $sessions = GenbaSession::with(['dealer', 'role', 'user', 'picas'])
            ->whereHas('picas')
            ->when($this->dealerId, fn($q) => $q->where('dealer_id', $this->dealerId))
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId))
            ->get();

        $rows = collect();
        $no = 1;

        foreach ($sessions as $session) {
            foreach ($session->picas as $pica) {
                if ($this->status && $pica->status !== $this->status) continue;

                $rows->push([
                    'No' => $no++,
                    'Dealer' => $session->dealer->name,
                    'Role' => $session->role->name,
                    'Auditor MD' => $session->user->name ?? '-',
                    'Tanggal Genba' => $session->submitted_at?->format('d/m/Y H:i'),
                    'Masalah/Temuan' => $pica->masalah,
                    'Indikator' => $pica->indikator ?? '-',
                    'Keterangan' => $pica->keterangan ?? '-',
                    'PIC' => $pica->pic ?? '-',
                    'Analisa' => $pica->analisa ?? '-',
                    'Tindakan' => $pica->tindakan ?? '-',
                    'Target Date' => $pica->target_date?->format('d/m/Y') ?? '-',
                    'Status' => strtoupper($pica->status),
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Dealer',
            'Role',
            'Auditor MD',
            'Tanggal Genba',
            'Masalah / Temuan',
            'Indikator',
            'Keterangan',
            'PIC',
            'Analisa',
            'Tindakan',
            'Target Date',
            'Status',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 18,
            'F' => 40,
            'G' => 15,
            'H' => 30,
            'I' => 20,
            'J' => 30,
            'K' => 30,
            'L' => 15,
            'M' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C8102E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(20);

        // Warna status
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $status = $sheet->getCell('M' . $row)->getValue();
            $color = match($status) {
                'OPEN' => 'FEE2E2',
                'ON_PROGRESS' => 'FEF3C7',
                'CLOSED' => 'F0FDF4',
                default => 'FFFFFF',
            };
            $sheet->getStyle('M' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            // Zebra stripe
            if ($row % 2 === 0) {
                $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9FAFB']],
                ]);
            }
        }

        // Border semua cell
        $sheet->getStyle('A1:M' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB'],
                ],
            ],
        ]);

        // Wrap text kolom panjang
        $sheet->getStyle('F2:F' . $highestRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('H2:H' . $highestRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('J2:J' . $highestRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('K2:K' . $highestRow)->getAlignment()->setWrapText(true);

        return [];
    }

    public function title(): string
    {
        return 'PICA Report';
    }
}