<?php

namespace App\Services\FeedRSS;

use App\Adapter\APIAdapter\WebAPI;
use App\Managers\ParseRSS\ParsedPostsManager;
use App\Services\ParseRSS\ParsePostService;

class FeedRSSService
{
    public static function start()
    {
        $feed = WebAPI::getFeed();

        $parser = new ParsePostService();
        $posts = $parser->parsePosts($feed);

        $manager = new ParsedPostsManager();
        $manager->storePosts($posts);
    }
}
