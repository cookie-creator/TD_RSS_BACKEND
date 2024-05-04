<?php

namespace App\Managers\Post;

use App\Helpers\PostHelper;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class PostManager
{
    /**
     * @param array $data
     * @return Post
     */
    public function create(array $data): Post
    {
        $title = $data['title'];
        $post = Post::create(array_merge($data,[
            'link' => PostHelper::makeSlug($title),
            'slug' => PostHelper::makeSlug($title),
            'guid' => Str::random(10),
            'updated_at' => $data['date'],
            'user_id' => auth()->id(),
        ]));
        if (request()->has('image')) {
            $this->saveImage($post, $data['image']);
        }
        return $post;
    }

    /**
     * @param int $postId
     * @param array $data
     * @return mixed
     */
    public function update(int $postId, array $data)
    {
        $slug = ($data['slug']) ? $data['slug'] : PostHelper::makeSlug($data['title']);
        $post = Post::find($postId);
        $post->update(array_merge($data,[
            'slug' => $slug,
            'updated_at' => $data['date']
        ]));
        if (request()->has('image')) {
            $this->saveImage($post, $data['image']);
        }
        return $post;
    }

    /**
     * @param User $user
     * @param Post $post
     * @return bool|null
     */
    public function delete(int $postId, User $user)
    {
        $post = Post::find($postId);
        if ($post && $post->user_id === $user->id) {
            return $post->delete();
        }
        return false;
    }

    /**
     * @param Post|Category $entity
     * @param $imageStr
     * @return void
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function saveImage(Post|Category $entity, $imageStr)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $imageStr, $type)) {
            $image = substr($imageStr, strpos($imageStr, ',') + 1);

            $type = strtolower($type[1]); // jpg, png, gif
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $image = str_replace(' ', '+', $image);
            $imageContent = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }

            $imageName = Str::random() . '.' . $type;

            $entity->clearMediaCollection('image');
            $entity->addMediaFromString($imageContent)
                ->usingFileName($imageName)
                ->toMediaCollection('image');
        }
    }
}
