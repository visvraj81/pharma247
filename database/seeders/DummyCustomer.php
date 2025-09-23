<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerModel;

class DummyCustomer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerModel::create([
            'name' => 'Direct Customers',
            'email' => 'directcustomer@gmail.com',
            'phone_number' => '1212121212',
            'city' => 'surat',
            'address' => 'surat',
            'status' => '1',
            'status' => '1',
            'role' => '3',
            'balance' =>'0',
        ]);
    }
}
