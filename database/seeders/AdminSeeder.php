<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama'     => 'Super Admin',
            'username' => 'superadmin',
            'password' => Hash::make('admin123'),
            'role'     => 'superadmin',
        ]);

        Admin::create([
            'nama'     => 'Admin OSA',
            'username' => 'adminosa',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);
    }
}