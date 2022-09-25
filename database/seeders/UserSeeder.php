<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = array(
	    		[
	            'first_name' => 'Gligorije',
	            'last_name' => 'Saric',
	            'email' => 'gligorijesaric@gmail.com',
	            'timezone' => 'Europe/Brussels',
		    	'password' => bcrypt('test123456')
	        	],
	        	[
	            'first_name' => 'Gligorije',
	            'last_name' => 'Developer',
	            'email' => 'gligorijesaric@hotmail.com',
	            'timezone' => 'America/Chicago',
		    	'password' => bcrypt('test123456')
	        	]
	        );

		foreach ($members as $member) 
		{
	        User::create([
	        	'first_name' => $member['first_name'],
	            'last_name' => $member['last_name'],
	            'email' => $member['email'],
	            'timezone' => $member['timezone'],
				'password' => $member['password']
	    	]);
	    }
    }
}
