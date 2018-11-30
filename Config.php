<?php

namespace Ziikr\Config;

use Ziikr\Config\Models\UserConfig;
use Ziikr\Config\Models\SystemConfig;

class Config
{

    protected $user = null;

    public function __construct($user = null)
    {
        $this->user($user);
    }

    public function user($user)
    {
        $this->user = $user;
        return $this;
    }

    public function set($key, $value, $group = null)
    {
        if($this->user) {
            (new UserConfigRepository($this->user))->updateOrCreate($key, $value, $group);
        } else {
            SystemConfigRepository::instance()->updateOrCreate($key, $value, $group);
        }
    }

    public function get($key, $default = null)
    {
        if($this->user) {
            $value = (new UserConfigRepository($this->user))->value($key);
        } else {
            $value = SystemConfigRepository::instance()->value($key);
        }
        if(is_null($value) && is_null($default) === false)  return $default;

        return $value;
    }

    public function increment($key, $step = 1)
    {
        if($this->user) {
            (new UserConfigRepository($this->user))->increment($key, $step);
        } else {
            SystemConfigRepository::instance()->increment($key, $step);
        }
    }

    public function flush($key)
    {
        if($this->user) {
            (new UserConfigRepository($this->user))->flush($key);
        } else {
            SystemConfigRepository::instance()->flush($key);
        }
    }
}
