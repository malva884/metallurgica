<?php

namespace Database\Seeders;

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
            'firstname' => 'Gregorio',
            'lastname' => 'Grande',
            'email' => 'gregorio.grande1984@gmail.com',
            'acl' => '4',
            'password' => Hash::make('pisolo84.'),
        ]);
    }
}
