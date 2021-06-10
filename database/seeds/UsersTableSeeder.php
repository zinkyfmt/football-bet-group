<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'o0ozinkyo0o@gmail.com',
            'role' => 1,
            'password' => Hash::make('minhtinh2807'),
        ]);
        DB::table('users')->insert([
            'name' => 'Tinh Pham',
            'username' => 'zinkyfmt',
            'email' => 'minhtinh2302@gmail.com',
            'role' => 5,
            'password' => Hash::make('minhtinh2807'),
        ]);
    }
}