<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public static $wrap = 'post';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $mediaItems = $this->getMedia('image');

        $photo = null;
        try {
            $photo = ! empty($mediaItems[0])
                ? $mediaItems[0]->getAvailableFullUrl(['small', 'medium', 'large'])
                : null;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'guid'        => $this->guid,
            'description' => $this->description,
            'thumbnail'   => $this->thumbnail,
            'image'       => $photo,
            'content'     => $this->content,
            'link'        => $this->link,
            'slug'        => $this->slug,
            'user_id'     => $this->user_id,
            'category_id' => $this->category_id,
            'date'        => $this->created_at,
            'deleted_at'  => $this->deleted_at,
        ];
    }
}
