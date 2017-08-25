<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 帖子
 * Class Topic
 * @package App\Models
 */
class Topic extends Model {

    use SoftDeletes;

    protected $table = 'topic';
    protected $fillable = ['section_id', 'user_id', 'title', 'content'];

    public static $rules = [
        // Validation rules

    ];

    // Relationships

    /**
     * 多个回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany('App\Models\Reply');
    }

    /**
     * 一个用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 所属类型
     * @param $number
     */
    public function section()
    {
        return $this->belongsTo('App\Models\Section');
    }


}
