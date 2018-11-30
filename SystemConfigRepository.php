<?php

namespace Ziikr\Config;

use Ziikr\Config\Models\SystemConfig;

class SystemConfigRepository
{

    public static function instance()
    {
        return new static();
    }

    public function value($name)
    {
        $ckey = $this->cacheKey($name);
        if (\Cache::has($ckey)) {
            return \Cache::get($ckey);
        }

        $value = SystemConfig::where('name', $name)->value('value');

        \Cache::forever($ckey, $value);

        return $value;
    }

    public function update($name, $value)
    {
        SystemConfig::where('name', $name)->update(['value'=>$value]);

        $this->flush($name);
    }

    public function increment($name, $step = 1)
    {
        SystemConfig::where('name', $name)->increment('value', $step);

        $this->flush($name);
    }

    public function updateOrCreate($name, $value, $group = null)
    {
        $attrs = ['name'=>$name];
        if($group > 0) {
            $attrs['group'] = $group;
        }

        SystemConfig::updateOrCreate($attrs, ['value'=>$value]);

        $this->flush($name);
    }

    protected function cacheKey($name)
    {
        return "app:config:{$name}";
    }

    protected function tags()
    {
        return ['config'];
    }

    public function flush($name)
    {
        \Cache::forget($this->cacheKey($name));
    }
}
