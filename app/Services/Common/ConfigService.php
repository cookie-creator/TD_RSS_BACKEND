<?php

namespace App\Services\Common;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class ConfigService
{
    public static function get(string $key): mixed
    {
        //check if this config and value exists
        if (! Config::has($key)) {
            $message = "Config key $key does not exist";
            Log::error($message);
            throw new \LogicException($message);
        }

        //check if given value refer to actual value
        if (! Config::get($key)) {
            $message = "Key $key is refers to an empty value";
            Log::error($message);
            throw new \LogicException($message);
        }

        return Config::get($key);
    }
}
