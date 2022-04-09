<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserClass;
use App\Services\CommonClass;
use App\Models\Role;

class UserController extends Controller
{   
    /**
     * 取得使用者資訊
     * 
     * @return array {role: 角色, account: 帳號, name: 名稱, permissions: (array)權限}
     */
    public function getUserInfo(Request $request){
        try {
            $result = UserClass::get_user_info();
            $response = ['result'=> 'success'];
            $response = array_merge($response, $result);
        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }

    /**
     * 取得使用者資訊
     * 
     * @param string request->page  頁數
     * @param string request->limit 一頁幾筆
     * 
     * @return array {result: 結果為 success or fail ,count: 總數, list: 列表}
     */
    public function getUserList(Request $request){
        try {
            $data = $request->all();
            $page = isset($data['page']) ? (int)$data['page'] : 1;
            $limit = isset($data['limit']) ? (int)$data['limit'] : 20;

            $users = UserClass::get_user_list($data);
            $result = CommonClass::getPageData($users, $page, $limit);

            if($result['list']){
                foreach($result['list'] as $i=>$v){
                    $result['list'][$i]['role_id'] = $v['get_role_map'] ? $v['get_role_map']['role_id'] : '';
                    unset($result['list'][$i]['get_role_map']);
                }
            }

            $response = ['result'=> 'success'];
            $response = array_merge($response, $result);
        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }

    public function getRoleList(){
        try {
            $roles = Role::select('id', 'name')
                        ->where('id', '<>', 1)
                        ->orderBy('id', 'asc')
                        ->get()
                        ->toArray();

            $response = ['result'=> 'success', 'list'=> $roles];
        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }

    /**
     * 新增使用者
     * 
     * @param string request->account 
     * @param string request->password
     * @param string request->name
     * @param string request->role_id
     * 
     * @return array {result: 結果為 success or fail}
     */
    public function createUser(Request $request){
        try {
            $data = $request->all();
            $data['id'] = '';
            $result = UserClass::editUser($data);

            $response = ['result'=> 'success', 'msg'=> '新增成功'];
        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }

    /**
     * 修改使用者
     * @param int request->id
     * @param string request->name
     * @param string request->role_id
     * 
     * @return array {result: 結果為 success or fail}
     */
    public function updateUser(Request $request){
        try {
            $data = $request->all();
            $result = UserClass::editUser($data);

            $response = ['result'=> 'success', 'msg'=> '修改成功'];
        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }
}