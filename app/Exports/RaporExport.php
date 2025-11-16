<?php

namespace App\Exports;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RaporExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $nilai;
    protected $siswa;
    protected $tahunAjaran;
    protected $semester;

    public function __construct($nilai, Siswa $siswa, ?TahunAjaran $tahunAjaran, $semester)
    {
        $this->nilai = $nilai;
        $this->siswa = $siswa;
        $this->tahunAjaran = $tahunAjaran;
        $this->semester = $semester;
    }

    public function collection()
    {
        return $this->nilai;
    }

    public function headings(): array
    {
        return [
            'Nama Siswa: ' . $this->siswa->nama_lengkap,
            ['NIS: ' . $this->siswa->nis],
            ['Tahun Ajaran: ' . ($this->tahunAjaran->tahun_ajaran ?? '-')],
            ['Semester: ' . ucfirst($this->semester)],
            [],
            [
                'No',
                'Mata Pelajaran',
                'Guru Pengampu',
                'UH 1',
                'UH 2',
                'UTS',
                'UAS',
                'Nilai Akhir',
                'Predikat',
                'Status',
                'KKM'
            ]
        ];
    }

    public function map($nilai): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $nilai->mapel->nama_mapel ?? 'N/A',
            $nilai->guru->nama_lengkap ?? 'N/A',
            $nilai->nilai_uh1 ? number_format($nilai->nilai_uh1, 2) : '-',
            $nilai->nilai_uh2 ? number_format($nilai->nilai_uh2, 2) : '-',
            $nilai->nilai_uts ? number_format($nilai->nilai_uts, 2) : '-',
            $nilai->nilai_uas ? number_format($nilai->nilai_uas, 2) : '-',
            $nilai->nilai_akhir ? number_format($nilai->nilai_akhir, 2) : '-',
            $nilai->predikat ?? '-',
            $nilai->nilai_akhir >= ($nilai->mapel->kkm ?? 75) ? 'Lulus' : 'Tidak Lulus',
            $nilai->mapel->kkm ?? 75
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->mergeCells('A3:K3');
        $sheet->mergeCells('A4:K4');
        
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A6:K6')->getFont()->setBold(true);
        $sheet->getStyle('A6:K6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
        
        // Auto size columns
        foreach(range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Border untuk data
        $sheet->getStyle('A6:K' . (6 + $this->nilai->count()))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            6 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Rapor ' . ucfirst($this->semester);
    }
}