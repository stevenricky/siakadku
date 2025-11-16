<?php
// app/Http/Controllers/Admin/AgendaController.php

public function store(StoreAgendaRequest $request)
{
    $agenda = Agenda::create($request->validated(a));
    
    // Trigger notification
    $count = \App\Services\NotificationService::agendaCreated($agenda);
    
    return redirect()->route('admin.agenda.index')
        ->with('success', "Agenda berhasil dibuat! Notifikasi dikirim ke {$count} pengguna");
}