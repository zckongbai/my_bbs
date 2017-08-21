<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model {

    protected $table = 'permission_role';

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
