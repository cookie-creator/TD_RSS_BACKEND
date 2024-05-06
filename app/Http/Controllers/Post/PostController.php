<?php

namespace App\Http\Controllers\Post;

use App\Helpers\Http\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\NotificationRequest;
use App\Http\Requests\Post\PostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Managers\Post\PostManager;
use App\Models\Post;
use App\Services\Post\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PostService $postService)
    {
        $postQuery = $postService->getUserPostsQueryByFilter(auth()->user(), $request->all());

        $posts = $request->boolean('all') ? $postQuery->get() : $postQuery->paginate($request->get('perPage', 15));

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
    public function store(StorePostRequest $request, PostManager $postManager)
    {
        $post = $postManager->create($request->validated());
        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show($postId, PostService $postService) {
        $post = $postService->getUserPost(auth()->user(), $postId);
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
    public function update($postId, UpdatePostRequest $request, PostManager $postManager)
    {
        // $this->authorize('update', [auth()->user(), $post]);

        $post = $postManager->update($postId, $request->validated());

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($postId, PostManager $postManager)
    {
        // $this->authorize('delete', [auth()->user(), $post]);

        $status = $postManager->delete($postId, auth()->user());

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
