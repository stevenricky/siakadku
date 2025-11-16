<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $message;
    public $type;
    public $data;
    public $roles;
    public $action_url;

    public function __construct($title, $message, $type, $data = [], $roles = ['admin', 'guru', 'siswa'], $actionUrl = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
        $this->roles = $roles;
        $this->action_url = $actionUrl;
    }

    public function broadcastOn()
    {
        return new Channel('public-notifications');
    }

    public function broadcastAs()
    {
        return 'public.notification';
    }

    public function broadcastWith()
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'data' => $this->data,
            'action_url' => $this->action_url,
            'timestamp' => now()->toISOString()
        ];
    }
}