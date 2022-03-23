<?php

use Illuminate\Database\Seeder;

class BulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mBulan')->insert([
        	[
                'bulan' => 'Januari',
                'bulanSingkatan' => 'Jan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'Februari',
                'bulanSingkatan' => 'Feb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'Maret',
                'bulanSingkatan' => 'Mar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'April',
                'bulanSingkatan' => 'Apr',
                'created_at' => now(),
                'updated_at' => now(),
            ],       	
            [
                'bulan' => 'Mei',
                'bulanSingkatan' => 'Mei',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'Juni',
                'bulanSingkatan' => 'Jun',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'Juli',
                'bulanSingkatan' => 'Jul',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'Agustus',
                'bulanSingkatan' => 'Aug',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'September',
                'bulanSingkatan' => 'Sep',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'Oktober',
                'bulanSingkatan' => 'Okt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'November',
                'bulanSingkatan' => 'Nov',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        	[
                'bulan' => 'Desember',
                'bulanSingkatan' => 'Des',
                'created_at' => now(),
                'updated_at' => now(),
            ]
		]);
    }
}
