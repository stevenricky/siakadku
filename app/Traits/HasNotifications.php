<?php
// app/Traits/HasNotifications.php

namespace App\Traits;

trait HasNotifications
{
    /**
     * Trigger notification ketika model created
     */
    protected static function bootHasNotifications()
    {
        static::created(function ($model) {
            $model->triggerNotification();
        });
    }

    /**
     * Trigger notification based on model type
     */
    public function triggerNotification()
    {
        $service = \App\Services\NotificationService::class;
        
        switch (get_class($this)) {
            case \App\Models\AgendaSekolah::class:
                $service::agendaCreated($this);
                break;
                
            case \App\Models\Pengumuman::class:
                $service::pengumumanCreated($this);
                break;
                
            case \App\Models\Nilai::class:
                $service::nilaiDitambahkan($this);
                break;
                
            case \App\Models\Tugas::class:
                $service::tugasDitambahkan($this);
                break;
                
            case \App\Models\KegiatanEkskul::class:
                $service::kegiatanEkskulCreated($this);
                break;
        }
    }
}