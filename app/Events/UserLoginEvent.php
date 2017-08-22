<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/21
 * Time: 下午8:49
 * 用户登录事件
 */

namespace App\Events;

use App\Models\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;


class UserLoginEvent extends Event
{
    use SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


}