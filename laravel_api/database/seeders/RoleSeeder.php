<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            [
                'name' => 'admin',
                'created_at'=> date('Y-m-d H:i:s')
            ],[
                'name' => 'QA',
                'created_at'=> date('Y-m-d H:i:s')
            ],[
                'name' => 'RD',
                'created_at'=> date('Y-m-d H:i:s')
            ],[
                'name' => 'PM',
                'created_at'=> date('Y-m-d H:i:s')
            ]
        ]);
    }
}
