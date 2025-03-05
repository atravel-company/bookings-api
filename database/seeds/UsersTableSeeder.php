<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Always hash passwords!
        ]);

        User::create([
            'name' => 'admin',
            'email' => 'accounts@atravel.pt',
            'password' => bcrypt('password'), 
        ]);
    }
}
