<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PostService
{
    /**
     * @param User $user
     * @return Collection
     */
    public function getUserPosts(User $user): Collection
    {
        return Post::where('user_id', $user->id)->get();
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserPostsWithPagination(User $user): LengthAwarePaginator
    {
        return Post::where('user_id', $user->id)->paginate(10);
    }

    /**
     * @param User $user
     * @param int $postId
     * @return Post|null
     */
    public function getUserPost(User $user, int $postId): Post|null
    {
        return Post::where([
            'id' => $postId,
            'user_id' => $user->id,
        ])->first();
    }
}
