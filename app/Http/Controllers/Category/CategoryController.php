<?php

namespace App\Http\Controllers\Category;

use App\Helpers\Http\ResponseCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Post\PostResource;
use App\Managers\Category\CategoryManager;
use App\Managers\Post\PostManager;
use App\Models\Category;
use App\Models\Post;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryService $categoryService)
    {
        $categories = $categoryService->getUserCategories(auth()->user());

        return CategoryResource::collection($categories);
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
    public function store(StoreCategoryRequest $request)
    {
        $category = CategoryManager::create($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show($categoryId, CategoryService $categoryService) {
        // $this->authorize('view', [auth()->user(), $post]);
        $category = $categoryService->getUserCategory(auth()->user(), $categoryId);

        return new CategoryResource($category);
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
    public function update($categoryId, UpdateCategoryRequest $request, CategoryManager $categoryManager)
    {
        // $this->authorize('update', [auth()->user(), $post]);
        $category = $categoryManager->update($categoryId, $request->validated());
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categoryId)
    {
        // $this->authorize('delete', [auth()->user(), $post]);

        $status = CategoryManager::delete($categoryId);

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
