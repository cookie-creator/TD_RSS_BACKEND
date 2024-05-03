<?php

namespace App\Managers\Category;

use App\Helpers\PostHelper;
use App\Models\Category;
use App\Models\Post;

class CategoryManager
{
    public static function create(array $data)
    {
        $slug = ($data['slug']) ? $data['slug'] : PostHelper::makeSlug($data['name']);
        $category = Category::create(array_merge($data,[
            'slug' => $slug,
            'user_id' => auth()->id(),
        ]));
        return $category;
    }

    public static function update(int $categoryId, array $data)
    {
        $slug = ($data['slug']) ? $data['slug'] : PostHelper::makeSlug($data['name']);
        $category = Category::find($categoryId);
        $category->update(array_merge($data,[
            'slug' => $slug,
        ]));

        return $category;
    }

    /**
     * @param int $categoryId
     * @return mixed
     */
    public static function delete(int $categoryId)
    {
        Post::where('category_id', $categoryId)->update(['category_id' => 0]);
        return Category::find($categoryId)->delete();
    }
}
