<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionMapSeeder extends Seeder
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
        
        //QA
        $qa_select = implode("','", $qa_permission);
        DB::insert("insert into permission_map (permission_id, role_id) 
                    select id, 2 
                    from permission 
                    where code in ('$qa_select') 
                    order by id asc");
        //RD
        $rd_select = implode("','", $rd_permission);
        DB::insert("insert into permission_map (permission_id, role_id) 
                    select id, 3 
                    from permission 
                    where code in ('$rd_select') 
                    order by id asc");
        //PM
        $pm_select = implode("','", $pm_permission);
        DB::insert("insert into permission_map (permission_id, role_id) 
                    select id, 4 
                    from permission 
                    where code in ('$pm_select') 
                    order by id asc");
    }
}
