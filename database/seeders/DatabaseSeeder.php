<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('users')->insert([
            [
                'name' => 'Test Manager',
                'email' => 'manager@email.com',
                'password' => Hash::make('manager'),
                'username' => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],           
        ]);
    }
}
