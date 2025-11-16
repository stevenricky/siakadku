<?php

namespace App\Exports;

use App\Models\PembayaranSpp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $kelasId;
    protected $status;
    protected $tanggal;

    public function __construct($kelasId = null, $status = null, $tanggal = null)
    {
        $this->kelasId = $kelasId;
        $this->status = $status;
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        $query = PembayaranSpp::with(['siswa.kelas', 'tagihanSpp', 'verifikator'])
            ->when($this->kelasId, function($q) {
                $q->whereHas('siswa', function($q) {
                    $q->where('kelas_id', $this->kelasId);
                });
            })
            ->when($this->status, function($q) {
                $q->where('status_verifikasi', $this->status);
            })
            ->when($this->tanggal, function($q) {
                $q->whereDate('tanggal_bayar', $this->tanggal);
            })
            ->latest();

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'NIS',
            'Kelas',
            'Periode Tagihan',
            'Jumlah Bayar',
            'Tanggal Bayar',
            'Metode Bayar',
            'Status',
            'Verifikator',
            'Tanggal Verifikasi',
            'Catatan'
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->siswa->nama_lengkap ?? 'N/A',
            $pembayaran->siswa->nis ?? 'N/A',
            $pembayaran->siswa->kelas->nama_kelas ?? 'N/A',
            ($pembayaran->tagihanSpp->bulan ?? '') . ' ' . ($pembayaran->tagihanSpp->tahun ?? ''),
            'Rp ' . number_format($pembayaran->jumlah_bayar, 0, ',', '.'),
            $pembayaran->tanggal_bayar->format('d/m/Y'),
            $pembayaran->metode_bayar_text,
            $pembayaran->status_text,
            $pembayaran->nama_verifikator ?? '-',
            $pembayaran->verified_at ? $pembayaran->verified_at->format('d/m/Y H:i') : '-',
            $pembayaran->catatan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3490DC']]
            ],
            // Auto size columns
            'A:K' => ['alignment' => ['vertical' => 'center']],
        ];
    }
}