<?php
// app/Events/NilaiDiupdate.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NilaiDiupdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $siswaId;
    public $mapelId;
    public $nilaiAkhir;

    /**
     * Create a new event instance.
     */
    public function __construct($siswaId, $mapelId = null, $nilaiAkhir = null)
    {
        $this->siswaId = $siswaId;
        $this->mapelId = $mapelId;
        $this->nilaiAkhir = $nilaiAkhir;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('siswa.' . $this->siswaId),
            new Channel('dashboard-updates'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'nilai.diupdate';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'siswa_id' => $this->siswaId,
            'mapel_id' => $this->mapelId,
            'nilai_akhir' => $this->nilaiAkhir,
            'message' => 'Nilai telah diperbarui',
            'timestamp' => now()->toISOString(),
        ];
    }
}