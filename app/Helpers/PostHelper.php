<?php

namespace App\Helpers;

class PostHelper
{
    /**
     * @param string $string
     * @return string
     */
    public static function makeSlug(string $string): string
    {
        $slug = strtolower($string);
        $slug = preg_replace('/[^a-z0-9-_\s]/', '', $slug);
        $slug = str_replace(' ', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
