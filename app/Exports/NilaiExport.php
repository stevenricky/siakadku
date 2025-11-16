<?php

namespace App\Exports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Nilai::query()
            ->select([
                'nilais.*',
                'siswas.nis',
                'siswas.nama_lengkap as nama_siswa', 
                'kelas.nama_kelas',
                'mapels.nama_mapel',
                'mapels.kkm',
                'gurus.nama_lengkap as nama_guru',
                'tahun_ajarans.tahun_awal',
                'tahun_ajarans.tahun_akhir',
                'tahun_ajarans.semester as tahun_semester'
            ])
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->join('gurus', 'nilais.guru_id', '=', 'gurus.id')
            ->join('tahun_ajarans', 'nilais.tahun_ajaran_id', '=', 'tahun_ajarans.id');

        return $this->applyFilters($query);
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Siswa',
            'Kelas', 
            'Mata Pelajaran',
            'Guru Pengajar',
            'Tahun Ajaran',
            'Semester',
            'Nilai UH 1',
            'Nilai UH 2',
            'Nilai UTS',
            'Nilai UAS',
            'Nilai Akhir',
            'Predikat',
            'Status',
            'KKM',
            'Deskripsi',
            'Tanggal Input'
        ];
    }

    public function map($nilai): array
    {
        $status = $nilai->nilai_akhir >= $nilai->kkm ? 'Lulus' : 'Tidak Lulus';
        
        return [
            $nilai->nis,
            $nilai->nama_siswa,
            $nilai->nama_kelas,
            $nilai->nama_mapel,
            $nilai->nama_guru,
            $nilai->tahun_awal . '/' . $nilai->tahun_akhir,
            $nilai->semester,
            number_format($nilai->nilai_uh1, 2),
            number_format($nilai->nilai_uh2, 2),
            number_format($nilai->nilai_uts, 2),
            number_format($nilai->nilai_uas, 2),
            number_format($nilai->nilai_akhir, 2),
            $nilai->predikat,
            $status,
            $nilai->kkm,
            $nilai->deskripsi ?? '-',
            $nilai->created_at->format('d/m/Y H:i')
        ];
    }

    public function title(): string
    {
        return 'Data Nilai';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Q1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);

        $sheet->setAutoFilter('A1:Q1');

        return [];
    }

    protected function applyFilters($query)
    {
        extract($this->filters);

        return $query
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('siswas.nama_lengkap', 'like', '%' . $search . '%')
                      ->orWhere('siswas.nis', 'like', '%' . $search . '%')
                      ->orWhere('mapels.nama_mapel', 'like', '%' . $search . '%');
                });
            })
            ->when($tahunAjaranFilter, function ($query) use ($tahunAjaranFilter) {
                $query->where('nilais.tahun_ajaran_id', $tahunAjaranFilter);
            })
            ->when($semesterFilter, function ($query) use ($semesterFilter) {
                $query->where('nilais.semester', $semesterFilter);
            })
            ->when($mapelFilter, function ($query) use ($mapelFilter) {
                $query->where('nilais.mapel_id', $mapelFilter);
            })
            ->when($kelasFilter, function ($query) use ($kelasFilter) {
                $query->where('siswas.kelas_id', $kelasFilter);
            })
            ->when($predikatFilter, function ($query) use ($predikatFilter) {
                $query->where('nilais.predikat', $predikatFilter);
            })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                if ($statusFilter === 'Lulus') {
                    $query->whereRaw('nilais.nilai_akhir >= mapels.kkm');
                } elseif ($statusFilter === 'Tidak Lulus') {
                    $query->whereRaw('nilais.nilai_akhir < mapels.kkm');
                }
            })
            ->when($rentangNilaiFilter, function ($query) use ($rentangNilaiFilter) {
                $ranges = [
                    '90-100' => [90, 100],
                    '80-89' => [80, 89.99],
                    '70-79' => [70, 79.99],
                    '0-69' => [0, 69.99],
                ];
                
                if (isset($ranges[$rentangNilaiFilter])) {
                    $query->whereBetween('nilais.nilai_akhir', $ranges[$rentangNilaiFilter]);
                }
            })
            ->orderBy('nilais.nilai_akhir', 'desc');
    }
}