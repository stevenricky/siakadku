<?php
// app/Events/NilaiDitambahkan.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NilaiDitambahkan implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $siswaId;

    public function __construct($siswaId)
    {
        $this->siswaId = $siswaId;
    }

    public function broadcastOn()
    {
        return new Channel('siswa.' . $this->siswaId);
    }

    public function broadcastAs()
    {
        return 'nilai.ditambahkan';
    }
}