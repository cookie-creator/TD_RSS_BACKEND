<?php

namespace App\Adapter\APIAdapter;

use Illuminate\Support\Facades\Http;

class WebAPI
{
    public static function getFeed()
    {
        $response = Http::get(env('FEEDRSS_URL'));

        $contents = $response->getBody()->getContents();

        return $contents;
    }
}
