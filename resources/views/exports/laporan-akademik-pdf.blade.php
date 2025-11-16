<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Akademik</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 20px; }
        .section-title { background: #f8f9fa; padding: 10px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AKADEMIK</h1>
        <p>SIAKAD SMA - Semester {{ $filters['semester'] }} {{ $filters['tahun_ajaran'] }}</p>
        <p>Kelas: {{ $filters['kelas'] }}</p>
        <p>Dibuat pada: {{ $export_date }}</p>
    </div>

    <div class="section">
        <div class="section-title">STATISTIK UTAMA</div>
        <table>
            <tr>
                <td width="70%">Total Siswa</td>
                <td>{{ $statistics['totalSiswa'] }}</td>
            </tr>
            <tr>
                <td>Total Kelas</td>
                <td>{{ $statistics['totalKelas'] }}</td>
            </tr>
            <tr>
                <td>Rata-rata Nilai Keseluruhan</td>
                <td>{{ number_format($statistics['averageNilai'], 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">DISTRIBUSI PREDIKAT</div>
        <table>
            <thead>
                <tr>
                    <th>Predikat</th>
                    <th>Jumlah Siswa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statistics['predikatStats'] as $stat)
                <tr>
                    <td>{{ $stat['predikat'] }}</td>
                    <td>{{ $stat['count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">5 SISWA TERBAIK</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Rata-rata Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statistics['topPerformers'] as $student)
                <tr>
                    <td>{{ $student['nama'] }}</td>
                    <td>{{ $student['kelas'] }}</td>
                    <td>{{ $student['average'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">5 MATA PELAJARAN TERBAIK</div>
        <table>
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Rata-rata Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statistics['bestSubjects'] as $subject)
                <tr>
                    <td>{{ $subject['mapel'] }}</td>
                    <td>{{ $subject['average'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>