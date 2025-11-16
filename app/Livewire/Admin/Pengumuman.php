<?php

namespace App\Livewire\Admin;

use App\Models\Pengumuman as PengumumanModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class Pengumuman extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pengumumanId;
    public $judul;
    public $isi;
    public $target = 'semua';
    public $is_urgent = false;
    public $tanggal_berlaku;
    public $showModal = false;
    public $modalTitle = 'Tambah Pengumuman';

    protected $rules = [
        'judul' => 'required|string|max:255',
        'isi' => 'required|string',
        'target' => 'required|in:semua,admin,guru,siswa',
        'is_urgent' => 'boolean',
        'tanggal_berlaku' => 'nullable|date|after_or_equal:today'
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->showModal = false;
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $pengumumen = PengumumanModel::with('user')
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('isi', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.pengumuman', [
            'pengumumen' => $pengumumen
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Pengumuman';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $pengumuman = PengumumanModel::findOrFail($id);
        $this->pengumumanId = $id;
        $this->judul = $pengumuman->judul;
        $this->isi = $pengumuman->isi;
        $this->target = $pengumuman->target;
        $this->is_urgent = $pengumuman->is_urgent;
        $this->tanggal_berlaku = $pengumuman->tanggal_berlaku?->format('Y-m-d');
        $this->modalTitle = 'Edit Pengumuman';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'judul' => $this->judul,
            'isi' => $this->isi,
            'target' => $this->target,
            'is_urgent' => $this->is_urgent,
            'tanggal_berlaku' => $this->tanggal_berlaku,
            'user_id' => Auth::id()
        ];

        if ($this->pengumumanId) {
            $pengumuman = PengumumanModel::findOrFail($this->pengumumanId);
            $pengumuman->update($data);
            session()->flash('success', 'Pengumuman berhasil diupdate.');
        } else {
            PengumumanModel::create($data);
            session()->flash('success', 'Pengumuman berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $pengumuman = PengumumanModel::findOrFail($id);
        $pengumuman->delete();
        session()->flash('success', 'Pengumuman berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'pengumumanId',
            'judul',
            'isi',
            'target',
            'is_urgent',
            'tanggal_berlaku'
        ]);
        $this->resetErrorBag();
    }
}