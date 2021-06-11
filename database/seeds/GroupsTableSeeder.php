<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            [
                'name' => 'Group A'
            ],
            [
                'name' => 'Group B'
            ],
            [
                'name' => 'Group C'
            ],
            [
                'name' => 'Group D'
            ],
            [
                'name' => 'Group E'
            ],
            [
                'name' => 'Group F'
            ],
        ];
        DB::table('groups')->insert($array);
    }
}