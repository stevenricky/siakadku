<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapor Siswa</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 10mm;
        }
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, 'Inter', 'Roboto', sans-serif; 
            font-size: 8pt; 
            line-height: 1.1;
            background: white;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        /* HEADER - Compact */
        .header { 
            text-align: center; 
            margin-bottom: 8pt; 
            padding: 6pt 0 8pt 0; 
            border-bottom: 2pt solid #000; 
        }
        .school-name { 
            font-size: 16pt; 
            font-weight: 700; 
            margin-bottom: 3pt;
            letter-spacing: 0.5pt;
            color: #000;
            text-transform: uppercase;
        }
        .school-address { 
            font-size: 9pt; 
            color: #000; 
            margin-bottom: 6pt;
            font-weight: 500;
        }
        .title { 
            font-size: 12pt; 
            font-weight: 800;
            background: #1e3a8a;
            color: #ffffff !important;
            padding: 6pt 20pt;
            display: inline-block;
            margin-top: 4pt;
            border-radius: 4pt;
            letter-spacing: 1pt;
            text-transform: uppercase;
            border: 1pt solid #000;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        /* STUDENT INFO - Compact */
        .student-info { 
            margin-bottom: 8pt; 
            padding: 4pt; 
            background: #f5f5f5; 
            border: 0.5pt solid #ccc;
            font-size: 8pt;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 1pt;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-col {
            display: table-cell;
            width: 50%;
        }
        .info-label { 
            font-weight: bold; 
            display: inline-block;
            width: 60pt;
        }
        .info-value {
            display: inline;
        }
        
        /* GROUP HEADERS - Compact */
        .group-header {
            color: white;
            padding: 3pt 6pt;
            margin: 6pt 0 4pt 0;
            font-size: 8pt;
            font-weight: bold;
        }
        .group-I { background: #2c5aa0; }
        .group-II { background: #16a34a; }
        .group-III { background: #dc2626; }
        
        /* TABLE - Compact */
        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6pt;
            font-size: 7pt;
        }
        .nilai-table th,
        .nilai-table td {
            border: 0.5pt solid #000;
            padding: 2pt 1pt;
            text-align: center;
            vertical-align: middle;
        }
        .nilai-table th {
            background: #ddd;
            color: #000;
            font-weight: bold;
            font-size: 6.5pt;
            padding: 3pt 1pt;
        }
        
        /* COLUMN WIDTHS - More Compact */
        .nilai-table .no-col { width: 4%; }
        .nilai-table .mapel-col { 
            width: 22%; 
            text-align: left; 
            padding-left: 3pt;
            font-size: 7pt;
        }
        .nilai-table .guru-col { 
            width: 20%; 
            text-align: left; 
            padding-left: 3pt;
            font-size: 6.5pt;
        }
        .nilai-col { width: 5%; font-size: 7pt; }
        .akhir-col { width: 6%; font-weight: bold; background: #ffffcc; }
        .predikat-col { width: 4%; }
        .status-col { width: 4%; }
        .kkm-col { width: 4%; font-size: 6.5pt; }
        
        /* STATUS & PREDIKAT - Compact */
        .lulus { 
            color: #16a34a; 
            font-weight: bold; 
            font-size: 7pt; 
        }
        .tidak-lulus { 
            color: #dc2626; 
            font-weight: bold; 
            font-size: 7pt; 
        }
        .predikat-a { 
            background: #16a34a; 
            color: white; 
            padding: 1pt 2pt;
            font-weight: bold;
            font-size: 6.5pt;
        }
        .predikat-b { 
            background: #2563eb; 
            color: white; 
            padding: 1pt 2pt;
            font-weight: bold;
            font-size: 6.5pt;
        }
        .predikat-c { 
            background: #ca8a04; 
            color: white; 
            padding: 1pt 2pt;
            font-weight: bold;
            font-size: 6.5pt;
        }
        .predikat-d { 
            background: #dc2626; 
            color: white; 
            padding: 1pt 2pt;
            font-weight: bold;
            font-size: 6.5pt;
        }
        
        /* STATISTICS - Compact */
        .statistics {
            margin: 6pt 0;
            padding: 6pt;
            background: #e3f2fd;
            border: 1pt solid #2196f3;
            font-size: 7pt;
        }
        .stats-grid { 
            display: table;
            width: 100%;
        }
        .stat-item { 
            display: table-cell;
            text-align: center; 
            width: 20%;
            padding: 2pt;
        }
        .stat-value { 
            font-size: 10pt; 
            font-weight: bold; 
            color: #1565c0;
            margin-bottom: 1pt;
        }
        .stat-label { 
            font-size: 6.5pt; 
            color: #333;
            font-weight: 600;
        }
        
        /* PERINGKAT KELAS - Compact */
        .peringkat-section {
            margin: 6pt 0;
            padding: 6pt;
            background: #fff3cd;
            border: 1pt solid #ffc107;
            font-size: 7pt;
        }
        .peringkat-info {
            display: table;
            width: 100%;
            margin-bottom: 4pt;
        }
        .peringkat-item {
            display: table-cell;
            text-align: center;
            width: 25%;
            padding: 2pt;
        }
        .peringkat-value {
            font-size: 9pt;
            font-weight: bold;
            color: #856404;
            margin-bottom: 1pt;
        }
        .peringkat-label {
            font-size: 6.5pt;
            color: #856404;
            font-weight: 600;
        }
        
        /* HASIL AKHIR - Compact */
        .hasil-akhir {
            margin: 6pt 0;
            padding: 6pt;
            background: #f8f9fa;
            border: 1pt solid #28a745;
            text-align: center;
            font-size: 7pt;
        }
        .hasil-lulus {
            background: #d4edda;
            border-color: #28a745;
        }
        .hasil-tidak-lulus {
            background: #f8d7da;
            border-color: #dc3545;
        }
        .hasil-title {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 2pt;
            color: #155724;
        }
        .hasil-tidak-lulus .hasil-title {
            color: #721c24;
        }
        .hasil-status {
            font-size: 12pt;
            font-weight: 800;
            margin-bottom: 2pt;
            text-transform: uppercase;
        }
        .hasil-pesan {
            font-size: 7pt;
            font-style: italic;
            color: #0c5460;
            line-height: 1.2;
        }
        .hasil-tidak-lulus .hasil-pesan {
            color: #721c24;
        }
        
        /* SIGNATURES - Compact */
        .footer { 
            margin-top: 8pt; 
            width: 100%;
        }
        .signature-wrapper {
            display: table;
            width: 100%;
        }
        .signature { 
            display: table-cell;
            text-align: center; 
            width: 50%; 
            vertical-align: bottom;
        }
        .signature-label {
            font-size: 8pt;
            margin-bottom: 20pt;
            font-weight: 600;
        }
        .signature-name { 
            font-size: 8pt; 
            font-weight: bold;
            border-top: 0.5pt solid #000;
            padding-top: 1pt;
            display: inline-block;
            min-width: 100pt;
        }
        .signature-role { 
            font-size: 7pt; 
            color: #555;
            margin-top: 1pt;
        }
        
        /* FOOTNOTE - Compact */
        .footnote { 
            text-align: center; 
            margin-top: 6pt; 
            padding-top: 4pt;
            border-top: 0.5pt solid #ccc;
            font-size: 6pt; 
            color: #999; 
        }

        /* Print optimization */
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 8pt;
            }
            .no-print {
                display: none;
            }
            table { 
                page-break-inside: avoid;
                font-size: 7pt;
            }
            .title {
                background: #1e3a8a !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }

        /* Force single page */
        .page-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="page-break">
        <!-- HEADER -->
        <div class="header">
            <h1 class="school-name">SILABAN HIGH SCHOOL</h1>
            <p class="school-address">Jl. Pendidikan No. 154, Kota Tarutung</p>
            <div class="title">RAPOR SEMESTER {{ strtoupper($semester) }}</div>
        </div>

        <!-- STUDENT INFO -->
        <div class="student-info">
            <div class="info-row">
                <div class="info-col">
                    <div class="info-label">Nama</div>
                    <div class="info-value">: {{ $siswa->nama_lengkap }}</div>
                </div>
                <div class="info-col">
                    <div class="info-label">Kelas</div>
                    <div class="info-value">: {{ $siswa->kelas->nama_kelas ?? '-' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-col">
                    <div class="info-label">NIS</div>
                    <div class="info-value">: {{ $siswa->nis }}</div>
                </div>
                <div class="info-col">
                    <div class="info-label">Tahun Ajaran</div>
                    <div class="info-value">: {{ $tahunAjaran->tahun_ajaran ?? '2024/2025' }}</div>
                </div>
            </div>
        </div>

        @php
            // Kelompokkan mata pelajaran
            $kelompokA = $nilai->filter(function($item) {
                $mapelWajib = [
                    'Pendidikan Agama Islam',
                    'Pendidikan Pancasila dan Kewarganegaraan', 
                    'Bahasa Indonesia',
                    'Bahasa Inggris',
                    'Matematika',
                    'Sejarah Indonesia',
                    'Seni Budaya',
                    'Pendidikan Jasmani, Olahraga dan Kesehatan',
                    'Prakarya dan Kewirausahaan'
                ];
                return in_array($item->mapel->nama_mapel ?? '', $mapelWajib);
            });
            
            $kelompokB = $nilai->filter(function($item) {
                $mapelPeminatan = [
                    'Biologi',
                    'Fisika', 
                    'Kimia'
                ];
                return in_array($item->mapel->nama_mapel ?? '', $mapelPeminatan);
            });
            
            $kelompokC = $nilai->filter(function($item) {
                $mapelPraktikum = [
                    'Praktikum Biologi',
                    'Praktikum Fisika',
                    'Praktikum Kimia',
                    'Praktikum Komputer'
                ];
                return in_array($item->mapel->nama_mapel ?? '', $mapelPraktikum);
            });

            // Hitung peringkat kelas
            $semester = $semester ?? 'ganjil';
            $tahunAjaranId = $tahunAjaran->id ?? null;
            
            $siswaKelas = \App\Models\Siswa::with(['user', 'kelas'])
                ->where('kelas_id', $siswa->kelas_id)
                ->get();

            $peringkatKelas = [];
            foreach ($siswaKelas as $siswaItem) {
                $nilaiSiswa = \App\Models\Nilai::where('siswa_id', $siswaItem->id)
                    ->when($semester, function($query) use ($semester) {
                        $query->where('semester', $semester);
                    })
                    ->when($tahunAjaranId, function($query) use ($tahunAjaranId) {
                        $query->where('tahun_ajaran_id', $tahunAjaranId);
                    })
                    ->get();

                if ($nilaiSiswa->count() > 0) {
                    $rataRata = $nilaiSiswa->avg('nilai_akhir');
                    $peringkatKelas[] = [
                        'siswa' => $siswaItem,
                        'rata_rata' => round($rataRata, 2),
                        'jumlah_mapel' => $nilaiSiswa->count(),
                        'peringkat' => 0,
                    ];
                }
            }

            // Urutkan berdasarkan rata-rata tertinggi
            usort($peringkatKelas, function($a, $b) {
                return $b['rata_rata'] <=> $a['rata_rata'];
            });

            // Beri peringkat dan cari peringkat siswa
            $peringkatSaya = null;
            foreach ($peringkatKelas as $index => &$data) {
                $data['peringkat'] = $index + 1;
                if ($data['siswa']->id === $siswa->id) {
                    $peringkatSaya = $data;
                }
            }

            // Tentukan hasil akhir
            $totalMapel = $statistics['totalMapel'] ?? 0;
            $mapelLulus = $statistics['lulus'] ?? 0;
            $persentaseLulus = $totalMapel > 0 ? ($mapelLulus / $totalMapel) * 100 : 0;
            
            // Kriteria kelulusan: Minimal 80% mapel harus lulus
            $dinyatakanLulus = $persentaseLulus >= 80;
            
            // Pesan apresiasi
            if ($dinyatakanLulus) {
                if ($peringkatSaya && $peringkatSaya['peringkat'] <= 3) {
                    $pesanApresiasi = "Selamat! Prestasi luar biasa dengan peringkat {$peringkatSaya['peringkat']} di kelas. Pertahankan semangat belajarnya!";
                } elseif ($peringkatSaya && $peringkatSaya['peringkat'] <= 10) {
                    $pesanApresiasi = "Kerja bagus! Peringkat {$peringkatSaya['peringkat']} menunjukkan dedikasi yang baik. Terus tingkatkan!";
                } else {
                    $pesanApresiasi = "Selamat! Anda telah menyelesaikan semester ini dengan baik. Terus semangat belajar untuk mencapai yang terbaik!";
                }
            } else {
                $pesanApresiasi = "Tetap semangat! Gunakan hasil ini sebagai motivasi untuk belajar lebih giat lagi. Anda pasti bisa!";
            }
        @endphp

        <!-- KELOMPOK A: MAPEL WAJIB -->
        <div class="group-header group-I">
            A. KELOMPOK MATA PELAJARAN UMUM (WAJIB)
        </div>
        <table class="nilai-table">
            <thead>
                <tr>
                    <th class="no-col">No</th>
                    <th class="mapel-col">Mata Pelajaran</th>
                    <th class="guru-col">Guru Pengampu</th>
                    <th class="nilai-col">UH1</th>
                    <th class="nilai-col">UH2</th>
                    <th class="nilai-col">UTS</th>
                    <th class="nilai-col">UAS</th>
                    <th class="akhir-col">Akhir</th>
                    <th class="predikat-col">NH</th>
                    <th class="status-col">Status</th>
                    <th class="kkm-col">KKM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelompokA as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="mapel-col">{{ $item->mapel->nama_mapel ?? 'N/A' }}</td>
                    <td class="guru-col">{{ $item->guru->nama_lengkap ?? 'N/A' }}</td>
                    <td>{{ $item->nilai_uh1 ? number_format($item->nilai_uh1, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uh2 ? number_format($item->nilai_uh2, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uts ? number_format($item->nilai_uts, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uas ? number_format($item->nilai_uas, 0) : '-' }}</td>
                    <td class="akhir-col">{{ $item->nilai_akhir ? number_format($item->nilai_akhir, 1) : '-' }}</td>
                    <td>
                        @if($item->predikat)
                            @php
                                $predikatClass = match($item->predikat) {
                                    'A' => 'predikat-a',
                                    'B' => 'predikat-b',
                                    'C' => 'predikat-c',
                                    'D' => 'predikat-d',
                                    default => 'predikat-c'
                                };
                            @endphp
                            <span class="{{ $predikatClass }}">{{ $item->predikat }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="{{ $item->nilai_akhir >= ($item->mapel->kkm ?? 75) ? 'lulus' : 'tidak-lulus' }}">
                        {{ $item->nilai_akhir >= ($item->mapel->kkm ?? 75) ? 'L' : 'TL' }}
                    </td>
                    <td>{{ $item->mapel->kkm ?? 75 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- KELOMPOK B: PEMINATAN IPA -->
        <div class="group-header group-II">
            B. KELOMPOK PEMINATAN IPA
        </div>
        <table class="nilai-table">
            <thead>
                <tr>
                    <th class="no-col">No</th>
                    <th class="mapel-col">Mata Pelajaran</th>
                    <th class="guru-col">Guru Pengampu</th>
                    <th class="nilai-col">UH1</th>
                    <th class="nilai-col">UH2</th>
                    <th class="nilai-col">UTS</th>
                    <th class="nilai-col">UAS</th>
                    <th class="akhir-col">Akhir</th>
                    <th class="predikat-col">NH</th>
                    <th class="status-col">Status</th>
                    <th class="kkm-col">KKM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelompokB as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="mapel-col">{{ $item->mapel->nama_mapel ?? 'N/A' }}</td>
                    <td class="guru-col">{{ $item->guru->nama_lengkap ?? 'N/A' }}</td>
                    <td>{{ $item->nilai_uh1 ? number_format($item->nilai_uh1, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uh2 ? number_format($item->nilai_uh2, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uts ? number_format($item->nilai_uts, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uas ? number_format($item->nilai_uas, 0) : '-' }}</td>
                    <td class="akhir-col">{{ $item->nilai_akhir ? number_format($item->nilai_akhir, 1) : '-' }}</td>
                    <td>
                        @if($item->predikat)
                            @php
                                $predikatClass = match($item->predikat) {
                                    'A' => 'predikat-a',
                                    'B' => 'predikat-b',
                                    'C' => 'predikat-c',
                                    'D' => 'predikat-d',
                                    default => 'predikat-c'
                                };
                            @endphp
                            <span class="{{ $predikatClass }}">{{ $item->predikat }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="{{ $item->nilai_akhir >= ($item->mapel->kkm ?? 75) ? 'lulus' : 'tidak-lulus' }}">
                        {{ $item->nilai_akhir >= ($item->mapel->kkm ?? 75) ? 'L' : 'TL' }}
                    </td>
                    <td>{{ $item->mapel->kkm ?? 75 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- KELOMPOK C: PRAKTIKUM -->
        <div class="group-header group-III">
            C. KELOMPOK PRAKTIKUM
        </div>
        <table class="nilai-table">
            <thead>
                <tr>
                    <th class="no-col">No</th>
                    <th class="mapel-col">Mata Pelajaran</th>
                    <th class="guru-col">Guru Pengampu</th>
                    <th class="nilai-col">UH1</th>
                    <th class="nilai-col">UH2</th>
                    <th class="nilai-col">UTS</th>
                    <th class="nilai-col">UAS</th>
                    <th class="akhir-col">Akhir</th>
                    <th class="predikat-col">NH</th>
                    <th class="status-col">Status</th>
                    <th class="kkm-col">KKM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelompokC as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="mapel-col">{{ $item->mapel->nama_mapel ?? 'N/A' }}</td>
                    <td class="guru-col">{{ $item->guru->nama_lengkap ?? 'N/A' }}</td>
                    <td>{{ $item->nilai_uh1 ? number_format($item->nilai_uh1, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uh2 ? number_format($item->nilai_uh2, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uts ? number_format($item->nilai_uts, 0) : '-' }}</td>
                    <td>{{ $item->nilai_uas ? number_format($item->nilai_uas, 0) : '-' }}</td>
                    <td class="akhir-col">{{ $item->nilai_akhir ? number_format($item->nilai_akhir, 1) : '-' }}</td>
                    <td>
                        @if($item->predikat)
                            @php
                                $predikatClass = match($item->predikat) {
                                    'A' => 'predikat-a',
                                    'B' => 'predikat-b',
                                    'C' => 'predikat-c',
                                    'D' => 'predikat-d',
                                    default => 'predikat-c'
                                };
                            @endphp
                            <span class="{{ $predikatClass }}">{{ $item->predikat }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="{{ $item->nilai_akhir >= ($item->mapel->kkm ?? 75) ? 'lulus' : 'tidak-lulus' }}">
                        {{ $item->nilai_akhir >= ($item->mapel->kkm ?? 75) ? 'L' : 'TL' }}
                    </td>
                    <td>{{ $item->mapel->kkm ?? 75 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- STATISTICS -->
        <div class="statistics">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ number_format($statistics['average'], 1) }}</div>
                    <div class="stat-label">RATA-RATA</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $statistics['totalMapel'] }}</div>
                    <div class="stat-label">TOTAL MAPEL</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $statistics['lulus'] }}</div>
                    <div class="stat-label">LULUS</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">
                        @if($statistics['totalMapel'] > 0)
                            {{ number_format(($statistics['lulus'] / $statistics['totalMapel']) * 100, 0) }}%
                        @else
                            0%
                        @endif
                    </div>
                    <div class="stat-label">PERSENTASE LULUS</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $peringkatSaya ? $peringkatSaya['peringkat'] : '-' }}</div>
                    <div class="stat-label">PERINGKAT</div>
                </div>
            </div>
        </div>

        <!-- PERINGKAT KELAS -->
        @if($peringkatSaya)
        <div class="peringkat-section">
            <div class="peringkat-info">
                <div class="peringkat-item">
                    <div class="peringkat-value">{{ $peringkatSaya['peringkat'] }}</div>
                    <div class="peringkat-label">Peringkat di Kelas</div>
                </div>
                <div class="peringkat-item">
                    <div class="peringkat-value">{{ $peringkatSaya['rata_rata'] }}</div>
                    <div class="peringkat-label">Nilai Rata-rata</div>
                </div>
                <div class="peringkat-item">
                    <div class="peringkat-value">{{ $peringkatSaya['jumlah_mapel'] }}</div>
                    <div class="peringkat-label">Total Mapel</div>
                </div>
                <div class="peringkat-item">
                    <div class="peringkat-value">{{ count($peringkatKelas) }}</div>
                    <div class="peringkat-label">Total Siswa</div>
                </div>
            </div>
        </div>
        @endif

        <!-- HASIL AKHIR -->
        <div class="hasil-akhir {{ $dinyatakanLulus ? 'hasil-lulus' : 'hasil-tidak-lulus' }}">
            <div class="hasil-title">HASIL AKHIR SEMESTER</div>
            <div class="hasil-status">
                Dinyatakan {{ $dinyatakanLulus ? 'LULUS' : 'TIDAK LULUS' }}
            </div>
            <div class="hasil-pesan">
                {{ $pesanApresiasi }}
            </div>
        </div>

        <!-- SIGNATURES -->
        <div class="footer">
            <div class="signature-wrapper">
                <!-- KIRI: Wali Kelas -->
                <div class="signature">
                    <div class="signature-label">Wali Kelas</div>
                    <div class="signature-name">{{ $siswa->kelas->waliKelas->nama_lengkap ?? 'Dra. Sri Wahyuni' }}</div>
                    <div class="signature-role">NIP. {{ $siswa->kelas->waliKelas->nip ?? '196805121993032001' }}</div>
                </div>
                
                <!-- KANAN: Kepala Sekolah -->
                <div class="signature">
                    <div class="signature-label">Kepala Sekolah</div>
                    <div class="signature-name">Prof Ricky Steven Silaban M.Kom</div>
                    <div class="signature-role">NIP. 196512101990031007</div>
                </div>
            </div>
        </div>

        <!-- FOOTNOTE -->
        <div class="footnote">
            Dicetak: {{ now()->format('d/m/Y H:i') }} WIB
        </div>
    </div>
</body>
</html>