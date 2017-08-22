<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/22
 * Time: 下午1:59
 * 初始化用户
 */

namespace App\Events;


class InitUserEvent
{
    protected $user;
    public function __construct()
    {
        $this->user = '';
    }

}