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
        return Post::where('user_id', $user->id)->latest()->paginate(10);
    }

    public function getUserPostsQueryByFilter(User $user, $attributes)
    {
        $fromDate = $attributes['from_date'] ?? false;
        $toDate = $attributes['to_date'] ?? false;
        $search = $attributes['search'] ?? false;
        $category_id = $attributes['category_id'] ?? false;

        $postQuery = Post::query()
            ->where('user_id', $user->id)
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->when($category_id, function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
            })
            ->latest();
        return $postQuery;
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
