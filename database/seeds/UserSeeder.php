<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Puri Bunda',
            'username' => 'puribunda',
            'email' => 'itm@puribunda.com',
            'password' => bcrypt('bullish'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
