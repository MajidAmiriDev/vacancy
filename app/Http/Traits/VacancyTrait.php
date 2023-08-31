<?php
namespace App\Http\Traits;


use Illuminate\Support\Facades\Cache;

trait VacancyTrait
{


    public function setInCache($key, $value, $expire_time = 10)
    {
        Cache::put($key, $value, $expire_time);
    }

    public function getFromCache($key)
    {
        return Cache::get($key);
    }

    public function checkCache($key) : bool
    {
        if (Cache::has($key)) {
            return true;
        }

        return false;
    }

    public function forgetFromCache($key) : bool
    {
        Cache::forget($key);

        return true;
    }
}
