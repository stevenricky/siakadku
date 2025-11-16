<?php
// app/Events/PembayaranDiupdate.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PembayaranDiupdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $siswaId;
    public $tagihanId;
    public $status;

    /**
     * Create a new event instance.
     */
    public function __construct($siswaId, $tagihanId = null, $status = null)
    {
        $this->siswaId = $siswaId;
        $this->tagihanId = $tagihanId;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('siswa.' . $this->siswaId),
            new Channel('pembayaran-updates'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'pembayaran.diupdate';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'siswa_id' => $this->siswaId,
            'tagihan_id' => $this->tagihanId,
            'status' => $this->status,
            'message' => 'Status pembayaran telah diperbarui',
            'timestamp' => now()->toISOString(),
        ];
    }
}