<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LoginClass;

class LoginController extends Controller
{
    /**
     * 登入
     * 
     * @param string request->account 
     * @param string request->password
     * 
     * @return array {access_token=> 登入token, refresh_token=> 刷新token}
     */
    public function login(Request $request){
        try {
            $account = $request->account;
            $password = $request->password;

            $result = LoginClass::login($account, $password);
            $response = ['result'=> 'success', 'access_token'=> $result['access_token'], 'refresh_token'=> $result['refresh_token']];

        } catch (\Exception $e) {
            $response = ['result'=> 'fail', 'msg'=> $e->getMessage()];
        }

        return $response;
    }
}