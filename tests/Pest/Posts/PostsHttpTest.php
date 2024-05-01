<?php

use App\Models\Category;
use App\Models\Post;
use App\Services\FeedRSS\FeedRSSService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\HttpAuthTestManager;
use Tests\Helpers\HttpPostsTestManager;
use Tests\Helpers\PostsTestManager;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->feedRSSService = new FeedRSSService();
    // $this->user = app(PostsTestManager::class)->createUser();
    [$this->user, $this->posts, $this->categories]
        = app(PostsTestManager::class)->createPostsAndCategories();

    $this->httpPostsTestManager = new HttpPostsTestManager($this);
    $this->httpAuthTestManager = new HttpAuthTestManager($this);
});

test('user can retrieve posts', function () {

    $token = $this->httpAuthTestManager->auth($this->user);

    $post = $this->httpPostsTestManager->getUserPosts($token);

    expect(count($post['data']))->toEqual(10);

})->group('test');

test('user can retrieve post by id', function () {

    $token = $this->httpAuthTestManager->auth($this->user);

    $postRetrive = $this->posts->random();
    $post = $this->httpPostsTestManager->getUserPost($postRetrive, $token);

    expect($postRetrive->guid)->toEqual($post['post']['guid']);

})->group('posts');

test('user can update post by id', function () {

    $token = $this->httpAuthTestManager->auth($this->user);

    $title = 'new name';
    $postRetrive = $this->posts->random();
    $post = $this->httpPostsTestManager->updatePost($postRetrive, $token, [
        'title' => $title,
        'category_id' => $this->categories->random()->id,
    ]);
    expect($post['post']['title'])->toEqual($title);

})->group('posts');

test('user can create new post', function () {

    $token = $this->httpAuthTestManager->auth($this->user);

    $title = 'new name';
    $post = $this->httpPostsTestManager->createPost($token, [
        'title' => $title,
        'category_id' => $this->categories->random()->id
    ]);
    expect($post['post']['title'])->toEqual($title);
})->group('post');

test('user can delete post', function () {

    $token = $this->httpAuthTestManager->auth($this->user);

    $postRetrive = $this->posts->random();

    $post = $this->httpPostsTestManager->deletePost($postRetrive, $token);

    $postExist = Post::find($postRetrive->id);
    expect($postExist)->toEqual(null);

})->group('post');
