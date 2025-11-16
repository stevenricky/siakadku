<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran SPP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .total { font-weight: bold; background-color: #e9ecef; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMBAYARAN SPP</h1>
        <p>SMA NEGERI 1 EXAMPLE</p>
        <p>Periode: {{ $tanggalCetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Bayar</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Bulan Tagihan</th>
                <th class="text-right">Jumlah Bayar</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->tanggal_bayar->format('d/m/Y') }}</td>
                <td>{{ $item->siswa->nis }}</td>
                <td>{{ $item->siswa->nama }}</td>
                <td>{{ $item->tagihanSpp->bulan }} {{ $item->tagihanSpp->tahun }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                <td>{{ ucfirst($item->metode_bayar) }}</td>
                <td>{{ $item->status_text }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="5" class="text-right"><strong>Total Pembayaran:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>