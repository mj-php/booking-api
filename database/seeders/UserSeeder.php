<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2023-02-18 10:22:11');

        DB::table('users')->insert([
            'name' => 'Michal Jankiewicz',
            'email' => 'user',
            'password' => Hash::make('michal14'),
            'updated_at' => $date,
            'created_at' => $date,
        ]);

        DB::table('users')->insert([
            'name' => 'Majkel Jackson',
            'email' => 'user2',
            'password' => Hash::make('michal14'),
            'updated_at' => $date,
            'created_at' => $date,
        ]);
    }
}
