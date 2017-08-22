<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/21
 * Time: 下午8:58
 */

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;


class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        // TODO: Implement register() method.
    }

    public function boot()
    {
        User::creating(function ($user){
            if (!$user->isVaid()){
                return false;
            }
        });
    }
}