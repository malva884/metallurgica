<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    Role::create([
		    'name' => 'super-admin',
		    'guard_name ' => 'web',
	        ],
		    [
			    'name' => 'admin',
			    'guard_name ' => 'web',
		    ],
		    [
			    'name' => 'staff',
			    'guard_name ' => 'web',
		    ],
		    [
			    'name' => 'user',
			    'guard_name ' => 'web',
		    ],
		    [
		        'name' => 'clinet',
			    'guard_name ' => 'web',
		    ]
	    );
    }
}
