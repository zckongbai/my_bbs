<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 帖子类别
 * Class Section
 * @package App\Models
 */
class Section extends Model {
    use SoftDeletes;

    protected $table = 'section';
    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function topics()
    {
        return $this->hasMany('App\Models\Topic');
    }

    public function hotTopics()
    {
        return static::all()->each(function ($item, $key){
            $item->topics->where('status', 1)->sortByDesc('click_number')->limit(3);
        });
        return static::query(
        );
    }

}
