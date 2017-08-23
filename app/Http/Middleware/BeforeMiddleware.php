<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/22
 * Time: 下午6:24
 */

namespace app\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Closure;

class BeforeMiddleware
{
    public function handle($request, Closure $next)
    {
        // echo 'BeforeMiddleware';
        DB::enableQueryLog();
        return $next($request);
    }

}