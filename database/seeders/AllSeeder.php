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
            'id' => 1,
            'name' => 'PIECES',
            'alias' => 'PCS',
            'created_at' => now(),
        ]);

        DB::table('units')->insert([
            'id' => 2,
            'name' => 'KARDUS',
            'alias' => 'KDS',
            'created_at' => now(),
        ]);

        DB::table('units')->insert([
            'id' => 3,
            'name' => 'RENCENG',
            'alias' => 'RCG',
            'created_at' => now(),
        ]);

        DB::table('items')->insert([
            'id' => 1,
            'name' => 'MIZONE',
            'code' => '12321321312',
            'manual' => false,
            'photo' => 'https://static8.depositphotos.com/1040728/935/i/600/depositphotos_9352722-stock-photo-tool-set.jpg',
            'created_at' => now(),
        ]);

        DB::table('items')->insert([
            'id' => 2,
            'name' => 'TELOR',
            'code' => '123456',
            'manual' => true,
            'photo' => 'https://static8.depositphotos.com/1040728/935/i/600/depositphotos_9352722-stock-photo-tool-set.jpg',
            'created_at' => now(),
        ]);

        DB::table('items')->insert([
            'id' => 3,
            'name' => 'PEMPES',
            'code' => '1234567',
            'manual' => false,
            'photo' => 'https://static8.depositphotos.com/1040728/935/i/600/depositphotos_9352722-stock-photo-tool-set.jpg',
            'created_at' => now(),
        ]);

        // Looping insert items
        for ($i = 4; $i <= 50; $i++) {
            DB::table('items')->insert([
                'name' => 'ITEM ' . $i,
                'code' => '312498329883' . $i,
                'manual' => false,
                'photo' => 'https://static8.depositphotos.com/1040728/935/i/600/depositphotos_9352722-stock-photo-tool-set.jpg',
                'created_at' => now(),
            ]);
        }

        DB::table('prices')->insert([
            'id' => 1,
            'price' => 10000,
            'quantity' => 1,
            'item_id' => 1,
            'unit_id' => 1,
            'created_at' => now(),
        ]);

        DB::table('prices')->insert([
            'id' => 2,
            'price' => 50000,
            'quantity' => 1,
            'item_id' => 2,
            'unit_id' => 1,
            'created_at' => now(),
        ]);

        DB::table('prices')->insert([
            'id' => 3,
            'price' => 4000,
            'quantity' => 1,
            'item_id' => 2,
            'unit_id' => 1,
            'created_at' => now(),
        ]);

        DB::table('prices')->insert([
            'id' => 4,
            'price' => 12000,
            'quantity' => 12,
            'item_id' => 3,
            'unit_id' => 3,
            'created_at' => now(),
        ]);

        DB::table('prices')->insert([
            'id' => 5,
            'price' => 1000,
            'quantity' => 1,
            'item_id' => 3,
            'unit_id' => 1,
            'created_at' => now(),
        ]);
    }
}
