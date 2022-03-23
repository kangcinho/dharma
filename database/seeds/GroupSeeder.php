<?php

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mGroupKategori')->insert([
        	[
                'id' =>1,
                'namaGroupKategori' => 'Rawat Jalan',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
        	[
                'id' =>2,
                'namaGroupKategori' => 'Rawat Inap',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'id' =>3,
                'namaGroupKategori' => 'Rawat Darurat',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
        	[
                'id' =>4,
                'namaGroupKategori' => 'Cafetaria',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'id' =>5,
                'namaGroupKategori' => 'Penunjang',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
        	[
                'id' =>6,
                'namaGroupKategori' => 'Lain-lain',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'id' =>7,
                'namaGroupKategori' => 'Pasien Baru',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'id' =>8,
                'namaGroupKategori' => 'Pasien Repeater',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'id' =>9,
                'namaGroupKategori' => 'Diskon',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'id' =>10,
                'namaGroupKategori' => 'Paket',
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'id' =>11,
                'namaGroupKategori' => 'Pendapatan',
                'created_at' => now(),
                'updated_at' => now(),                
            ]
		]);
    }
}
