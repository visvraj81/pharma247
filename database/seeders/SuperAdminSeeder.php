<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'superadmin@gmail.com',
            'phone_number' => '1234567890',
            'role' => '0',
            'status' => '1',
            'password' => Hash::make('password') ,
        ]);
    }
}
