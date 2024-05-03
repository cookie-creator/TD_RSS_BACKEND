<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Services\FeedRSS\FeedRSSService;
use App\Services\Post\PostService;

class TestController extends Controller
{
    public function index(FeedRSSService $feedRSSService, PostService $postService)
    {
        $feedRSSService::start();

        //$posts = $postService->getUserPostsQueryByFilter(User::find(1));

        $posts = Post::all();
        foreach ($posts as $post) {
            $post->title = $post->id . ' : ' . $post->title;
            $post->update();
        }
//        foreach ($posts as $post) {
//            $photo = null;
//            $media = $post->getMedia('image');
//            //dump($media);
//
//            try {
//                $photo = ! empty($media[0])
//                    ? $media[0]->getAvailableFullUrl(['small', 'medium', 'large'])
//                    : null;
//
//                dump($photo);
//            } catch (\Exception $e) {
//                \Log::error($e->getMessage());
//            }
//
//
//        }

        return PostResource::collection($posts);
    }
}
