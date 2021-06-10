<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'name' => 'Tinh Pham',
            'username' => 'zinkyfmt',
            'email' => 'o0ozinkyo0o@gmail.com',
            'password' => Hash::make('minhtinh2807'),
        ]);
    }
}