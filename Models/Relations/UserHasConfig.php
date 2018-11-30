<?php

namespace Yoogr\Foundation\Models\Relations;

use Yoogr\Foundation\Models\UserConfig;

trait UserHasConfig
{

    public function configs()
    {
        return $this->hasMany(UserConfig::class, 'user_id', 'id');
    }

    public function config($key = null, $default = null)
    {
        // set
        if(is_array($key)) {
            $group = is_null($default) ? 0 : $default;
            foreach ($key as $k=>$v) {
                $this->configs()->updateOrCreate(['name'=>$k], ['value'=>$v, 'group'=>$group]);
            }
            return true;
        }

        // get all configs
        if(is_null($key)) {
            return $this->configs;
        }

        // get single config
        $value = $this->configs()->where('name', $key)->value('value');
        return (is_null($value) && is_null($default) === false) ? $default : $value;
    }
}
