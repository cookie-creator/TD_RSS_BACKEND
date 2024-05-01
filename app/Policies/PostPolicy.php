<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use \Illuminate\Http\Response;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function view(User $user, Post $post): bool
    {
        dd([
            $user->id,
            $post->user_id
        ]);

        return $user->id === $post->user_id;
    }

    public function update(User $user, Post $project): Response|bool
    {
        if ($user->id !== $project->user_id) {
            return false;
        }
        return true;
    }

    public function delete(User $user, Post $project): Response|bool
    {
        if ($user->id !== $project->user_id) {
            return false;
        }
        return true;
    }
}
