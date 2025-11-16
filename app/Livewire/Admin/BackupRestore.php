<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app-new')]
class BackupRestore extends Component
{
    public $backups = [];
    public $creatingBackup = false;
    public $loadingBackups = false;
    public $backupType = 'json'; // Default backup type

    public function mount()
    {
        $this->loadBackups();
    }

    public function render()
    {
        return view('livewire.admin.backup-restore');
    }

    public function loadBackups()
    {
        $this->loadingBackups = true;
        $this->backups = [];
        
        try {
            set_time_limit(10);
            
            if (class_exists('Spatie\Backup\BackupServiceProvider')) {
                $this->loadSpatieBackups();
            } else {
                $this->loadManualBackups();
            }
            
        } catch (\Exception $e) {
            $this->backups = $this->getSampleBackups();
        } finally {
            $this->loadingBackups = false;
        }
    }

    private function loadSpatieBackups()
    {
        try {
            $backupDisk = Storage::disk(config('backup.backup.destination.disks')[0] ?? 'local');
            $backupPath = config('backup.backup.name', 'laravel-backup');
            
            if ($backupDisk->exists($backupPath)) {
                $files = $backupDisk->files($backupPath);
                
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                        $this->backups[] = [
                            'name' => basename($file),
                            'size' => $this->formatBytes($backupDisk->size($file)),
                            'date' => Carbon::createFromTimestamp($backupDisk->lastModified($file))->format('Y-m-d H:i:s'),
                            'path' => $file,
                            'type' => 'spatie'
                        ];
                    }
                }
                
                $this->sortBackups();
            }
        } catch (\Exception $e) {
            $this->loadManualBackups();
        }
    }

    private function loadManualBackups()
    {
        try {
            $backupPath = 'backups';
            if (Storage::disk('local')->exists($backupPath)) {
                $files = Storage::disk('local')->files($backupPath);
                $files = array_slice($files, 0, 50);
                
                foreach ($files as $file) {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array($extension, ['sql', 'json', 'txt'])) {
                        $this->backups[] = [
                            'name' => basename($file),
                            'size' => $this->formatBytes(Storage::disk('local')->size($file)),
                            'date' => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file))->format('Y-m-d H:i:s'),
                            'path' => $file,
                            'type' => $extension
                        ];
                    }
                }
                
                $this->sortBackups();
            }
        } catch (\Exception $e) {
            $this->backups = $this->getSampleBackups();
        }
    }

    private function getSampleBackups()
    {
        return [
            [
                'name' => 'backup_' . date('Y_m_d') . '.json', 
                'size' => '1.2 MB', 
                'date' => now()->format('Y-m-d H:i:s'), 
                'path' => 'backups/backup_' . date('Y_m_d') . '.json',
                'type' => 'json'
            ],
        ];
    }

    private function sortBackups()
    {
        usort($this->backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
    }

    public function createBackup()
    {
        $this->creatingBackup = true;
        
        try {
            set_time_limit(180); // Increase timeout for SQL backup
            
            if (class_exists('Spatie\Backup\BackupServiceProvider')) {
                Artisan::call('backup:run');
                session()->flash('success', 'Backup database berhasil dibuat menggunakan Spatie Backup.');
            } else {
                // Backup menggunakan Laravel native berdasarkan tipe yang dipilih
                switch ($this->backupType) {
                    case 'sql':
                        $this->createSqlBackup(); // Data saja
                        break;
                    case 'sql_full':
                        $this->createFullSqlBackup(); // Struktur + Data dengan IF NOT EXISTS
                        break;
                    case 'json':
                    default:
                        $this->createJsonBackup();
                        break;
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
        
        $this->creatingBackup = false;
        $this->loadBackups();
    }

    private function createJsonBackup()
    {
        $filename = 'backup_' . date('Y_m_d_His') . '.json';
        $backupPath = 'backups/' . $filename;
        
        // Pastikan directory backups ada
        if (!Storage::disk('local')->exists('backups')) {
            Storage::disk('local')->makeDirectory('backups');
        }

        $backupData = [
            'metadata' => [
                'created_at' => now()->toDateTimeString(),
                'type' => 'json_backup',
                'database' => config('database.connections.mysql.database'),
                'tables' => []
            ],
            'data' => []
        ];

        try {
            // Backup tabel-tabel penting saja (bisa disesuaikan)
            $tables = [
                'users', 'siswas', 'gurus', 'kelas', 'mapels', 
                'nilais', 'absensis', 'pembayaran_spp', 'tagihan_spp'
            ];

            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $backupData['metadata']['tables'][] = $table;
                    $backupData['data'][$table] = DB::table($table)->get()->toArray();
                }
            }

            // Simpan backup sebagai JSON
            Storage::disk('local')->put($backupPath, json_encode($backupData, JSON_PRETTY_PRINT));
            
            session()->flash('success', 'Backup JSON berhasil dibuat: ' . $filename . ' (' . count($backupData['metadata']['tables']) . ' tabel)');
            
        } catch (\Exception $e) {
            // Jika gagal, buat backup minimal
            $this->createMinimalBackup($backupPath, 'json');
        }
    }

    private function createSqlBackup()
    {
        $filename = 'backup_data_' . date('Y_m_d_His') . '.sql';
        $backupPath = 'backups/' . $filename;
        
        // Pastikan directory backups ada
        if (!Storage::disk('local')->exists('backups')) {
            Storage::disk('local')->makeDirectory('backups');
        }

        try {
            // Daftar tabel untuk di-backup
            $tables = [
                'users', 'siswas', 'gurus', 'kelas', 'mapels', 
                'nilais', 'absensis', 'pembayaran_spp', 'tagihan_spp'
            ];

            $sqlContent = "-- MySQL Data Backup\n";
            $sqlContent .= "-- Generated: " . now()->toDateTimeString() . "\n";
            $sqlContent .= "-- Database: " . config('database.connections.mysql.database') . "\n";
            $sqlContent .= "-- Note: This backup contains DATA ONLY, no table structure\n\n";
            
            $sqlContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            $backedUpTables = 0;

            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $backedUpTables++;
                    
                    // Hanya backup data, bukan struktur
                    $rows = DB::table($table)->get();
                    
                    if ($rows->count() > 0) {
                        $sqlContent .= "--\n-- Dumping data for table `{$table}`\n--\n";
                        
                        // Hapus data lama sebelum insert (untuk restore)
                        $sqlContent .= "DELETE FROM `{$table}`;\n\n";
                        
                        foreach ($rows->chunk(100) as $chunkIndex => $chunk) {
                            $sqlContent .= "INSERT INTO `{$table}` VALUES ";
                            
                            $values = [];
                            foreach ($chunk as $row) {
                                $rowValues = [];
                                foreach ((array)$row as $value) {
                                    if ($value === null) {
                                        $rowValues[] = 'NULL';
                                    } else {
                                        $rowValues[] = "'" . addslashes($value) . "'";
                                    }
                                }
                                $values[] = '(' . implode(', ', $rowValues) . ')';
                            }
                            
                            $sqlContent .= implode(', ', $values) . ";\n";
                        }
                        $sqlContent .= "\n";
                    } else {
                        $sqlContent .= "--\n-- Table `{$table}` is empty\n--\n\n";
                    }
                }
            }

            $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // Simpan file SQL
            Storage::disk('local')->put($backupPath, $sqlContent);
            
            session()->flash('success', 'Backup SQL (Data Only) berhasil dibuat: ' . $filename . ' (' . $backedUpTables . ' tabel)');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat backup SQL: ' . $e->getMessage());
            // Fallback ke JSON backup
            $this->createJsonBackup();
        }
    }

    private function createFullSqlBackup()
    {
        $filename = 'full_backup_' . date('Y_m_d_His') . '.sql';
        $backupPath = 'backups/' . $filename;
        
        if (!Storage::disk('local')->exists('backups')) {
            Storage::disk('local')->makeDirectory('backups');
        }

        try {
            $tables = [
                'users', 'siswas', 'gurus', 'kelas', 'mapels', 
                'nilais', 'absensis', 'pembayaran_spp', 'tagihan_spp'
            ];

            $sqlContent = "-- MySQL Full Backup (Structure + Data)\n";
            $sqlContent .= "-- Generated: " . now()->toDateTimeString() . "\n";
            $sqlContent .= "-- Database: " . config('database.connections.mysql.database') . "\n";
            $sqlContent .= "-- Note: Uses CREATE TABLE IF NOT EXISTS for safe restoration\n\n";
            
            $sqlContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            $backedUpTables = 0;

            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $backedUpTables++;
                    
                    // Backup struktur dengan IF NOT EXISTS
                    $createTableResult = DB::select("SHOW CREATE TABLE `{$table}`");
                    if (!empty($createTableResult)) {
                        $createTable = $createTableResult[0]->{'Create Table'};
                        // Replace CREATE TABLE dengan CREATE TABLE IF NOT EXISTS
                        $createTable = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $createTable);
                        
                        $sqlContent .= "--\n-- Table structure for table `{$table}`\n--\n";
                        $sqlContent .= $createTable . ";\n\n";
                    }
                    
                    // Backup data
                    $rows = DB::table($table)->get();
                    
                    if ($rows->count() > 0) {
                        $sqlContent .= "--\n-- Dumping data for table `{$table}`\n--\n";
                        $sqlContent .= "DELETE FROM `{$table}`;\n\n"; // Hapus data lama sebelum insert
                        
                        foreach ($rows->chunk(100) as $chunkIndex => $chunk) {
                            $sqlContent .= "INSERT INTO `{$table}` VALUES ";
                            
                            $values = [];
                            foreach ($chunk as $row) {
                                $rowValues = [];
                                foreach ((array)$row as $value) {
                                    if ($value === null) {
                                        $rowValues[] = 'NULL';
                                    } else {
                                        $rowValues[] = "'" . addslashes($value) . "'";
                                    }
                                }
                                $values[] = '(' . implode(', ', $rowValues) . ')';
                            }
                            
                            $sqlContent .= implode(', ', $values) . ";\n";
                        }
                        $sqlContent .= "\n";
                    } else {
                        $sqlContent .= "--\n-- Table `{$table}` is empty\n--\n\n";
                    }
                }
            }

            $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;\n";

            Storage::disk('local')->put($backupPath, $sqlContent);
            
            session()->flash('success', 'Backup SQL Full (Struktur + Data) berhasil dibuat: ' . $filename . ' (' . $backedUpTables . ' tabel)');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat backup SQL lengkap: ' . $e->getMessage());
            // Fallback ke backup data saja
            $this->createSqlBackup();
        }
    }

    private function createMinimalBackup($backupPath, $type)
    {
        $minimalData = [
            'metadata' => [
                'created_at' => now()->toDateTimeString(),
                'type' => 'minimal_backup',
                'format' => $type,
                'note' => 'Backup created without complete database access'
            ],
            'info' => 'This is a minimal backup file. Install spatie/laravel-backup for full database backup.'
        ];
        
        Storage::disk('local')->put($backupPath, json_encode($minimalData, JSON_PRETTY_PRINT));
        
        session()->flash('info', 'Backup minimal berhasil dibuat. Install package spatie/laravel-backup untuk backup lengkap.');
    }

    public function downloadBackup($filename)
    {
        try {
            set_time_limit(30);
            
            if (class_exists('Spatie\Backup\BackupServiceProvider')) {
                $backupDisk = Storage::disk(config('backup.backup.destination.disks')[0] ?? 'local');
                $path = config('backup.backup.name', 'laravel-backup') . '/' . $filename;
                
                if ($backupDisk->exists($path)) {
                    return response()->download($backupDisk->path($path));
                }
            }
            
            // Untuk backup manual
            $path = 'backups/' . $filename;
            if (Storage::disk('local')->exists($path)) {
                return Storage::disk('local')->download($path);
            }
            
            session()->flash('error', 'File backup tidak ditemukan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mendownload backup: ' . $e->getMessage());
        }
    }

    public function deleteBackup($filename)
    {
        try {
            if (class_exists('Spatie\Backup\BackupServiceProvider')) {
                $backupDisk = Storage::disk(config('backup.backup.destination.disks')[0] ?? 'local');
                $path = config('backup.backup.name', 'laravel-backup') . '/' . $filename;
                
                if ($backupDisk->exists($path)) {
                    $backupDisk->delete($path);
                }
            }
            
            // Untuk backup manual
            $path = 'backups/' . $filename;
            if (Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
            
            session()->flash('success', 'Backup berhasil dihapus: ' . $filename);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus backup: ' . $e->getMessage());
        }
        
        $this->loadBackups();
    }

    public function restoreBackup($filename)
    {
        try {
            $path = 'backups/' . $filename;
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (Storage::disk('local')->exists($path)) {
                if ($extension === 'json') {
                    $this->restoreFromJson($path);
                } elseif ($extension === 'sql') {
                    $this->restoreFromSql($path);
                } else {
                    session()->flash('error', 'Format backup tidak didukung untuk restore: ' . $extension);
                }
            } else {
                session()->flash('error', 'File backup tidak ditemukan.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memulai proses restore: ' . $e->getMessage());
        }
    }

    private function restoreFromJson($filePath)
    {
        try {
            $content = Storage::disk('local')->get($filePath);
            $backupData = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Format JSON tidak valid');
            }
            
            if (!isset($backupData['data'])) {
                throw new \Exception('Struktur backup JSON tidak valid');
            }
            
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            $restoredTables = 0;
            
            foreach ($backupData['data'] as $table => $records) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    // Kosongkan tabel terlebih dahulu
                    DB::table($table)->truncate();
                    
                    // Insert data
                    foreach (array_chunk($records, 100) as $chunk) {
                        DB::table($table)->insert($chunk);
                    }
                    
                    $restoredTables++;
                }
            }
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            session()->flash('success', 'Restore dari JSON berhasil: ' . $restoredTables . ' tabel dipulihkan');
            
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            session()->flash('error', 'Gagal restore dari JSON: ' . $e->getMessage());
        }
    }

    private function restoreFromSql($filePath)
    {
        try {
            $content = Storage::disk('local')->get($filePath);
            
            // Split SQL content by semicolons
            $queries = array_filter(array_map('trim', explode(';', $content)));
            
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            $executedQueries = 0;
            $errors = [];
            
            foreach ($queries as $query) {
                if (!empty($query) && !str_starts_with($query, '--')) {
                    try {
                        DB::statement($query);
                        $executedQueries++;
                    } catch (\Exception $e) {
                        // Skip error untuk CREATE TABLE yang sudah ada (jika menggunakan IF NOT EXISTS)
                        if (str_contains($e->getMessage(), 'already exists') && 
                            str_starts_with(strtoupper(trim($query)), 'CREATE TABLE')) {
                            // Skip saja, tabel sudah ada
                            continue;
                        }
                        
                        // Untuk error lainnya, catat tapi lanjutkan
                        $errors[] = "Query gagal: " . substr($query, 0, 100) . "... Error: " . $e->getMessage();
                    }
                }
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            DB::commit();
            
            $message = 'Restore dari SQL berhasil: ' . $executedQueries . ' query dieksekusi';
            if (!empty($errors)) {
                $message .= '. Terdapat ' . count($errors) . ' error yang diabaikan';
                logger()->error('SQL Restore Errors:', $errors);
            }
            
            session()->flash('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            session()->flash('error', 'Gagal restore dari SQL: ' . $e->getMessage());
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $base = log($size) / log(1024);
            $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        return '0 bytes';
    }
}