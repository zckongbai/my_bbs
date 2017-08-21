<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model {

    protected $table = 'reply';
    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
