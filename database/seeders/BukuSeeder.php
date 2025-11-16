<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBuku;
use App\Models\Buku;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        // Buat kategori buku terlebih dahulu
        $kategoriData = [
            [
                'nama_kategori' => 'Pendidikan', 
                'kode_kategori' => 'PEN',
                'deskripsi' => 'Buku-buku pendidikan dan pembelajaran', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Fiksi', 
                'kode_kategori' => 'FIK',
                'deskripsi' => 'Novel, cerpen, dan karya fiksi lainnya', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Sains & Teknologi', 
                'kode_kategori' => 'SNS',
                'deskripsi' => 'Buku ilmu pengetahuan dan teknologi', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Sejarah & Biografi', 
                'kode_kategori' => 'SEJ',
                'deskripsi' => 'Buku sejarah dan biografi tokoh', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Agama & Spiritual', 
                'kode_kategori' => 'AGM',
                'deskripsi' => 'Buku keagamaan dan spiritualitas', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Bisnis & Ekonomi', 
                'kode_kategori' => 'BIS',
                'deskripsi' => 'Buku bisnis, ekonomi, dan manajemen', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Seni & Budaya', 
                'kode_kategori' => 'SEN',
                'deskripsi' => 'Buku seni, budaya, dan kesenian', 
                'status' => true
            ],
        ];

        $kategoriIds = [];
        foreach ($kategoriData as $kat) {
            $kategori = KategoriBuku::firstOrCreate(
                ['kode_kategori' => $kat['kode_kategori']],
                $kat
            );
            $kategoriIds[$kat['kode_kategori']] = $kategori->id;
        }

        // Sample data 40 buku dengan kategori_id yang benar
        $buku = [
            // Pendidikan (8 buku)
            [
                'isbn' => '9786020332956',
                'judul' => 'Pendidikan Karakter Bangsa',
                'penulis' => 'Prof. Dr. Arief Rahman',
                'penerbit' => 'Pustaka Pelajar',
                'tahun_terbit' => 2020,
                'kategori_id' => $kategoriIds['PEN'],
                'stok' => 10,
                'dipinjam' => 3,
                'rak_buku' => 'A1',
                'deskripsi' => 'Buku tentang pentingnya pendidikan karakter dalam membangun bangsa yang beradab dan bermartabat.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786028519937',
                'judul' => 'Strategi Pembelajaran Modern',
                'penulis' => 'Dr. Siti Aminah',
                'penerbit' => 'Edu Press',
                'tahun_terbit' => 2021,
                'kategori_id' => $kategoriIds['PEN'],
                'stok' => 8,
                'dipinjam' => 2,
                'rak_buku' => 'A2',
                'deskripsi' => 'Panduan lengkap strategi pembelajaran abad 21 untuk guru dan pendidik.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020332963',
                'judul' => 'Psikologi Pendidikan',
                'penulis' => 'Dr. Bambang Hartono',
                'penerbit' => 'Academic Press',
                'tahun_terbit' => 2019,
                'kategori_id' => $kategoriIds['PEN'],
                'stok' => 12,
                'dipinjam' => 5,
                'rak_buku' => 'A3',
                'deskripsi' => 'Memahami psikologi dalam konteks pendidikan untuk pengajaran yang efektif.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020332970',
                'judul' => 'Metodologi Penelitian Pendidikan',
                'penulis' => 'Prof. Dr. H. Muhammad Zain',
                'penerbit' => 'Research Center',
                'tahun_terbit' => 2022,
                'kategori_id' => $kategoriIds['PEN'],
                'stok' => 15,
                'dipinjam' => 7,
                'rak_buku' => 'A4',
                'deskripsi' => 'Panduan praktis metodologi penelitian untuk mahasiswa dan peneliti pendidikan.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020332987',
                'judul' => 'Kurikulum dan Pembelajaran',
                'penulis' => 'Dr. Linda Sari',
                'penerbit' => 'Curriculum Press',
                'tahun_terbit' => 2020,
                'kategori_id' => $kategoriIds['PEN'],
                'stok' => 9,
                'dipinjam' => 4,
                'rak_buku' => 'A5',
                'deskripsi' => 'Analisis mendalam tentang pengembangan kurikulum dan implementasinya.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020332994',
                'judul' => 'Teknologi dalam Pendidikan',
                'penulis' => 'Ir. Ahmad Fauzi',
                'penerbit' => 'Tech Edu',
                'tahun_terbit' => 2023,
                'kategori_id' => $kategoriIds['PEN'],
                'stok' => 11,
                'dipinjam' => 6,
                'rak_buku' => 'A6',
                'deskripsi' => 'Pemanfaatan teknologi digital dalam proses pembelajaran modern.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333007',
                'judul' => 'Evaluasi Pembelajaran',
                'penulis' => 'Dr. Rina Wijaya',
                'penerbit' => 'Assessment Press',
                'tahun_terbit' => 2021,
                'kategori_id' => $kategoriIds['PEN'],
                'stok' => 7,
                'dipinjam' => 3,
                'rak_buku' => 'A7',
                'deskripsi' => 'Teknik dan metode evaluasi pembelajaran yang komprehensif.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333014',
                'judul' => 'Manajemen Pendidikan',
                'penulis' => 'Prof. Dr. Surya Dharma',
                'penerbit' => 'Management Press',
                'tahun_terbit' => 2022,
                'kategori_id' => $kategoriIds['PEN'],
                'stok' => 10,
                'dipinjam' => 5,
                'rak_buku' => 'A8',
                'deskripsi' => 'Prinsip-prinsip manajemen dalam institusi pendidikan.',
                'status' => 'tersedia'
            ],

            // Fiksi (8 buku)
            [
                'isbn' => '9786028519939',
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'kategori_id' => $kategoriIds['FIK'],
                'stok' => 15,
                'dipinjam' => 8,
                'rak_buku' => 'B1',
                'deskripsi' => 'Novel inspiratif tentang perjuangan anak-anak Belitung mengejar pendidikan.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020328768',
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Lentera Dipantara',
                'tahun_terbit' => 1980,
                'kategori_id' => $kategoriIds['FIK'],
                'stok' => 12,
                'dipinjam' => 9,
                'rak_buku' => 'B2',
                'deskripsi' => 'Karya sastra monumental tentang perjuangan di era kolonial.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333021',
                'judul' => 'Ronggeng Dukuh Paruk',
                'penulis' => 'Ahmad Tohari',
                'penerbit' => 'Gramedia Pustaka',
                'tahun_terbit' => 1982,
                'kategori_id' => $kategoriIds['FIK'],
                'stok' => 8,
                'dipinjam' => 4,
                'rak_buku' => 'B3',
                'deskripsi' => 'Kisah tragis penari ronggeng di pedesaan Jawa.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333038',
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'Ahmad Fuadi',
                'penerbit' => 'Gramedia Pustaka',
                'tahun_terbit' => 2009,
                'kategori_id' => $kategoriIds['FIK'],
                'stok' => 14,
                'dipinjam' => 7,
                'rak_buku' => 'B4',
                'deskripsi' => 'Inspirasi dari pesantren dan impian meraih kesuksesan.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333045',
                'judul' => 'Pulang',
                'penulis' => 'Leila S. Chudori',
                'penerbit' => 'Kepustakaan Populer Gramedia',
                'tahun_terbit' => 2012,
                'kategori_id' => $kategoriIds['FIK'],
                'stok' => 10,
                'dipinjam' => 5,
                'rak_buku' => 'B5',
                'deskripsi' => 'Kisah eksil Indonesia dan kerinduan akan tanah air.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333052',
                'judul' => 'Saman',
                'penulis' => 'Ayu Utami',
                'penerbit' => 'Kepustakaan Populer Gramedia',
                'tahun_terbit' => 1998,
                'kategori_id' => $kategoriIds['FIK'],
                'stok' => 9,
                'dipinjam' => 6,
                'rak_buku' => 'B6',
                'deskripsi' => 'Novel pembuka angkatan sastrawi dengan gaya penulisan baru.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333069',
                'judul' => 'Cantik Itu Luka',
                'penulis' => 'Eka Kurniawan',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2002,
                'kategori_id' => $kategoriIds['FIK'],
                'stok' => 11,
                'dipinjam' => 8,
                'rak_buku' => 'B7',
                'deskripsi' => 'Epik keluarga dengan latar sejarah Indonesia modern.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333076',
                'judul' => 'Perahu Kertas',
                'penulis' => 'Dee Lestari',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2009,
                'kategori_id' => $kategoriIds['FIK'],
                'stok' => 13,
                'dipinjam' => 7,
                'rak_buku' => 'B8',
                'deskripsi' => 'Kisah cinta dan perjuangan meraih mimpi.',
                'status' => 'tersedia'
            ],

            // Sains & Teknologi (8 buku)
            [
                'isbn' => '9789792297437',
                'judul' => 'Fisika Dasar Edisi 10',
                'penulis' => 'Halliday & Resnick',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2018,
                'kategori_id' => $kategoriIds['SNS'],
                'stok' => 8,
                'dipinjam' => 5,
                'rak_buku' => 'C1',
                'deskripsi' => 'Buku teks fisika dasar terbaik untuk mahasiswa sains dan teknik.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333083',
                'judul' => 'Kimia Organik Modern',
                'penulis' => 'Prof. Dr. Robert Thornton',
                'penerbit' => 'Science Press',
                'tahun_terbit' => 2020,
                'kategori_id' => $kategoriIds['SNS'],
                'stok' => 10,
                'dipinjam' => 4,
                'rak_buku' => 'C2',
                'deskripsi' => 'Pembahasan lengkap kimia organik dengan pendekatan modern.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333090',
                'judul' => 'Biologi Molekuler',
                'penulis' => 'Dr. Elizabeth Wilson',
                'penerbit' => 'Bio Science',
                'tahun_terbit' => 2021,
                'kategori_id' => $kategoriIds['SNS'],
                'stok' => 7,
                'dipinjam' => 3,
                'rak_buku' => 'C3',
                'deskripsi' => 'Prinsip-prinsip biologi molekuler dan aplikasinya.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333106',
                'judul' => 'Pemrograman Python',
                'penulis' => 'John Smith',
                'penerbit' => 'Tech Publishing',
                'tahun_terbit' => 2023,
                'kategori_id' => $kategoriIds['SNS'],
                'stok' => 15,
                'dipinjam' => 9,
                'rak_buku' => 'C4',
                'deskripsi' => 'Panduan lengkap pemrograman Python untuk pemula hingga mahir.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333113',
                'judul' => 'Artificial Intelligence',
                'penulis' => 'Dr. Michael Chen',
                'penerbit' => 'AI Press',
                'tahun_terbit' => 2022,
                'kategori_id' => $kategoriIds['SNS'],
                'stok' => 12,
                'dipinjam' => 8,
                'rak_buku' => 'C5',
                'deskripsi' => 'Pengantar kecerdasan buatan dan machine learning.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333120',
                'judul' => 'Astronomi Modern',
                'penulis' => 'Neil deGrasse Tyson',
                'penerbit' => 'Space Books',
                'tahun_terbit' => 2021,
                'kategori_id' => $kategoriIds['SNS'],
                'stok' => 9,
                'dipinjam' => 5,
                'rak_buku' => 'C6',
                'deskripsi' => 'Penjelasan tentang alam semesta dan penemuan astronomi terbaru.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333137',
                'judul' => 'Robotika dan Otomasi',
                'penulis' => 'Prof. Dr. Tanaka',
                'penerbit' => 'Robotics Inc',
                'tahun_terbit' => 2020,
                'kategori_id' => $kategoriIds['SNS'],
                'stok' => 8,
                'dipinjam' => 4,
                'rak_buku' => 'C7',
                'deskripsi' => 'Dasar-dasar robotika dan sistem otomasi industri.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333144',
                'judul' => 'Data Science Handbook',
                'penulis' => 'Dr. Sarah Johnson',
                'penerbit' => 'Data Press',
                'tahun_terbit' => 2023,
                'kategori_id' => $kategoriIds['SNS'],
                'stok' => 11,
                'dipinjam' => 6,
                'rak_buku' => 'C8',
                'deskripsi' => 'Panduan praktis ilmu data dan analitik.',
                'status' => 'tersedia'
            ],

            // Sejarah & Biografi (8 buku)
            [
                'isbn' => '9786020333151',
                'judul' => 'Sejarah Indonesia Modern',
                'penulis' => 'Prof. Dr. Merle Ricklefs',
                'penerbit' => 'Gadjah Mada Press',
                'tahun_terbit' => 2019,
                'kategori_id' => $kategoriIds['SEJ'],
                'stok' => 10,
                'dipinjam' => 5,
                'rak_buku' => 'D1',
                'deskripsi' => 'Analisis komprehensif sejarah Indonesia dari masa kolonial hingga reformasi.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333168',
                'judul' => 'Soekarno: Biografi Singkat',
                'penulis' => 'Dr. Asvi Warman Adam',
                'penerbit' => 'Historia Press',
                'tahun_terbit' => 2020,
                'kategori_id' => $kategoriIds['SEJ'],
                'stok' => 12,
                'dipinjam' => 7,
                'rak_buku' => 'D2',
                'deskripsi' => 'Biografi bapak proklamator Indonesia, Ir. Soekarno.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333175',
                'judul' => 'Sejarah Dunia Kuno',
                'penulis' => 'Prof. John Boardman',
                'penerbit' => 'Ancient Press',
                'tahun_terbit' => 2018,
                'kategori_id' => $kategoriIds['SEJ'],
                'stok' => 8,
                'dipinjam' => 4,
                'rak_buku' => 'D3',
                'deskripsi' => 'Peradaban kuno dunia dari Mesir, Yunani, hingga Romawi.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333182',
                'judul' => 'Biografi Steve Jobs',
                'penulis' => 'Walter Isaacson',
                'penerbit' => 'Simon & Schuster',
                'tahun_terbit' => 2011,
                'kategori_id' => $kategoriIds['SEJ'],
                'stok' => 14,
                'dipinjam' => 9,
                'rak_buku' => 'D4',
                'deskripsi' => 'Kisah inspiratif pendiri Apple yang mengubah dunia teknologi.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333199',
                'judul' => 'Sejarah Perang Dunia II',
                'penulis' => 'Antony Beevor',
                'penerbit' => 'Military History',
                'tahun_terbit' => 2012,
                'kategori_id' => $kategoriIds['SEJ'],
                'stok' => 9,
                'dipinjam' => 5,
                'rak_buku' => 'D5',
                'deskripsi' => 'Narasi detail tentang Perang Dunia II dari berbagai perspektif.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333205',
                'judul' => 'Biografi Nelson Mandela',
                'penulis' => 'Mandela Foundation',
                'penerbit' => 'Freedom Press',
                'tahun_terbit' => 2018,
                'kategori_id' => $kategoriIds['SEJ'],
                'stok' => 11,
                'dipinjam' => 6,
                'rak_buku' => 'D6',
                'deskripsi' => 'Perjuangan dan kehidupan Nelson Mandela melawan apartheid.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333212',
                'judul' => 'Sejarah Nusantara',
                'penulis' => 'Prof. Dr. Slamet Muljana',
                'penerbit' => 'Nusantara Press',
                'tahun_terbit' => 2019,
                'kategori_id' => $kategoriIds['SEJ'],
                'stok' => 7,
                'dipinjam' => 3,
                'rak_buku' => 'D7',
                'deskripsi' => 'Sejarah kerajaan-kerajaan di Nusantara sebelum kemerdekaan.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333229',
                'judul' => 'Biografi Marie Curie',
                'penulis' => 'Eve Curie',
                'penerbit' => 'Science Biography',
                'tahun_terbit' => 2020,
                'kategori_id' => $kategoriIds['SEJ'],
                'stok' => 10,
                'dipinjam' => 5,
                'rak_buku' => 'D8',
                'deskripsi' => 'Kisah ilmuwan perempuan peraih dua Nobel dalam bidang berbeda.',
                'status' => 'dipinjam'
            ],

            // Agama & Spiritual (4 buku)
            [
                'isbn' => '9786020333236',
                'judul' => 'Tafsir Al-Mishbah',
                'penulis' => 'Prof. Dr. M. Quraish Shihab',
                'penerbit' => 'Lentera Hati',
                'tahun_terbit' => 2019,
                'kategori_id' => $kategoriIds['AGM'],
                'stok' => 15,
                'dipinjam' => 8,
                'rak_buku' => 'E1',
                'deskripsi' => 'Tafsir Al-Quran lengkap dengan pendekatan kontekstual.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333243',
                'judul' => 'The Power of Now',
                'penulis' => 'Eckhart Tolle',
                'penerbit' => 'New World Library',
                'tahun_terbit' => 1997,
                'kategori_id' => $kategoriIds['AGM'],
                'stok' => 12,
                'dipinjam' => 7,
                'rak_buku' => 'E2',
                'deskripsi' => 'Panduan spiritual untuk hidup di masa sekarang.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333250',
                'judul' => 'Filsafat Ketuhanan',
                'penulis' => 'Prof. Dr. Komaruddin Hidayat',
                'penerbit' => 'Paramadina Press',
                'tahun_terbit' => 2021,
                'kategori_id' => $kategoriIds['AGM'],
                'stok' => 9,
                'dipinjam' => 4,
                'rak_buku' => 'E3',
                'deskripsi' => 'Kajian filosofis tentang konsep ketuhanan dalam berbagai agama.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333267',
                'judul' => 'Meditasi untuk Pemula',
                'penulis' => 'Jack Kornfield',
                'penerbit' => 'Mindfulness Press',
                'tahun_terbit' => 2022,
                'kategori_id' => $kategoriIds['AGM'],
                'stok' => 11,
                'dipinjam' => 6,
                'rak_buku' => 'E4',
                'deskripsi' => 'Panduan praktis meditasi untuk kesehatan mental dan spiritual.',
                'status' => 'tersedia'
            ],

            // Bisnis & Ekonomi (4 buku)
            [
                'isbn' => '9786020333274',
                'judul' => 'The Lean Startup',
                'penulis' => 'Eric Ries',
                'penerbit' => 'Crown Business',
                'tahun_terbit' => 2011,
                'kategori_id' => $kategoriIds['BIS'],
                'stok' => 13,
                'dipinjam' => 8,
                'rak_buku' => 'F1',
                'deskripsi' => 'Metodologi untuk mengembangkan bisnis dan produk dengan efisien.',
                'status' => 'dipinjam'
            ],
            [
                'isbn' => '9786020333281',
                'judul' => 'Ekonomi Indonesia Kontemporer',
                'penulis' => 'Prof. Dr. Sri Adiningsih',
                'penerbit' => 'UI Press',
                'tahun_terbit' => 2020,
                'kategori_id' => $kategoriIds['BIS'],
                'stok' => 10,
                'dipinjam' => 5,
                'rak_buku' => 'F2',
                'deskripsi' => 'Analisis kondisi ekonomi Indonesia dalam konteks global.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333298',
                'judul' => 'Financial Intelligence',
                'penulis' => 'Karen Berman',
                'penerbit' => 'Harvard Business Review',
                'tahun_terbit' => 2013,
                'kategori_id' => $kategoriIds['BIS'],
                'stok' => 8,
                'dipinjam' => 4,
                'rak_buku' => 'F3',
                'deskripsi' => 'Memahami laporan keuangan untuk pengambilan keputusan bisnis.',
                'status' => 'tersedia'
            ],
            [
                'isbn' => '9786020333304',
                'judul' => 'Marketing 4.0',
                'penulis' => 'Philip Kotler',
                'penerbit' => 'Wiley',
                'tahun_terbit' => 2017,
                'kategori_id' => $kategoriIds['BIS'],
                'stok' => 12,
                'dipinjam' => 7,
                'rak_buku' => 'F4',
                'deskripsi' => 'Strategi pemasaran di era digital dan disruption.',
                'status' => 'dipinjam'
            ],
        ];

         foreach ($buku as $b) {
            Buku::firstOrCreate(
                ['isbn' => $b['isbn']],
                $b
            );
        }

        $this->command->info('Seeder Buku berhasil dijalankan!');
    }
}