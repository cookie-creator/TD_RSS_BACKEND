<?php

namespace Tests\Helpers;

use App\Helpers\PostHelper;
use App\Managers\ParseRSS\ParsedPostsManager;
use App\Models\Post;

class HttpPostsTestManager
{
    /**
     * @param $test
     */
    public function __construct($test)
    {
        $this->test = $test;
        $this->baseUrl = '/api/v1';
    }

    public function getUserPosts(string $token)
    {
        $url = '/posts';

        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson($this->baseUrl.$url);

        $response->assertStatus(200);
        $postData = $response->json();

        return $postData;
    }

    public function getUserPost(Post $post, string $token)
    {
        $url = '/posts/' . $post->id;

        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson($this->baseUrl.$url);

        $response->assertStatus(200);
        $postData = $response->json();

        return $postData;
    }

    public function createPost(string $token, array $postData)
    {
        $url = '/posts';

        $title = $postData['title'] ?? fake()->text(100);

        $data = [
            'title' => $title,
            'description' => $postData['description'] ?? fake()->name,
            'content' => $postData['content'] ?? fake()->name,
            'link' => $postData['link'] ?? fake()->name,
            'slug' => $postData['slug'] ?? fake()->name,
            'category_id' => $postData['category_id'],
        ];

        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson($this->baseUrl.$url, $data);

        $response->assertStatus(201);
        $postData = $response->json();

        return $postData;
    }

    public function updatePost(Post $post, string $token, array $postData)
    {
        $url = '/posts/' . $post->id;

        $title = $postData['title'] ?? fake()->text(100);

        $data = [
            'title' => $title,
            'description' => $postData['description'] ?? fake()->text(100),
            'content' => $postData['content'] ?? fake()->text(100),
            'link' => $postData['link'] ?? PostHelper::makeSlug($title),
            'slug' => $postData['slug'] ?? PostHelper::makeSlug($title),
            'category_id' => $postData['category_id'],
        ];

        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson($this->baseUrl.$url, $data);

        $response->assertStatus(200);
        $postData = $response->json();

        return $postData;
    }

    public function deletePost(Post $post, string $token)
    {
        $url = '/posts/' . $post->id;
        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson($this->baseUrl.$url);

        $response->assertStatus(200);
        $postData = $response->json();

        return $postData;
    }

}
