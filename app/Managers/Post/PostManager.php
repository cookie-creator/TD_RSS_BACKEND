<?php

namespace App\Managers\Post;

use App\Helpers\PostHelper;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class PostManager
{
    public static function create(array $data)
    {
        $title = $data['title'];
        $post = Post::create(array_merge($data,[
            'link' => PostHelper::makeSlug($title),
            'slug' => PostHelper::makeSlug($title),
            'guid' => Str::random(10),
            'user_id' => auth()->id(),
        ]));
        return $post;
    }

    public static function update(Post $post, array $data)
    {
//        $post->title = $data['title'];
//        $post->content = $data['content'];
//        $post->link = $data['link'];
//        $post->slug = $data['slug'];
//        $post->category_id = $data['category_id'];
        $post->update($data);

        return $post;
    }

    public static function delete(User $user, Post $post)
    {
        if ($post->user_id === $user->id) {
            return $post->delete();
        }
        return false;
    }
}
