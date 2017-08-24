<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    // 网管名字
    const ROOT_NAME = 'root';

    protected $table = 'role';

    protected $fillable = ['name'];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission');
    }

    /**
     * 是不是网管
     * @return bool
     */
    public function isRoot()
    {
        return $this->name == self::ROOT_NAME;
    }
}
