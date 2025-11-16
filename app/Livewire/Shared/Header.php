<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TestNotification;

class Header extends Component
{
    public $unreadNotifications = 0;
    public $darkMode = false;
    public $notifications = [];

    protected $listeners = [
        'refreshNotifications' => 'getUnreadNotificationsCount',
        'notificationRead' => 'getUnreadNotificationsCount'
    ];

    public function mount()
    {
        $this->getUnreadNotificationsCount();
        $this->loadNotifications();
        $this->checkDarkMode();
        
        // Uncomment baris berikut untuk membuat notifikasi contoh (testing only)
        // $this->createSampleNotifications();
    }

    public function getUnreadNotificationsCount()
    {
        if (Auth::check()) {
            $this->unreadNotifications = Auth::user()->unreadNotifications()->count();
        } else {
            $this->unreadNotifications = 0;
        }
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $this->notifications = Auth::user()->notifications()
                ->take(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'message' => $notification->data['message'] ?? 'Notifikasi',
                        'time' => $notification->created_at->diffForHumans(),
                        'read' => $notification->read_at !== null,
                        'type' => $notification->data['type'] ?? 'info'
                    ];
                })
                ->toArray();
        } else {
            $this->notifications = [];
        }
    }

    public function checkDarkMode()
    {
        // Check dari localStorage terlebih dahulu, lalu session
        if (isset($_COOKIE['darkMode'])) {
            $this->darkMode = $_COOKIE['darkMode'] === 'true';
        } else {
            $this->darkMode = session('darkMode', false);
        }
    }

public function toggleDarkMode()
{
    $this->darkMode = !$this->darkMode;
    
    // Simpan di session
    session(['darkMode' => $this->darkMode]);
    
    // Simpan di localStorage via JavaScript
    $this->dispatch('themeToggled', darkMode: $this->darkMode);
}

    public function markAsRead($notificationId)
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->markAsRead();
                $this->getUnreadNotificationsCount();
                $this->loadNotifications();
                $this->dispatch('notificationRead');
            }
        }
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->getUnreadNotificationsCount();
            $this->loadNotifications();
            $this->dispatch('notificationRead');
        }
    }

    /**
     * Method untuk membuat notifikasi contoh (testing only)
     */
    public function createSampleNotifications()
    {
        if (Auth::check() && Auth::user()->notifications()->count() === 0) {
            $user = Auth::user();
            
            // Buat beberapa notifikasi contoh
            $user->notify(new TestNotification('Pembaruan nilai matematika telah tersedia', 'success'));
            $user->notify(new TestNotification('Jadwal ulangan harian minggu depan', 'info'));
            $user->notify(new TestNotification('Rapat guru bulan depan', 'warning'));
            
            $this->getUnreadNotificationsCount();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.shared.header');
    }
}