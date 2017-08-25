<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class User extends Model {

    protected $table = 'user';
    protected $fillable = ['name', 'email', 'role_id', 'password', 'salt'];

    protected $dates = ['delete_at'];

    public static $rules = [
        // Validation rules
        'name'  =>  'required|max:32',
        'email' =>  'required|email|unique:users',
    ];

    // Relationships

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 角色
     */
    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
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

    /**
     * 收到的回复
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function getReplies()
    {
        return $this->hasManyThrough(
          'App\Models\Reply', 'App\Models\Topic',
                'user_id'
        );
    }

    /**
     * @param $id
     * @param $permission
     * @return bool
     */
    public function checkPermissionByUses($uses)
    {
        // 检查是不是网管
        if ($this->role->isRoot()){
            return true;
        }
        return $this->role->permissions()->where('uses', $uses)->first() ? true : false;
    }

}
