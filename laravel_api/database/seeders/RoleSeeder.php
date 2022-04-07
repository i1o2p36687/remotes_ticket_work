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
        $qa_permission = [ 
            'view_bug', 'create_bug', 'update_bug', 'delete_bug', 
            'view_test', 'create_test', 'update_test', 'delete_test', 'resolve_test'
        ];
        $rd_permission = ['view_bug', 'resolve_bug', 'view_test', 'view_feature', 'resolve_feature'];
        $pm_permission = [
            'view_test', 
            'view_feature', 'create_feature', 'update_feature', 'delete_feature'
        ];

        DB::table('role')->insert([
            [
                'name' => 'admin',
                'permission'=> json_encode([]),
                'created_at'=> date('Y-m-d H:i:s')
            ],[
                'name' => 'QA',
                'permission'=> json_encode($qa_permission),
                'created_at'=> date('Y-m-d H:i:s')
            ],[
                'name' => 'RD',
                'permission'=> json_encode($rd_permission),
                'created_at'=> date('Y-m-d H:i:s')
            ],[
                'name' => 'PM',
                'permission'=> json_encode($pm_permission),
                'created_at'=> date('Y-m-d H:i:s')
            ]
        ]);
    }
}
