<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanAkademikExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $statistics = $this->data['statistics'];
        $filters = $this->data['filters'];

        return [
            ['LAPORAN AKADEMIK'],
            [''],
            ['Periode', $filters['tahun_ajaran'] . ' - Semester ' . $filters['semester']],
            ['Kelas', $filters['kelas']],
            ['Tanggal Export', now()->format('d F Y H:i:s')],
            [''],
            ['STATISTIK UTAMA'],
            ['Total Siswa', $statistics['totalSiswa']],
            ['Total Kelas', $statistics['totalKelas']],
            ['Rata-rata Nilai', number_format($statistics['averageNilai'], 2)],
            [''],
            ['DISTRIBUSI PREDIKAT'],
            ...$statistics['predikatStats']->map(function ($stat) {
                return [$stat['predikat'], $stat['count']];
            })->toArray(),
            [''],
            ['SISWA TERBAIK'],
            ...$statistics['topPerformers']->map(function ($student) {
                return [$student['nama'], $student['kelas'], $student['average']];
            })->toArray(),
            [''],
            ['MATA PELAJARAN TERBAIK'],
            ...$statistics['bestSubjects']->map(function ($subject) {
                return [$subject['mapel'], $subject['average']];
            })->toArray(),
        ];
    }

    public function headings(): array
    {
        return [
            ['LAPORAN AKADEMIK'],
            [''],
            ['Keterangan', 'Nilai'],
        ];
    }

    public function title(): string
    {
        return 'Laporan Akademik';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            7 => ['font' => ['bold' => true]],
            12 => ['font' => ['bold' => true]],
            18 => ['font' => ['bold' => true]],
            23 => ['font' => ['bold' => true]],
        ];
    }
}