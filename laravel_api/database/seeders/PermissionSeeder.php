<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'view_bug'=> '瀏覽錯誤', 'create_bug'=> '創建錯誤', 'update_bug'=> '修改錯誤', 'delete_bug'=> '刪除錯誤', 'resolve_bug'=> '解決錯誤',
            'view_test'=> '瀏覽測試', 'create_test'=> '創建測試', 'update_test'=> '修改測試', 'delete_test'=> '刪除測試', 'resolve_test'=> '解決測試',
            'view_feature'=> '瀏覽功能', 'create_feature'=> '創建功能', 'update_feature'=> '修改功能', 'delete_feature'=> '刪除功能', 'resolve_feature'=> '解決功能'
        ];

        $insert = [];
        foreach($permissions as $k=>$v){
            $insert[] = ['code'=> $k, 'name'=> $v];
        }

        DB::table('permission')->insert($insert);
    }
}
