<?php

namespace App\Livewire\Admin;

use App\Models\TagihanSpp as TagihanSppModel;
use App\Models\Siswa;
use App\Models\BiayaSpp;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app-new')]
class TagihanSpp extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    public $bulanFilter = '';
    public $tahunFilter = '';

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $tagihanId = null;

    public $siswa_id;
    public $biaya_spp_id;
    public $bulan;
    public $tahun;
    public $jumlah_tagihan;
    public $denda = 0;
    public $status = 'belum_bayar';
    public $tanggal_jatuh_tempo;
    public $tanggal_bayar;
    public $keterangan;

    public $bulanList = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    public $statusList = [
        'belum_bayar' => 'Belum Bayar',
        'lunas' => 'Lunas',
        'tertunggak' => 'Tertunggak'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'statusFilter' => ['except' => ''],
        'bulanFilter' => ['except' => ''],
        'tahunFilter' => ['except' => '']
    ];

    protected $rules = [
        'siswa_id' => 'required|exists:siswas,id',
        'biaya_spp_id' => 'required|exists:biaya_spp,id',
        'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
        'tahun' => 'required|integer|min:2020|max:2030',
        'jumlah_tagihan' => 'required|numeric|min:0',
        'denda' => 'nullable|numeric|min:0',
        'status' => 'required|in:belum_bayar,lunas,tertunggak',
        'tanggal_jatuh_tempo' => 'required|date',
        'tanggal_bayar' => 'nullable|date',
        'keterangan' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->tahun = date('Y');
        $this->tahunFilter = date('Y');
        // Set default tanggal jatuh tempo 10 hari dari sekarang
        $this->tanggal_jatuh_tempo = now()->addDays(10)->format('Y-m-d');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedBulanFilter()
    {
        $this->resetPage();
    }

    public function updatedTahunFilter()
    {
        $this->resetPage();
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $tagihan = TagihanSppModel::with(['siswa', 'biayaSpp'])->findOrFail($id);
        
        $this->tagihanId = $tagihan->id;
        $this->siswa_id = $tagihan->siswa_id;
        $this->biaya_spp_id = $tagihan->biaya_spp_id;
        $this->bulan = $tagihan->bulan;
        $this->tahun = $tagihan->tahun;
        $this->jumlah_tagihan = $tagihan->jumlah_tagihan;
        $this->denda = $tagihan->denda;
        $this->status = $tagihan->status;
        $this->tanggal_jatuh_tempo = $tagihan->tanggal_jatuh_tempo->format('Y-m-d');
        $this->tanggal_bayar = $tagihan->tanggal_bayar?->format('Y-m-d');
        $this->keterangan = $tagihan->keterangan;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'tagihanId', 'siswa_id', 'biaya_spp_id', 'bulan', 'tahun', 
            'jumlah_tagihan', 'denda', 'status', 'tanggal_jatuh_tempo', 
            'tanggal_bayar', 'keterangan'
        ]);
        $this->resetErrorBag();
        $this->tahun = date('Y');
        $this->tanggal_jatuh_tempo = now()->addDays(10)->format('Y-m-d');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'bulanFilter', 'tahunFilter']);
        $this->resetPage();
    }

    public function saveTagihan()
    {
        // Auto set tanggal_bayar jika status lunas
        if ($this->status === 'lunas' && empty($this->tanggal_bayar)) {
            $this->tanggal_bayar = now()->format('Y-m-d');
        }

        // Auto clear tanggal_bayar jika status bukan lunas
        if ($this->status !== 'lunas') {
            $this->tanggal_bayar = null;
        }

        $validatedData = $this->validate();

        try {
            DB::transaction(function () use ($validatedData) {
                if ($this->formType === 'create') {
                    TagihanSppModel::create($validatedData);
                    session()->flash('success', 'Data tagihan SPP berhasil ditambahkan.');
                } else {
                    $tagihan = TagihanSppModel::findOrFail($this->tagihanId);
                    $tagihan->update($validatedData);
                    session()->flash('success', 'Data tagihan SPP berhasil diperbarui.');
                }
            });

            $this->closeForm();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteTagihan($id)
    {
        try {
            $tagihan = TagihanSppModel::findOrFail($id);
            $tagihan->delete();
            
            session()->flash('success', 'Data tagihan SPP berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function markAsPaid($id)
    {
        try {
            $tagihan = TagihanSppModel::findOrFail($id);
            $tagihan->update([
                'status' => 'lunas',
                'tanggal_bayar' => now()
            ]);
            
            session()->flash('success', 'Tagihan berhasil ditandai sebagai lunas.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Tambahkan di class TagihanSpp
public function openUploadModal($tagihanId)
{
    $this->dispatch('open-upload-modal', tagihanId: $tagihanId);
}

    public function generateTagihan()
    {
        try {
            $siswas = Siswa::where('status', 'aktif')->get();
            $biayaSpp = BiayaSpp::where('status', 1)->first();

            if (!$biayaSpp) {
                session()->flash('error', 'Tidak ada biaya SPP aktif yang ditemukan.');
                return;
            }

            $currentMonth = date('n');
            $currentYear = date('Y');
            $bulan = $this->bulanList[$currentMonth - 1];
            
            $created = 0;
            $updated = 0;

            foreach ($siswas as $siswa) {
                // Cek apakah tagihan sudah ada
                $existingTagihan = TagihanSppModel::where('siswa_id', $siswa->id)
                    ->where('bulan', $bulan)
                    ->where('tahun', $currentYear)
                    ->first();

                if ($existingTagihan) {
                    $updated++;
                    continue;
                }

                // Hitung tanggal jatuh tempo (tanggal 10 bulan depan)
                $tanggalJatuhTempo = now()->addMonth()->setDay(10);

                TagihanSppModel::create([
                    'siswa_id' => $siswa->id,
                    'biaya_spp_id' => $biayaSpp->id,
                    'bulan' => $bulan,
                    'tahun' => $currentYear,
                    'jumlah_tagihan' => $biayaSpp->jumlah,
                    'denda' => 0,
                    'status' => 'belum_bayar',
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'keterangan' => 'Tagihan otomatis'
                ]);

                $created++;
            }

            $message = "Berhasil generate tagihan: {$created} tagihan baru dibuat";
            if ($updated > 0) {
                $message .= ", {$updated} tagihan sudah ada";
            }

            session()->flash('success', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Computed properties untuk statistik
    public function getTotalTagihanProperty()
    {
        return TagihanSppModel::count();
    }

    public function getTotalLunasProperty()
    {
        return TagihanSppModel::where('status', 'lunas')->count();
    }

    public function getTotalBelumBayarProperty()
    {
        return TagihanSppModel::where('status', 'belum_bayar')->count();
    }

    public function getTotalTertunggakProperty()
    {
        return TagihanSppModel::where('status', 'tertunggak')->count();
    }

    public function getTotalPendapatanProperty()
    {
        return TagihanSppModel::where('status', 'lunas')->sum('jumlah_tagihan');
    }

    public function render()
    {
        $query = TagihanSppModel::with(['siswa.kelas', 'biayaSpp.kategoriBiaya'])
            ->when($this->search, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->bulanFilter, function ($query) {
                $query->where('bulan', $this->bulanFilter);
            })
            ->when($this->tahunFilter, function ($query) {
                $query->where('tahun', $this->tahunFilter);
            })
            ->orderBy('tahun', 'desc')
            ->orderByRaw("FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->orderBy('siswa_id');

        $tagihanList = $query->paginate($this->perPage);

        return view('livewire.admin.tagihan-spp', [
            'tagihanList' => $tagihanList,
            'siswas' => Siswa::where('status', 'aktif')->orderBy('nama')->get(),
            'biayaSppList' => BiayaSpp::with('kategoriBiaya')->where('status', 1)->orderBy('created_at', 'desc')->get(),
            'totalTagihan' => $this->totalTagihan,
            'totalLunas' => $this->totalLunas,
            'totalBelumBayar' => $this->totalBelumBayar,
            'totalTertunggak' => $this->totalTertunggak,
            'totalPendapatan' => $this->totalPendapatan,
        ]);
    }
}