<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::create([
            'name'     => 'Budi Manager',
            'email'    => 'manager@perusahaan.com',
            'password' => Hash::make('password123'),
            'role'     => 'manager',
        ]);

        $user1 = User::create([
            'name'     => 'Andi Karyawan',
            'email'    => 'andi@perusahaan.com',
            'password' => Hash::make('password123'),
            'role'     => 'karyawan',
        ]);

        Karyawan::create([
            'user_id'       => $user1->id,
            'nama'          => 'Andi Karyawan',
            'jabatan'       => 'Frontend Developer',
            'departemen'    => 'IT',
            'no_telepon'    => '08123456789',
            'tanggal_masuk' => '2023-01-15',
        ]);

        $user2 = User::create([
            'name'     => 'Sari Karyawan',
            'email'    => 'sari@perusahaan.com',
            'password' => Hash::make('password123'),
            'role'     => 'karyawan',
        ]);

        Karyawan::create([
            'user_id'       => $user2->id,
            'nama'          => 'Sari Karyawan',
            'jabatan'       => 'HR Specialist',
            'departemen'    => 'Human Resource',
            'no_telepon'    => '08234567890',
            'tanggal_masuk' => '2023-03-01',
        ]);
    }
}
