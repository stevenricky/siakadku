<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User (Data Real)
        $admin = User::firstOrCreate(
            ['email' => 'rickysilaban384@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('silaban154'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // User untuk Wali Kelas (12 guru)
        $waliKelasUsers = [
            ['name' => 'Dr. Maruli Tua Sitorus, M.Pd', 'email' => 'maruli@siakad.sch.id'],
            ['name' => 'Drs. Binsar Situmorang', 'email' => 'binsar@siakad.sch.id'],
            ['name' => 'Sinta Ria Hutagalung, S.Pd', 'email' => 'sinta@siakad.sch.id'],
            ['name' => 'Maya Sarah Simbolon, M.Pd', 'email' => 'maya@siakad.sch.id'],
            ['name' => 'Rizky Pratama Nainggolan, S.Pd', 'email' => 'rizky@siakad.sch.id'],
            ['name' => 'Hotman Paris Siahaan, S.Pd', 'email' => 'hotman@siakad.sch.id'],
            ['name' => 'Debora Rotua Sinaga, M.Pd', 'email' => 'debora@siakad.sch.id'],
            ['name' => 'Lina Marlina Pardede, S.Pd', 'email' => 'lina@siakad.sch.id'],
            ['name' => 'Jhonni Marbun, S.Pd', 'email' => 'jhonni@siakad.sch.id'],
            ['name' => 'Rina Julianti Sihombing, M.Pd', 'email' => 'rina@siakad.sch.id'],
            ['name' => 'Roy Martua Simanjuntak, S.Pd', 'email' => 'roy@siakad.sch.id'],
            ['name' => 'Erika Margaretha Sihotang, S.Pd', 'email' => 'erika@siakad.sch.id'],
        ];

        // User untuk Kepala Laboratorium (4 guru)
        $kepalaLabUsers = [
            ['name' => 'Dr. Robert Manurung, M.Si', 'email' => 'robert@siakad.sch.id'],
            ['name' => 'Diana Rosdiana Siregar, M.Si', 'email' => 'diana@siakad.sch.id'],
            ['name' => 'Ferdinand Tampubolon, S.Si', 'email' => 'ferdinand@siakad.sch.id'],
            ['name' => 'Sarah Debora Panjaitan, S.Kom', 'email' => 'sarah@siakad.sch.id'],
        ];

        $userCounter = 2; // Start from user_id 2

        // Create users for wali kelas
        foreach ($waliKelasUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password154'),
                    'role' => 'guru',
                    'is_active' => true,
                ]
            );
            $user->assignRole('guru');
            $userCounter++;
        }

        // Create users for kepala lab
        foreach ($kepalaLabUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password154'),
                    'role' => 'guru',
                    'is_active' => true,
                ]
            );
            $user->assignRole('guru');
            $userCounter++;
        }

        // Sample Siswa User (Hanya 1 untuk demo)
        $siswa = User::firstOrCreate(
            ['email' => 'siswa1@gmail.com'],
            [
                'name' => 'Siswa Demo',
                'password' => Hash::make('password154'),
                'role' => 'siswa',
                'is_active' => true,
            ]
        );
        $siswa->assignRole('siswa');
        $userCounter++;

        $this->command->info('User seeder berhasil dijalankan! Total users: ' . $userCounter);
        $this->command->info('Admin: rickysilaban384@gmail.com / silaban154');
        $this->command->info('Guru: guru@siakad.sch.id / password154');
        $this->command->info('Siswa: siswa1@gmail.com / password154');
    }
}