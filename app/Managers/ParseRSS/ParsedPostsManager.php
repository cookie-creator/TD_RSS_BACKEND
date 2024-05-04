<?php

namespace App\Managers\ParseRSS;

use Exception;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Helpers\PostHelper;
use Illuminate\Support\Collection;
use App\Notifications\NewPostsWasImportedFromFeed;

class ParsedPostsManager
{
    private User $user;
    /**
     * @param Collection $parsedPosts
     * @return void
     */
    public function storePosts(Collection $parsedPosts)
    {
        $this->user = User::find(1);
        $cntPostsAdded = 0;

        foreach ($parsedPosts as $parsedPost)
        {
            // checkUnique and check if we have description
            if ($this->isUniqueGuid($parsedPost['guid']) && $parsedPost['description'] !== '')
            {
                try {
                    $category = $this->addCategory($parsedPost['category']);

                    $post = new Post();
                    $post->title = $parsedPost['title'];
                    $post->guid = $parsedPost['guid'];
                    $post->description = $parsedPost['description'];
                    $post->thumbnail = $parsedPost['thumbnail'];
                    $post->content = $parsedPost['content'];
                    $post->link = $parsedPost['link'];
                    $post->slug = $parsedPost['slug'];
                    $post->user_id = $this->user->id;
                    $post->category_id = $category->id;
                    $post->save();

                    $this->uploadImage($post, $parsedPost['image']);

                    $cntPostsAdded++;
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }
        }
        if ($cntPostsAdded > 0) {
            $this->user->notify(new NewPostsWasImportedFromFeed($this->user, $cntPostsAdded));
        }
    }

    /**
     * @param string $guid
     */
    public function isUniqueGuid(string $guid)
    {
        return (Post::where('guid',$guid)->first()) ? false : true;
    }

    /**
     * @param string $categoryTitle
     * @return Category
     */
    private function addCategory(string $categoryTitle)
    {
        $category = Category::where('name', $categoryTitle)->first();
        if (!$category) {
            $category = new Category();
            $category->name = $categoryTitle;
            $category->slug = PostHelper::makeSlug($categoryTitle);
            $category->user_id = $this->user->id;
            $category->save();
        }
        return $category;
    }

    /**
     * @param Post $post
     * @param $imageUrl
     * @return void
     */
    public function uploadImage(Post $post, $imageUrl)
    {
        if ($imageUrl) {
            try {
            $imageContents = file_get_contents($imageUrl);
            $imageName = last(explode('/', $imageUrl));

            $post->clearMediaCollection('image');
            $post->addMediaFromString($imageContents)
                ->usingFileName($imageName)
                ->toMediaCollection('image');
            } catch (Exception $e) {
            }
        }
    }

    public function cutFilename($string)
    {

    }

    /**
     * @param string $string
     * @return string
     */
//    public function makeSlug(string $string): string
//    {
//        $slug = strtolower($string);
//        $slug = preg_replace('/[^a-z0-9-_\s]/', '', $slug);
//        $slug = str_replace(' ', '-', $slug);
//        $slug = preg_replace('/-+/', '-', $slug);
//        $slug = trim($slug, '-');
//
//        return $slug;
//    }
}
