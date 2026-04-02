<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Posyandu;

class PosyanduSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Posyandu::create([
            'nama' => 'Posyandu Melati',
            'kode' => 'PSY-001',
            'kelurahan' => 'Kaliwates',
            'kecamatan' => 'Kaliwates',
            'kabupaten' => 'Jember',
            'alamat' => 'Jl. Melati No. 10',
            'is_active' => true,
        ]);

        Posyandu::create([
            'nama' => 'Posyandu Mawar',
            'kode' => 'PSY-002',
            'kelurahan' => 'Sumbersari',
            'kecamatan' => 'Sumbersari',
            'kabupaten' => 'Jember',
            'alamat' => 'Jl. Mawar No. 5',
            'is_active' => true,
        ]);
    }
}
