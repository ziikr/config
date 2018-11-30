<?php

namespace Ziikr\Config\Models;

use Illuminate\Database\Eloquent\Model;

class UserConfig extends Model
{

    protected $table = 'user_configs';

    protected $fillable = ['user_id', 'name', 'value', 'group'];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id', 'id');
    }
}