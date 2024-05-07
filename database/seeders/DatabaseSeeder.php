<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\Role::create([
            'name' => 'Super Admin',
        ]);
        \App\Models\Role::create([
            'name' => 'Admin',
        ]);
        \App\Models\Role::create([
            'name' => 'Sales Agent',
        ]);


        \App\Models\User::create([
            'fname' => 'Super',
            'lname' => 'SAdmin',
            'email' => 'sadmin@gmail.com',
            'phone' => '0711111111',
            'is_admin' => true,
            'role_id' => 1,
            'password' => Hash::make('admin')
        ]);
        \App\Models\User::create([
            'fname' => 'Admin',
            'lname' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '0722222222',
            'is_admin' => true,
            'role_id' => 2,
            'password' => Hash::make('admin')
        ]);
        \App\Models\User::create([
            'fname' => 'Sales',
            'lname' => 'Rep',
            'email' => 'sales@gmail.com',
            'phone' => '0733333333',
            'is_admin' => false,
            'role_id' => 3,
            'password' => Hash::make('admin')
        ]);

        for ($i=0; $i < 10; $i++) { 
            \App\Models\Client::create([
                'name' => 'Client'.$i,
                'email' => 'keenzuki@gmail.com',
                'phone' => '070172'.str_pad($i,4,0,STR_PAD_LEFT),
                'address' => 'P O Box'.str_pad($i,4,0,STR_PAD_LEFT).' Kithimani',
                'agent_id'=> $i <20? 3 : null
            ]);
        }
    }
}
