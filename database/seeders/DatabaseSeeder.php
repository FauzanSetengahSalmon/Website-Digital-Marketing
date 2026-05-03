<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun ADMIN
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'phone_number' => '0811111111',
            'role' => 'admin',
        ]);

        // 2. Akun KWT (Pengelola Produk)
        User::create([
            'name' => 'Ketua KWT',
            'email' => 'kwt@gmail.com',
            'password' => Hash::make('kwt123'),
            'phone_number' => '0822222222',
            'role' => 'kwt',
            'address' => 'Desa Makmur Jaya',
        ]);

        // 3. Akun CUSTOMER (Untuk ngetes katalog)
        User::create([
            'name' => 'Budi Pembeli',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('user123'),
            'phone_number' => '0833333333',
            'role' => 'customer',
        ]);
    }
}