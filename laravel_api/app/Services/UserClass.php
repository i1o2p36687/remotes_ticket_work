<?php
namespace App\Services;

use App\Models\RoleMap;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;

class UserClass {
    //取得使用者資訊
    public static function get_user_info(){
        $user_id = Auth::user()->id;
        //取得腳色
        $role = RoleMap::with(['getRole', 'getUser'])->where('user_id', $user_id)->first()->toArray();
        //取得使用者擁有權限
        $permissions = self::get_user_permission();

        return ['role'=> $role['get_role']['name'], 'account'=> $role['get_user']['account'], 'name'=> $role['get_user']['name'], 'permissions'=> $permissions];
    }

    //取得使用者列表
    public static function get_user_list($data){
        $user = User::with(['getRoleMap'])->select('id', 'account', 'name', 'status', 'created_at', 'updated_at');

        if(isset($data['status']) && strlen($data['status'])){
            $user = $user->where('status', $data['status']);
        }

        $user = $user->orderBy('id', 'asc');

        return $user;
    }

    //取得使用者擁有權限
    public static function get_user_permission(){
        $user_id = Auth::user()->id;

        $role = RoleMap::where('user_id', $user_id)->first();

        //非admin使用者
        if($role->role_id != 1){
            $permissions = DB::table('role as r')
                            ->join('role_map as rm', 'rm.role_id', '=', 'r.id')
                            ->join('permission_map as pm', 'pm.role_id', '=', 'r.id')
                            ->join('permission as p', 'p.id', '=', 'pm.permission_id')
                            ->select('p.code')
                            ->where('rm.user_id', $user_id)
                            ->where('r.status', 1)
                            ->where('r.status', 1)
                            ->orderBy('p.id', 'asc')
                            ->get();
        }else{
            $permissions = Permission::select('code')->orderBy('id', 'asc')->get();
        }
        
        $tmp = [];
        if($permissions = json_decode(json_encode($permissions), true)){
            foreach($permissions as $v){
                $tmp[] = $v['code'];
            }
        }

        return $tmp;
    }

    public static function edit_user($data){
        if($data['id'] == ''){
            $user_id = User::insertGetId([
                'account'=> $data['account'],
                'password'=> Hash::make($data['password']),
                'name'=> $data['name'],
                'created_at'=> date('Y-m-d H:i:s')
            ]);
            if(!$user_id){
                throw new \Exception('新增失敗');
            }

            if(!$role = RoleMap::insert(['user_id'=> $user_id, 'role_id'=> $data['role_id']])){
                throw new \Exception('角色修改失敗');
            }
        }else{
            $user_id = $data['id'];
            $update = User::where('id', $user_id)->update(['name'=> $data['name']]);
            $role = RoleMap::where('user_id', $user_id)->update(['role_id'=> $data['role_id']]);
        }

        return true;
    }
}