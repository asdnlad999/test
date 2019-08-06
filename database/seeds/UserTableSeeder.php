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
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'name'=>'administrator',
            'email'=>'admin@admin.com',
            'password'=>bcrypt('administrator888'),
            'avatar'=>'/assets/images/avatar.jpeg',
            'phone'=>'15680993343',
            'pid'=>0,
            'is_admin'=>true,
            'level'=>0,
            'created_at'=>\Carbon\Carbon::now()
        ]);
    }
}
