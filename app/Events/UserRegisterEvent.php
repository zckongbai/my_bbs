<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/22
 * Time: 下午2:37
 * 用户注册事件
 */

namespace App\Events;

use App\Models\User;

class UserRegisterEvent
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

}