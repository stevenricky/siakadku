<?php  
// app/Http/Controllers/Admin/PengumumanController.php

public function store(StorePengumumanRequest $request)
{
    $pengumuman = Pengumuman::create($request->validated());
    
    $count = \App\Services\NotificationService::pengumumanCreated($pengumuman);
    
    return redirect()->route('admin.pengumuman.index')
        ->with('success', "Pengumuman berhasil dibuat! Notifikasi dikirim ke {$count} pengguna");
}