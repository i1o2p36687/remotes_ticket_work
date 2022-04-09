<?php
/**
 * 檢查使用者權限
 */
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Services\UserClass;

class ChkUserPermission extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user_permissions = UserClass::get_user_permission();
        if(!in_array($permission, $user_permissions)){
            return response()->json(['result'=>'fail', 'msg'=> '權限不足!'], 200);
        }

        return $next($request);
    }
}
