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
            'fname' => 'Francis',
            'lname' => 'Super Admin',
            'email' => 'sadmin@gmail.com',
            'phone' => '0711111111',
            'is_admin' => true,
            'role_id' => 1,
            'password' => Hash::make('keenzuki')
        ]);
        \App\Models\User::create([
            'fname' => 'Francis',
            'lname' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '0722222222',
            'is_admin' => true,
            'role_id' => 2,
            'password' => Hash::make('keenzuki')
        ]);
        \App\Models\User::create([
            'fname' => 'Francis',
            'lname' => 'sales',
            'email' => 'sales@gmail.com',
            'phone' => '0733333333',
            'is_admin' => false,
            'role_id' => 3,
            'password' => Hash::make('keenzuki')
        ]);


        \App\Models\User::create([
            'fname' => 'Nite',
            'lname' => 'N',
            'email' => 'nite@inspirehub.co.ke',
            'phone' => '0701020304',
            'is_admin' => false,
            'role_id' => 3,
            'password' => Hash::make('nite@inspirehub')
        ]);
        \App\Models\User::create([
            'fname' => 'Martin',
            'lname' => 'M',
            'email' => 'martin@inspirehub.co.ke',
            'phone' => '0710203040',
            'is_admin' => false,
            'role_id' => 3,
            'password' => Hash::make('martin@inspirehub')
        ]);
        \App\Models\User::create([
            'fname' => 'George',
            'lname' => 'K',
            'email' => 'george@inspirehub.co.ke',
            'phone' => '0715959665',
            'is_admin' => false,
            'role_id' => 3,
            'password' => Hash::make('george@inspirehub')
        ]);
        \App\Models\User::create([
            'fname' => 'Shadrack',
            'lname' => 'Mule',
            'email' => 'md@inspirehub.co.ke',
            'phone' => '0715653981',
            'is_admin' => false,
            'role_id' => 3,
            'password' => Hash::make('md@inspirehub')
        ]);
        \App\Models\User::create([
            'fname' => 'Moses',
            'lname' => 'Makokha',
            'email' => 'moses.makokha@inspirehub.co.ke',
            'phone' => '0750607080',
            'is_admin' => false,
            'role_id' => 3,
            'password' => Hash::make('moses.makokha@inspirehub')
        ]);
        \App\Models\User::create([
            'fname' => 'Sharon',
            'lname' => 'S',
            'email' => 'sharon@inspirehub.co.ke',
            'phone' => '0790807060',
            'is_admin' => false,
            'role_id' => 3,
            'password' => Hash::make('sharon@inspirehub')
        ]);
    }
}
