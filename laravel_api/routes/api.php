<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//登入
Route::post('/login', 'LoginController@login');
//刷新登入token
Route::post('/refreshToken', 'LoginController@refreshToken');

//需登入token
Route::group(['middleware' => ['auth:api']], function () {
    //取得使用者資訊
    Route::get('/user_info', 'UserController@getUserInfo');
    //取得使用者列表
    Route::get('/get_user_list', 'UserController@getUserList')->middleware('chk.permission:view_user');
    //取得所有角色
    Route::get('/get_role_list', 'UserController@getRoleList');
    //新增使用者
    Route::post('/create_user', 'UserController@createUser')->middleware('chk.permission:create_user');
    //修改使用者
    Route::put('/update_user', 'UserController@updateUser')->middleware('chk.permission:update_user');
});