<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    protected $table = 'permission';
    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

}
