<?php

namespace App\Services\FeedRSS;

use App\Adapter\APIAdapter\WebAPI;
use App\Managers\ParseRSS\ParsedPostsManager;

class FeedRSSService
{
    public static function start()
    {
        $feed = WebAPI::getFeed();

        $parser = new PostParserService();
        $posts = $parser->parsePosts($feed);

        $manager = new ParsedPostsManager();
        $manager->storePosts($posts);
    }
}
