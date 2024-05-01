<?php

namespace App\Http\Controllers\Post;

use App\Helpers\Http\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Managers\Post\PostManager;
use App\Models\Post;
use App\Services\Post\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PostService $postService)
    {
        $posts = $postService->getUserPostsWithPagination(auth()->user());

        return PostResource::collection($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = PostManager::create($request->validated());

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        Post $post,
        PostService $postService
    ) {
//        try {
//        $this->authorize('view', [auth()->user(), $post]);
//        } catch (\Exception $e) {
//            dump($e->getMessage());
//        }
        $post = $postService->getUserPost(auth()->user(), $post->id);

        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Post $post, UpdatePostRequest $request)
    {
        // $this->authorize('update', [auth()->user(), $post]);

        $post = PostManager::update($post, $request->validated());

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // $this->authorize('delete', [auth()->user(), $post]);

        $status = PostManager::delete(auth()->user(), $post);

        if ($status) {
            return Response::json(
                ResponseCodes::HTTP_SUCCESS
            );
        }
        return Response::json(
            ResponseCodes::HTTP_FORBIDDEN
        );
    }
}
