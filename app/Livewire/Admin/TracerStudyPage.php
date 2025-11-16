<?php

namespace App\Livewire\Admin;

use App\Models\TracerStudy;
use App\Models\Alumni;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class TracerStudyPage extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $tracerId;
    public $alumni_id;
    public $tahun_survey;
    public $status_pekerjaan = 'bekerja';
    public $nama_perusahaan;
    public $bidang_pekerjaan;
    public $jabatan;
    public $gaji;
    public $nama_kampus;
    public $jurusan_kuliah;
    public $tahun_masuk_kuliah;
    public $relevansi_pendidikan;
    public $saran_untuk_sekolah;
    public $status_survey = 'terkirim';
    public $showModal = false;
    public $modalTitle = 'Tambah Survey Tracer Study';
    public $showDetailModal = false;
    public $selectedTracer = null;

    // Options
    public $statusPekerjaanOptions = [
        'bekerja' => 'Bekerja',
        'wirausaha' => 'Wirausaha',
        'melanjutkan' => 'Melanjutkan Kuliah',
        'belum_bekerja' => 'Belum Bekerja'
    ];

    public $relevansiOptions = [
        'sangat_relevan' => 'Sangat Relevan',
        'cukup_relevan' => 'Cukup Relevan',
        'kurang_relevan' => 'Kurang Relevan',
        'tidak_relevan' => 'Tidak Relevan'
    ];

    public $statusSurveyOptions = [
        'terkirim' => 'Terkirim',
        'dijawab' => 'Dijawab'
    ];

    protected function rules()
    {
        return [
            'alumni_id' => 'required|exists:alumni,id',
            'tahun_survey' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'status_pekerjaan' => 'required|in:bekerja,wirausaha,melanjutkan,belum_bekerja',
            'nama_perusahaan' => 'nullable|string|max:200',
            'bidang_pekerjaan' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:100',
            'gaji' => 'nullable|numeric|min:0',
            'nama_kampus' => 'nullable|string|max:200',
            'jurusan_kuliah' => 'nullable|string|max:100',
            'tahun_masuk_kuliah' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'relevansi_pendidikan' => 'nullable|string',
            'saran_untuk_sekolah' => 'nullable|string',
            'status_survey' => 'required|in:terkirim,dijawab'
        ];
    }

    public function mount()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->tahun_survey = date('Y');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function updatedStatusPekerjaan($value)
    {
        // Reset fields when status changes
        if ($value === 'melanjutkan') {
            $this->nama_perusahaan = null;
            $this->bidang_pekerjaan = null;
            $this->jabatan = null;
            $this->gaji = null;
        } elseif ($value === 'bekerja' || $value === 'wirausaha') {
            $this->nama_kampus = null;
            $this->jurusan_kuliah = null;
            $this->tahun_masuk_kuliah = null;
        }
    }

    public function render()
    {
        $tracerStudy = TracerStudy::with(['alumni.siswa'])
            ->when($this->search, function ($query) {
                $query->whereHas('alumni.siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhere('nama_perusahaan', 'like', '%' . $this->search . '%')
                ->orWhere('nama_kampus', 'like', '%' . $this->search . '%');
            })
            ->latest('tahun_survey')
            ->paginate($this->perPage);

        // Hitung statistik
        $surveyTerkirim = TracerStudy::where('status_survey', 'terkirim')->count();
        $surveyDijawab = TracerStudy::where('status_survey', 'dijawab')->count();
        $responseRate = $surveyDijawab > 0 ? round(($surveyDijawab / ($surveyTerkirim + $surveyDijawab)) * 100) : 0;

        // Statistik status alumni
        $bekerja = TracerStudy::where('status_pekerjaan', 'bekerja')->count();
        $kuliah = TracerStudy::where('status_pekerjaan', 'melanjutkan')->count();
        $wirausaha = TracerStudy::where('status_pekerjaan', 'wirausaha')->count();
        $belumBekerja = TracerStudy::where('status_pekerjaan', 'belum_bekerja')->count();

        $alumni = Alumni::with('siswa')->get();

        return view('livewire.admin.tracer-study', [
            'tracerStudy' => $tracerStudy,
            'surveyTerkirim' => $surveyTerkirim,
            'surveyDijawab' => $surveyDijawab,
            'responseRate' => $responseRate,
            'bekerja' => $bekerja,
            'kuliah' => $kuliah,
            'wirausaha' => $wirausaha,
            'belumBekerja' => $belumBekerja,
            'alumni' => $alumni
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Survey Tracer Study';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $tracer = TracerStudy::findOrFail($id);
        $this->tracerId = $id;
        $this->alumni_id = $tracer->alumni_id;
        $this->tahun_survey = $tracer->tahun_survey;
        $this->status_pekerjaan = $tracer->status_pekerjaan;
        $this->nama_perusahaan = $tracer->nama_perusahaan;
        $this->bidang_pekerjaan = $tracer->bidang_pekerjaan;
        $this->jabatan = $tracer->jabatan;
        $this->gaji = $tracer->gaji;
        $this->nama_kampus = $tracer->nama_kampus;
        $this->jurusan_kuliah = $tracer->jurusan_kuliah;
        $this->tahun_masuk_kuliah = $tracer->tahun_masuk_kuliah;
        $this->relevansi_pendidikan = $tracer->relevansi_pendidikan;
        $this->saran_untuk_sekolah = $tracer->saran_untuk_sekolah;
        $this->status_survey = $tracer->status_survey;
        $this->modalTitle = 'Edit Survey Tracer Study';
        $this->showModal = true;
    }

    public function showDetail($id)
    {
        $this->selectedTracer = TracerStudy::with(['alumni.siswa'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'alumni_id' => $this->alumni_id,
            'tahun_survey' => $this->tahun_survey,
            'status_pekerjaan' => $this->status_pekerjaan,
            'nama_perusahaan' => $this->nama_perusahaan,
            'bidang_pekerjaan' => $this->bidang_pekerjaan,
            'jabatan' => $this->jabatan,
            'gaji' => $this->gaji,
            'nama_kampus' => $this->nama_kampus,
            'jurusan_kuliah' => $this->jurusan_kuliah,
            'tahun_masuk_kuliah' => $this->tahun_masuk_kuliah,
            'relevansi_pendidikan' => $this->relevansi_pendidikan,
            'saran_untuk_sekolah' => $this->saran_untuk_sekolah,
            'status_survey' => $this->status_survey
        ];

        if ($this->tracerId) {
            $tracer = TracerStudy::findOrFail($this->tracerId);
            $tracer->update($data);
            session()->flash('success', 'Survey tracer study berhasil diupdate.');
        } else {
            TracerStudy::create($data);
            session()->flash('success', 'Survey tracer study berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function updateStatus($id, $status)
    {
        $tracer = TracerStudy::findOrFail($id);
        $tracer->update(['status_survey' => $status]);
        
        session()->flash('success', 'Status survey berhasil diupdate.');
    }

    public function delete($id)
    {
        $tracer = TracerStudy::findOrFail($id);
        $tracer->delete();
        
        session()->flash('success', 'Survey tracer study berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'tracerId',
            'alumni_id',
            'tahun_survey',
            'status_pekerjaan',
            'nama_perusahaan',
            'bidang_pekerjaan',
            'jabatan',
            'gaji',
            'nama_kampus',
            'jurusan_kuliah',
            'tahun_masuk_kuliah',
            'relevansi_pendidikan',
            'saran_untuk_sekolah',
            'status_survey'
        ]);
        $this->resetErrorBag();
        $this->tahun_survey = date('Y');
        $this->status_pekerjaan = 'bekerja';
        $this->status_survey = 'terkirim';
    }
}