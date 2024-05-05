<?php

namespace Tests\Helpers;

use App\Helpers\PostHelper;
use App\Managers\ParseRSS\ParsedPostsManager;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PostsTestManager
{
    public function createUser()
    {
        return User::factory()->create([
            'name'  => "test",
            'email' => "test@test.com",
            'password' => Hash::make("password"),
        ]);
    }

    public function createPostsAndCategories()
    {
        $user = User::find(1);
        $posts = collect();

        if (! $user) {
            $user = $this->createUser();
        }

        $categories = Category::all();
        if ($categories->count() == 0) {
            $categories = collect();

            $number_of_categories = 0;
            while ($number_of_categories++ < 10) {
                $categories->push($this->createCategory($user, fake()->name));
            }
        }

        $number_of_categories = 0;
        while ($number_of_categories++ < 10) {

            $title = fake()->text(100);
            $posts->push($this->createPost($user, $categories->random(),[
                'title' => $title,
                'guid' => Str::random(10),
                'description' => fake()->text(100),
                'thumbnail' => null,
                'content' => fake()->text(100),
                'link' => PostHelper::makeSlug($title),
                'slug' => PostHelper::makeSlug($title),
            ]));
        }

        return [$user, $posts, $categories];
    }

    /**
     * @param string $categoryTitle
     * @return Category
     */
    private function createCategory(User $user, string $categoryTitle)
    {
        $category = new Category();
        $category->name = $categoryTitle;
        $category->slug = PostHelper::makeSlug($categoryTitle);
        $category->user_id = $user->id;
        $category->save();

        return $category;
    }

    /**
     * @param User $user
     * @param Category $category
     * @param $data
     * @return Post
     */
    private function createPost(User $user, Category $category, $data)
    {
        $post = new Post();
        $post->title = $data['title'];
        $post->guid = $data['guid'];
        $post->description = $data['description'];
        $post->thumbnail = $data['thumbnail'];
        $post->content = $data['content'];
        $post->link = $data['link'];
        $post->slug = $data['slug'];
        $post->user_id = $user->id;
        $post->category_id = $category->id;
        $post->save();

        return $post;
    }
}
