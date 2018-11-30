<?php

namespace Ziikr\Config;

use Illuminate\Contracts\Auth\Authenticatable;
use Ziikr\Config\Models\UserConfig;

class UserConfigRepository
{

    protected $user = null;
    protected $userId = null;

    public function __construct($user)
    {
        $this->user($user);
    }

    protected function user($user)
    {
        if($user instanceof Authenticatable) {
            $this->user = $user;
            $this->userId = $user->getAuthIdentifier();
        } else if(is_numeric($user) && $user > 0) {
            $this->userId = $user;
        } else {
            throw new \Exception('Invalid param for user config cache');
        }
    }

    public function value($name)
    {
        $ckey = $this->cacheKey($name);
        if (\Cache::has($ckey)) {
            return \Cache::get($ckey);
        }

        return \Cache::remember($ckey, 36000, function() use ($name) {
            return UserConfig::where(['user_id'=>$this->userId, 'name'=>$name])->value('value');
        });
    }

    public function update($name, $value)
    {
        UserConfig::where(['user_id'=>$this->userId, 'name'=>$name])->update(['value'=>$value]);

        $this->flush($name);
    }

    public function increment($name, $step = 1)
    {
        UserConfig::where(['user_id'=>$this->userId, 'name'=>$name])->increment('value', $step);

        $this->flush($name);
    }

    public function updateOrCreate($name, $value, $group = null)
    {
        $attrs = ['user_id'=>$this->userId, 'name'=>$name];
        if($group > 0) {
            $attrs['group'] = $group;
        }

        UserConfig::updateOrCreate($attrs, ['value'=>$value]);

        $this->flush($name);
    }

    protected function cacheKey(string $name)
    {
        return "app:config:user:{$this->userId}:{$name}";
    }

    protected function tags()
    {
        return ['user_config'];
    }

    public function flush($name)
    {
        \Cache::forget($this->cacheKey($name));
    }
}
