<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $fillable = ['name', 'email', 'role_id', 'password', 'salt'];

    protected $dates = ['delete_at'];

    public static $rules = [
        // Validation rules
        'name'  =>  'required|max:32',
        'email' =>  'required|email|unique',
    ];

    // Relationships

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 角色
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *  发出的回复
     */
    public function replies()
    {
        return $this->hasMany('App\Models\Reply');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 发的帖子
     */
    public function topics()
    {
        return $this->hasMany('App\Models\Topic');
    }

    public function getReplies()
    {
        return $this->hasManyThrough(
          'App\Models\Reply', 'App\Models\Topic',
                'user_id', 'topic_id'
        );
    }

}
