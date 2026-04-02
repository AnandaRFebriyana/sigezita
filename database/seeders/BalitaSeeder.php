<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Balita;

class BalitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Balita::create([
            'kode_balita' => 'BLT-0001',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2022-03-15',
            'nama_orang_tua' => 'Budi Santoso',
            'no_hp' => '081111111111',
            'alamat' => 'Jl. Contoh No.1',
            'posyandu_id' => 1,
            'user_id' => 2,
        ]);
    }
}
