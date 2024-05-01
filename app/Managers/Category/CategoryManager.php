<?php

namespace App\Managers\Category;

use App\Helpers\PostHelper;
use App\Models\Category;
use App\Models\Post;

class CategoryManager
{
    public static function create(array $data)
    {
        $name = $data['name'];
        $category = Category::create(array_merge($data,[
            'slug' => PostHelper::makeSlug($name),
            'user_id' => auth()->id(),
        ]));
        return $category;
    }

    public static function update(Category $category, array $data)
    {
        $category->update(array_merge($data,[
            'slug' => PostHelper::makeSlug($data['name']),
        ]));

        return $category;
    }

    public static function delete(Category $category)
    {
        Post::where('category_id', $category->id)->update(['category_id' => 0]);
        return $category->delete();
    }
}
