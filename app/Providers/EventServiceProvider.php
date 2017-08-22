<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],

//        'App\Events\UserLoginEvent' => [
//            'App\Listeners\UserEventListener',
//        ]
    ];

    /**
     * 要注册的订阅者
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\UserEventListener',
        'App\Listeners\TopicEventListener',
    ];

}
