<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\BarangInventaris;
use App\Models\KategoriInventaris;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app-new')]
class ManajemenInventaris extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $kategoriFilter = '';
    public $ruanganFilter = '';
    public $kondisiFilter = '';

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $barangId = null;
    public $selectedBarang = null; // Tambahkan ini
    
    public $kode_barang;
    public $nama_barang;
    public $kategori_id;
    public $ruangan_id;
    public $merk;
    public $tipe;
    public $jumlah = 1;
    public $satuan = 'unit';
    public $harga;
    public $tanggal_pembelian;
    public $sumber_dana;
    public $spesifikasi;
    public $kondisi = 'baik';
    public $keterangan;
    public $foto;

    public $kategoriList = [];
    public $ruanganList = [];
    public $kondisiList = ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'];
    public $satuanList = ['unit', 'buah', 'set', 'paket', 'lembar', 'roll', 'botol', 'dus'];

    protected $queryString = ['search', 'perPage', 'kategoriFilter', 'ruanganFilter', 'kondisiFilter'];

    protected function rules()
    {
        $currentYear = date('Y');
        
        return [
            'kode_barang' => 'required|string|max:100|unique:barang_inventaris,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_inventaris,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'merk' => 'nullable|string|max:100',
            'tipe' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'harga' => 'nullable|numeric|min:0',
            'tanggal_pembelian' => 'nullable|date',
            'sumber_dana' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // 2MB max
        ];
    }

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->loadKategoriAndRuangan();
    }

    public function loadKategoriAndRuangan()
    {
        $this->kategoriList = KategoriInventaris::where('status', true)->get();
        $this->ruanganList = Ruangan::all();
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function generateKodeBarang()
    {
        $lastItem = BarangInventaris::latest()->first();
        $number = $lastItem ? (int) substr($lastItem->kode_barang, -3) + 1 : 1;
        $this->kode_barang = 'INV-' . date('Y') . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->generateKodeBarang();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $barang = BarangInventaris::findOrFail($id);
        
        // Simpan data barang yang sedang diedit
        $this->selectedBarang = $barang;
        
        $this->barangId = $barang->id;
        $this->kode_barang = $barang->kode_barang;
        $this->nama_barang = $barang->nama_barang;
        $this->kategori_id = $barang->kategori_id;
        $this->ruangan_id = $barang->ruangan_id;
        $this->merk = $barang->merk;
        $this->tipe = $barang->tipe;
        $this->jumlah = $barang->jumlah;
        $this->satuan = $barang->satuan;
        $this->harga = $barang->harga;
        $this->tanggal_pembelian = $barang->tanggal_pembelian?->format('Y-m-d');
        $this->sumber_dana = $barang->sumber_dana;
        $this->spesifikasi = $barang->spesifikasi;
        $this->kondisi = $barang->kondisi;
        $this->keterangan = $barang->keterangan;
        // Jangan reset foto biar foto lama tetap ada
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'barangId', 'kode_barang', 'nama_barang', 'kategori_id', 'ruangan_id', 
            'merk', 'tipe', 'jumlah', 'satuan', 'harga', 'tanggal_pembelian',
            'sumber_dana', 'spesifikasi', 'kondisi', 'keterangan', 'foto', 'selectedBarang'
        ]);
        $this->resetErrorBag();
        $this->jumlah = 1;
        $this->satuan = 'unit';
        $this->kondisi = 'baik';
    }

    public function saveBarang()
    {
        // Update rules untuk edit
        $rules = $this->rules();
        if ($this->formType === 'edit') {
            $rules['kode_barang'] = 'required|string|max:100|unique:barang_inventaris,kode_barang,' . $this->barangId;
            $rules['foto'] = 'nullable|image|max:2048';
        }

        $validatedData = $this->validate($rules);

        try {
            if ($this->formType === 'create') {
                // Handle file upload
                if ($this->foto) {
                    $validatedData['foto'] = $this->foto->store('inventaris', 'public');
                }
                
                BarangInventaris::create($validatedData);
                session()->flash('success', 'Data barang inventaris berhasil ditambahkan.');
            } else {
                $barang = BarangInventaris::find($this->barangId);
                
                // Handle file upload
                if ($this->foto) {
                    // Delete old photo if exists
                    if ($barang->foto) {
                        Storage::disk('public')->delete($barang->foto);
                    }
                    
                    $validatedData['foto'] = $this->foto->store('inventaris', 'public');
                }
                
                $barang->update($validatedData);
                session()->flash('success', 'Data barang inventaris berhasil diperbarui.');
            }

            $this->showForm = false;
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteBarang($id)
    {
        try {
            $barang = BarangInventaris::findOrFail($id);
            
            // Delete photo if exists
            if ($barang->foto) {
                Storage::disk('public')->delete($barang->foto);
            }
            
            $barang->delete();
            session()->flash('success', 'Data barang inventaris berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Tambahkan method untuk menghapus foto
    public function removePhoto()
    {
        if ($this->selectedBarang && $this->selectedBarang->foto) {
            // Hapus file dari storage
            Storage::disk('public')->delete($this->selectedBarang->foto);
            
            // Update database
            $this->selectedBarang->update(['foto' => null]);
            
            // Refresh selectedBarang
            $this->selectedBarang->refresh();
            
            session()->flash('success', 'Foto berhasil dihapus');
        }
    }

    public function render()
{
    $query = BarangInventaris::with(['kategori', 'ruangan'])
        ->when($this->search, function ($query) {
            $query->where('kode_barang', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_barang', 'like', '%' . $this->search . '%')
                  ->orWhere('merk', 'like', '%' . $this->search . '%')
                  ->orWhere('tipe', 'like', '%' . $this->search . '%');
        })
        ->when($this->kategoriFilter, function ($query) {
            $query->where('kategori_id', $this->kategoriFilter);
        })
        ->when($this->ruanganFilter, function ($query) {
            $query->where('ruangan_id', $this->ruanganFilter);
        })
        ->when($this->kondisiFilter, function ($query) {
            $query->where('kondisi', $this->kondisiFilter);
        })
        ->orderBy('kode_barang');

    // Data untuk mobile dengan pagination
    $barang = $query->paginate($this->perPage);
    
    // Data untuk desktop tanpa pagination
    $allBarang = $query->get();

    // Hitung statistik
    $totalBarang = BarangInventaris::count();
    $totalNilai = BarangInventaris::sum('harga');
    $baikCount = BarangInventaris::where('kondisi', 'baik')->count();
    $rusakRinganCount = BarangInventaris::where('kondisi', 'rusak_ringan')->count();
    $rusakBeratCount = BarangInventaris::where('kondisi', 'rusak_berat')->count();
    $hilangCount = BarangInventaris::where('kondisi', 'hilang')->count();

    return view('livewire.admin.manajemen-inventaris', [
        'barang' => $barang,
        'allBarang' => $allBarang, // Tambahkan ini
        'totalBarang' => $totalBarang,
        'totalNilai' => $totalNilai,
        'baikCount' => $baikCount,
        'rusakRinganCount' => $rusakRinganCount,
        'rusakBeratCount' => $rusakBeratCount,
        'hilangCount' => $hilangCount,
    ]);
}
}