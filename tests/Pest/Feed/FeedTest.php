<?php

use App\Services\FeedRSS\FeedRSSService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\PostsTestManager;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->feedRSSService = new FeedRSSService();
    // $this->user = app(PostsTestManager::class)->createUser();
    [$this->user, $this->posts, $this->categories]
        = app(PostsTestManager::class)->createPostsAndCategories();
});

test('service can pull posts from feed', function () {


})->group('feed');

