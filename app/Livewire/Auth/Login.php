<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        // Coba login dengan credential biasa
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return $this->redirectIntended('/dashboard', navigate: true);
        }

        // Jika gagal, coba cari siswa dengan NIS sebagai password
        $siswa = \App\Models\Siswa::whereHas('user', function($query) {
            $query->where('email', $this->email);
        })->first();

        if ($siswa && $this->password === $siswa->nis) {
            // Login dengan user siswa
            Auth::login($siswa->user, $this->remember);
            return $this->redirectIntended('/dashboard', navigate: true);
        }

        // Jika masih gagal, beri pesan error
        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}