<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        DB::table('users')->insert([
            [
                'name' => 'Test Manager',
                'password' => Hash::make('manager'),
                'email' => 'manager@example.com',
                'email_verified_at' => now(),
                'username' => 'manager',
                'designation' => 'manager',
                'created_at' => now(),
            ],  
            [
                'name' => 'Test Cashier',
                'password' => Hash::make('cashier'),
                'email' => 'cashier@example.com',
                'email_verified_at' => now(),
                'username' => 'cashier',
                'designation' => 'cashier',
                'created_at' => now(),
            ],           
        ]);
    }
}
