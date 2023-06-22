<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'admin',
        ]);

        DB::table('users')->insert([
            'name' => 'superadmin',
            'username' => 'admin',
            'photo' => 'https://img.freepik.com/free-icon/user_318-563642.jpg?w=360',
            'role_id' => 1,
            'password' => Hash::make('kinza123'),
        ]);

        DB::table('units')->insert([
            'name' => 'Pieces',
            'alias' => 'Pcs',
            'created_at' => now(),
        ]);
    }
}
