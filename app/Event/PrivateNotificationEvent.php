<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $message;
    public $type;
    public $data;
    public $target; // Bisa role atau user_id
    public $action_url;

    public function __construct($title, $message, $type, $data = [], $target, $actionUrl = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
        $this->target = $target;
        $this->action_url = $actionUrl;
    }

    public function broadcastOn()
    {
        // Jika target adalah role
        if (in_array($this->target, ['admin', 'guru', 'siswa'])) {
            return new Channel("notifications.{$this->target}");
        }
        
        // Jika target adalah user_id spesifik
        return new Channel("notifications.user.{$this->target}");
    }

    public function broadcastAs()
    {
        return 'private.notification';
    }

    public function broadcastWith()
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'data' => $this->data,
            'action_url' => $this->action_url,
            'is_public' => false,
            'timestamp' => now()->toISOString()
        ];
    }
}