<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (yang sudah ada)
        $permissions = [
            // Dashboard
            'view dashboard',
            
            // User Management
            'view users',
            'create users', 
            'edit users',
            'delete users',
            
            // Guru Management
            'view gurus',
            'create gurus',
            'edit gurus', 
            'delete gurus',
            
            // Siswa Management
            'view siswas',
            'create siswas',
            'edit siswas',
            'delete siswas',
            
            // ... permissions lainnya
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // TAMBAHKAN: Permission khusus untuk API
        $apiPermissions = [
            // API permissions dengan format {model}.{action}
            'guru.read',
            'guru.create', 
            'guru.update',
            'guru.delete',
            
            'siswa.read',
            'siswa.create',
            'siswa.update', 
            'siswa.delete',
            
            'kelas.read',
            'kelas.create',
            'kelas.update',
            'kelas.delete',
            
            'mapel.read',
            'mapel.create',
            'mapel.update',
            'mapel.delete',
        ];

        foreach ($apiPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);
        
        // TAMBAHKAN: Role untuk API
        $apiRole = Role::firstOrCreate(['name' => 'api']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo($permissions);
        $adminRole->givePermissionTo($apiPermissions); // Juga beri permission API ke admin

        // Assign API permissions to api role
        $apiRole->givePermissionTo($apiPermissions);

        // Assign permissions to guru (yang sudah ada)
        $guruPermissions = [
            'view dashboard',
            'view jadwals',
            'view nilais',
            'create nilais',
            'edit nilais',
            'view pengumumen',
            'create pengumumen',
            'edit pengumumen',
        ];
        $guruRole->givePermissionTo($guruPermissions);

        // Assign permissions to siswa (yang sudah ada)
        $siswaPermissions = [
            'view dashboard',
            'view jadwals', 
            'view nilais',
            'view pengumumen',
        ];
        $siswaRole->givePermissionTo($siswaPermissions);

        // Assign admin role to first user
        try {
            $user = User::first();
            if ($user) {
                $user->assignRole('admin');
                $this->command->info('Admin role assigned to user: ' . $user->email);
            } else {
                $this->command->warn('No user found to assign admin role');
            }
        } catch (\Exception $e) {
            $this->command->error('Error assigning admin role: ' . $e->getMessage());
        }

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Total roles: ' . Role::count());
        $this->command->info('Total permissions: ' . Permission::count());
    }
}