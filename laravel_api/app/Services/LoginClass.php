<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use DB;

class LoginClass {
    /**
     * 登入
     * 
     * @param string $account 
     * @param string $password
     * 
     * @return array {access_token=> 登入token, refresh_token=> 刷新token}
     */
    public static function login($account, $password){
        //檢查
        if(self::chk_user_password($account, $password)){
            //取得token secret
            $token_secret = DB::table('oauth_clients')
                ->where('password_client', true)
                ->orderBy('id', 'asc')
                ->first();
            $token_secret = json_decode(json_encode($token_secret), true);
            $token_data = [
                'grant_type' => 'password',
                'client_id' => $token_secret['id'],
                'client_secret' => $token_secret['secret'],
                'username' => $account,
                'password' => $password,
            ];
            $response = Http::post(route('passport.token'), $token_data);
            $response_array = $response->json();
            
            return ['access_token'=> $response_array['access_token'], 'refresh_token'=> $response_array['refresh_token']];
        }
    }

    /**
     * 檢查密碼
     * 
     * @param string $account 
     * @param string $password
     * 
     * @return boolean
     */
    private static function chk_user_password($account, $password){
        $user = DB::table('users')
                  ->select('password', 'status')
                  ->where('account', $account)
                  ->first();
        if(!$user){
            throw new \Exception('帳號或密碼錯誤');
        }

        if(!Hash::check($password, $user->password)) {
            throw new \Exception('帳號或密碼錯誤');
        }

        if($user->status == 0){
            throw new \Exception('帳號已被停用');
        }

        return true;
    }
}