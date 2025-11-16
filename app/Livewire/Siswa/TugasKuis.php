<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\Tugas;
use App\Models\TugasSiswa;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout; 

#[Layout(name: 'layouts.app-new')]

class TugasKuis extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $filterStatus = 'semua'; // semua, belum, selesai, terlambat
    
    // Properties untuk modal
    public $showModal = false;
    public $selectedTugas = null;
    public $jawaban = '';
    public $fileJawaban;
    public $isSubmitting = false;

    public function render()
    {
        $siswa = auth()->user()->siswa;
        
        // Query dasar untuk tugas
        $tugasList = Tugas::with(['mapel', 'guru'])
            ->whereHas('mapel.jadwal', function($query) use ($siswa) {
                $query->where('kelas_id', $siswa->kelas_id);
            })
            ->when($this->search, function($query) {
                $query->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhereHas('mapel', function($q) {
                          $q->where('nama_mapel', 'like', '%'.$this->search.'%');
                      });
            })
            ->when($this->filterStatus !== 'semua', function($query) use ($siswa) {
                if ($this->filterStatus === 'belum') {
                    $query->where(function($q) use ($siswa) {
                        $q->whereDoesntHave('tugasSiswa', function($subQuery) use ($siswa) {
                            $subQuery->where('siswa_id', $siswa->id)
                                  ->whereIn('status', ['dikumpulkan', 'dinilai']);
                        })->orWhereHas('tugasSiswa', function($subQuery) use ($siswa) {
                            $subQuery->where('siswa_id', $siswa->id)
                                  ->where('status', 'belum');
                        });
                    })->where('deadline', '>', now());
                } elseif ($this->filterStatus === 'selesai') {
                    $query->whereHas('tugasSiswa', function($q) use ($siswa) {
                        $q->where('siswa_id', $siswa->id)
                          ->whereIn('status', ['dikumpulkan', 'dinilai']);
                    });
                } elseif ($this->filterStatus === 'terlambat') {
                    $query->where(function($q) use ($siswa) {
                        $q->whereHas('tugasSiswa', function($subQuery) use ($siswa) {
                            $subQuery->where('siswa_id', $siswa->id)
                                  ->where('status', 'terlambat');
                        })->orWhere(function($subQuery) use ($siswa) {
                            $subQuery->where('deadline', '<', now())
                                  ->whereDoesntHave('tugasSiswa', function($q2) use ($siswa) {
                                      $q2->where('siswa_id', $siswa->id);
                                  });
                        });
                    });
                }
            })
            ->orderBy('deadline')
            ->paginate($this->perPage);

        return view('livewire.siswa.tugas-kuis', [
            'tugasList' => $tugasList
        ]);
    }

    public function bukaModal($tugasId)
    {
        $this->selectedTugas = Tugas::with(['mapel', 'guru'])->find($tugasId);
        $this->jawaban = '';
        $this->fileJawaban = null;
        
        // Load existing submission if any
        if ($this->selectedTugas) {
            $submission = $this->getPengumpulanSiswa($this->selectedTugas->id, auth()->user()->siswa->id);
            if ($submission) {
                $this->jawaban = $submission->jawaban;
            }
        }
        
        $this->showModal = true;
    }

    public function tutupModal()
    {
        $this->showModal = false;
        $this->selectedTugas = null;
        $this->jawaban = '';
        $this->fileJawaban = null;
        $this->isSubmitting = false;
    }

    public function kumpulkanTugas()
    {
        $this->validate([
            'jawaban' => 'required_without:fileJawaban|nullable|string|max:5000',
            'fileJawaban' => 'required_without:jawaban|nullable|file|max:10240', // 10MB max
        ]);

        $this->isSubmitting = true;

        try {
            $siswa = auth()->user()->siswa;
            $fileName = null;

            // Upload file jika ada
            if ($this->fileJawaban) {
                $fileName = time() . '_' . $this->fileJawaban->getClientOriginalName();
                $this->fileJawaban->storeAs('tugas_jawaban', $fileName, 'public');
            }

            // Cek apakah sudah ada submission
            $existingSubmission = $this->getPengumpulanSiswa($this->selectedTugas->id, $siswa->id);

            if ($existingSubmission) {
                // Update existing submission
                $existingSubmission->update([
                    'jawaban' => $this->jawaban,
                    'file_jawaban' => $fileName ?: $existingSubmission->file_jawaban,
                    'submitted_at' => now(),
                    'status' => now()->gt($this->selectedTugas->deadline) ? 'terlambat' : 'dikumpulkan'
                ]);
            } else {
                // Create new submission
                TugasSiswa::create([
                    'tugas_id' => $this->selectedTugas->id,
                    'siswa_id' => $siswa->id,
                    'jawaban' => $this->jawaban,
                    'file_jawaban' => $fileName,
                    'submitted_at' => now(),
                    'status' => now()->gt($this->selectedTugas->deadline) ? 'terlambat' : 'dikumpulkan'
                ]);
            }

            // Dispatch event untuk real-time update
            $this->dispatch('tugasDiupdate');

            $this->tutupModal();
            session()->flash('success', 'Tugas berhasil dikumpulkan!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->isSubmitting = false;
    }

    public function downloadMateri($tugasId)
    {
        $tugas = Tugas::findOrFail($tugasId);
        
        if ($tugas->file) {
            return Storage::disk('public')->download('tugas/' . $tugas->file);
        }
        
        session()->flash('error', 'File materi tidak ditemukan.');
    }

    // Helper method untuk mendapatkan pengumpulan siswa
    private function getPengumpulanSiswa($tugasId, $siswaId)
    {
        return TugasSiswa::where('tugas_id', $tugasId)
                        ->where('siswa_id', $siswaId)
                        ->first();
    }

    // Helper method untuk cek status pengumpulan
    private function getStatusPengumpulan($tugas, $siswaId)
    {
        $submission = $this->getPengumpulanSiswa($tugas->id, $siswaId);
        
        if (!$submission) {
            return now()->gt($tugas->deadline) ? 'expired' : 'belum';
        }
        
        return $submission->status;
    }

    // Real-time event listener
    #[On('tugasDiupdate')]
    public function refreshTugas()
    {
        // Component akan otomatis refresh ketika ada event
        $this->render();
    }

    #[On('tugasDitambahkan')]
    public function onTugasDitambahkan()
    {
        $this->render();
    }
}