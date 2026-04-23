<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScreeningExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct(protected Collection $data) {}

    public function collection(): Collection
    {
        return $this->data->map(fn($s) => [
            $s->user->name         ?? '-',
            $s->user->nip          ?? '-',
            $s->user->unit         ?? '-',
            $s->questionnaire->name ?? '-',
            $s->total_score,
            ucfirst($s->risk_level),
            $s->completed_at?->format('d/m/Y H:i'),
        ]);
    }

    public function headings(): array
    {
        return ['Nama', 'NIP', 'Unit', 'Kuesioner', 'Total Skor', 'Tingkat Risiko', 'Tanggal Skrining'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0F766E']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]],
        ];
    }
}
