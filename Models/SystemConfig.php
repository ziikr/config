<?php

namespace Ziikr\Config\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{

    protected $table = 'system_configs';


    protected $fillable = ['name', 'value', 'value_type', 'configurable', 'display_name'];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(config('util.user.model'), 'user_id', 'id');
    }
}