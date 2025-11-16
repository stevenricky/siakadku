<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.app-new')]
class AgendaSekolah extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $agendaId;
    public $judul_agenda;
    public $deskripsi;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $waktu_mulai;
    public $waktu_selesai;
    public $tempat;
    public $penanggung_jawab;
    public $sasaran;
    public $jenis_agenda;
    public $dokumentasi;
    public $status = 'terjadwal';
    public $showModal = false;
    public $modalTitle = 'Tambah Agenda';
    public $showDetailModal = false;
    public $selectedAgenda = null;

    // Options
    public $sasaranOptions = [
        'seluruh_sekolah' => 'Seluruh Sekolah',
        'guru' => 'Guru',
        'siswa' => 'Siswa',
        'kelas_x' => 'Kelas X',
        'kelas_xi' => 'Kelas XI',
        'kelas_xii' => 'Kelas XII'
    ];

    public $jenisAgendaOptions = [
        'akademik' => 'Akademik',
        'non_akademik' => 'Non-Akademik',
        'sosial' => 'Sosial',
        'lainnya' => 'Lainnya'
    ];

    public $statusOptions = [
        'terjadwal' => 'Terjadwal',
        'berlangsung' => 'Berlangsung',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan'
    ];

    protected function rules()
    {
        return [
            'judul_agenda' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'nullable',
            'waktu_selesai' => 'nullable',
            'tempat' => 'required|string|max:100',
            'penanggung_jawab' => 'required|string|max:100',
            'sasaran' => 'required|string',
            'jenis_agenda' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string'
        ];
    }

    public function mount()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->tanggal_mulai = now()->format('Y-m-d');
        $this->tanggal_selesai = now()->format('Y-m-d');
        $this->waktu_mulai = '08:00';
        $this->waktu_selesai = '12:00';
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $agenda = \App\Models\AgendaSekolah::when($this->search, function ($query) {
                $query->where('judul_agenda', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                      ->orWhere('tempat', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        // Hitung statistik
        $terjadwal = \App\Models\AgendaSekolah::where('status', 'terjadwal')->count();
        $berlangsung = \App\Models\AgendaSekolah::where('status', 'berlangsung')->count();
        $selesai = \App\Models\AgendaSekolah::where('status', 'selesai')->count();
        $dibatalkan = \App\Models\AgendaSekolah::where('status', 'dibatalkan')->count();

        return view('livewire.admin.agenda-sekolah', [
            'agenda' => $agenda,
            'terjadwal' => $terjadwal,
            'berlangsung' => $berlangsung,
            'selesai' => $selesai,
            'dibatalkan' => $dibatalkan
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Agenda';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $agenda = \App\Models\AgendaSekolah::findOrFail($id);
        $this->agendaId = $id;
        $this->judul_agenda = $agenda->judul_agenda;
        $this->deskripsi = $agenda->deskripsi;
        $this->tanggal_mulai = $agenda->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $agenda->tanggal_selesai->format('Y-m-d');
        $this->waktu_mulai = $agenda->waktu_mulai;
        $this->waktu_selesai = $agenda->waktu_selesai;
        $this->tempat = $agenda->tempat;
        $this->penanggung_jawab = $agenda->penanggung_jawab;
        $this->sasaran = $agenda->sasaran;
        $this->jenis_agenda = $agenda->jenis_agenda;
        $this->status = $agenda->status;
        $this->modalTitle = 'Edit Agenda';
        $this->showModal = true;
    }

    public function showDetail($id)
    {
        $this->selectedAgenda = \App\Models\AgendaSekolah::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'judul_agenda' => $this->judul_agenda,
            'deskripsi' => $this->deskripsi,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'tempat' => $this->tempat,
            'penanggung_jawab' => $this->penanggung_jawab,
            'sasaran' => $this->sasaran,
            'jenis_agenda' => $this->jenis_agenda,
            'status' => $this->status
        ];

        // Handle file upload
        if ($this->dokumentasi) {
            $data['dokumentasi'] = $this->dokumentasi->store('agenda', 'public');
        }

        if ($this->agendaId) {
            $agenda = \App\Models\AgendaSekolah::findOrFail($this->agendaId);
            $agenda->update($data);
            session()->flash('success', 'Agenda berhasil diupdate.');
        } else {
            \App\Models\AgendaSekolah::create($data);
            session()->flash('success', 'Agenda berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function updateStatus($id, $status)
    {
        $agenda = \App\Models\AgendaSekolah::findOrFail($id);
        $agenda->update(['status' => $status]);
        
        session()->flash('success', 'Status agenda berhasil diupdate.');
    }

    public function delete($id)
    {
        $agenda = \App\Models\AgendaSekolah::findOrFail($id);
        $agenda->delete();
        
        session()->flash('success', 'Agenda berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'agendaId',
            'judul_agenda',
            'deskripsi',
            'tanggal_mulai',
            'tanggal_selesai',
            'waktu_mulai',
            'waktu_selesai',
            'tempat',
            'penanggung_jawab',
            'sasaran',
            'jenis_agenda',
            'dokumentasi',
            'status'
        ]);
        $this->resetErrorBag();
        $this->tanggal_mulai = now()->format('Y-m-d');
        $this->tanggal_selesai = now()->format('Y-m-d');
        $this->waktu_mulai = '08:00';
        $this->waktu_selesai = '12:00';
        $this->status = 'terjadwal';
    }
}