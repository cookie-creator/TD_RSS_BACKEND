<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryService
{
    /**
     * @param User $user
     * @return Collection
     */
    public function getUserCategories(User $user): Collection
    {
        return Category::where('user_id', $user->id)->get();
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserCategoriesWithPagination(User $user): LengthAwarePaginator
    {
        return Category::where('user_id', $user->id)->paginate(10);
    }

    /**
     * @param User $user
     * @param int $postId
     * @return Post|null
     */
    public function getUserCategory(User $user, int $postId): Category|null
    {
        return Category::where([
            'id' => $postId,
            'user_id' => $user->id,
        ])->first();
    }
}
