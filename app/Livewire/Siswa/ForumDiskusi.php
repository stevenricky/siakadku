<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Forum;
use App\Models\KomentarForum;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class ForumDiskusi extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $filterKategori = '';
        public $filterSort = 'terbaru';
    public $judul = '';
    public $isi = '';
    public $kategori = 'umum';
    public $showModal = false;
    public $selectedForum = null;
    public $showDetailModal = false;
    public $komentarText = '';

    public function render()
    {
        $forumList = Forum::with(['user', 'komentar.user', 'likes'])
            ->when($this->search, function($query) {
                $query->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhere('isi', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterKategori, function($query) {
                $query->where('kategori', $this->filterKategori);
            })
            ->when($this->filterSort, function($query) {
                match($this->filterSort) {
                    'terbaru' => $query->orderBy('created_at', 'desc'),
                    'populer' => $query->orderBy('view_count', 'desc'),
                    'trending' => $query->orderBy('like_count', 'desc'),
                    'paling_dikomentari' => $query->withCount('komentar')->orderBy('komentar_count', 'desc'),
                    default => $query->orderBy('created_at', 'desc')
                };
            })
            ->orderBy('is_pinned', 'desc') // Selalu prioritaskan yang disematkan
            ->paginate($this->perPage);

        return view('livewire.siswa.forum-diskusi', [
            'forumList' => $forumList
        ]);
    }

    public function buatDiskusi()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string|min:10',
            'kategori' => 'required|in:pelajaran,umum,ekstrakurikuler',
        ]);

        Forum::create([
            'user_id' => auth()->id(),
            'judul' => $this->judul,
            'isi' => $this->isi,
            'kategori' => $this->kategori,
        ]);

        session()->flash('success', 'Diskusi berhasil dibuat.');
        $this->reset(['judul', 'isi', 'kategori', 'showModal']);
        $this->resetPage();
    }

    public function bukaDetail($forumId)
    {
        $this->selectedForum = Forum::with(['user', 'komentar.user', 'likes'])->find($forumId);
        
        // Tambah view count
        if ($this->selectedForum) {
            $this->selectedForum->tambahView();
        }
        
        $this->showDetailModal = true;
        $this->komentarText = ''; // Reset komentar text
    }

    public function tambahKomentar()
{
    // Validasi manual tanpa trim di awal
    if (empty($this->komentarText)) {
        session()->flash('error', 'Komentar tidak boleh kosong.');
        return;
    }

    // Trim setelah validasi empty
    $komentarText = trim($this->komentarText);
    
    if (empty($komentarText)) {
        session()->flash('error', 'Komentar tidak boleh hanya berisi spasi.');
        return;
    }

    if (!$this->selectedForum) {
        session()->flash('error', 'Forum tidak ditemukan.');
        return;
    }

    try {
        KomentarForum::create([
            'forum_id' => $this->selectedForum->id,
            'user_id' => auth()->id(),
            'komentar' => $komentarText,
        ]);

        $this->komentarText = '';
        session()->flash('success', 'Komentar berhasil ditambahkan.');
        
        // Refresh selected forum data
        $this->selectedForum->refresh();
        
    } catch (\Exception $e) {
        session()->flash('error', 'Gagal menambahkan komentar: ' . $e->getMessage());
    }
}

// Method untuk handle enter key
public function kirimKomentarEnter()
{
    $this->tambahKomentar();
}

    public function likeDiskusi($forumId)
    {
        $forum = Forum::with('likes')->find($forumId);
        if ($forum) {
            $result = $forum->toggleLike();
            
            if ($result === 'liked') {
                session()->flash('info', 'Anda menyukai diskusi ini!');
            } else {
                session()->flash('info', 'Anda tidak lagi menyukai diskusi ini.');
            }
            
            // Jika forum yang di-like sedang dilihat di detail, refresh data
            if ($this->selectedForum && $this->selectedForum->id == $forumId) {
                $this->selectedForum->refresh();
            }
        }
    }

     public function resetFilters()
    {
        $this->search = '';
        $this->filterKategori = '';
        $this->filterSort = 'terbaru';
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->selectedForum = null;
        $this->komentarText = '';
    }

    // Method untuk clear flash messages
    public function clearFlash()
    {
        session()->forget(['success', 'error', 'info']);
    }
}