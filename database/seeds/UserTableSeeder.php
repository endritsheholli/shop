<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@test.com',
            'password' => bcrypt('123456'),
            'shop_name' => 'admin',
            'role' => 'admin'
        ]);
    }
}
