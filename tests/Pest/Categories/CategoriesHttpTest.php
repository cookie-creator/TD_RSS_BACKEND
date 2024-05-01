<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\HttpAuthTestManager;
use Tests\Helpers\HttpCategoriesTestManager;
use Tests\Helpers\HttpPostsTestManager;
use Tests\Helpers\PostsTestManager;

uses(RefreshDatabase::class);

beforeEach(function () {

    [$this->user, $this->posts, $this->categories]
        = app(PostsTestManager::class)->createPostsAndCategories();

    $this->httpCategoriesTestManager = new HttpCategoriesTestManager($this);
    $this->httpPostsTestManager = new HttpPostsTestManager($this);
    $this->httpAuthTestManager = new HttpAuthTestManager($this);
});

test('user can retrieve categories', function () {

    $token = $this->httpAuthTestManager->auth($this->user);

    $categories = $this->httpCategoriesTestManager->getUserCategories($token);

})->group('http');

test('user can retrieve category by id', function () {
    $token = $this->httpAuthTestManager->auth($this->user);

    $categoryRetrive = $this->categories->random();
    $category = $this->httpCategoriesTestManager->getUserCategory($categoryRetrive, $token);

})->group('http');

test('user can update category by id', function () {
    $token = $this->httpAuthTestManager->auth($this->user);

    $name = 'new name';
    $categoryRetrive = $this->categories->random();
    $category = $this->httpCategoriesTestManager->updateCategory($categoryRetrive, $token, [
        'name' => $name
    ]);

    expect($category['category']['name'])->toEqual($name);
})->group('http');


test('user can create category', function () {
    $token = $this->httpAuthTestManager->auth($this->user);

    $name = 'new name';
    $category = $this->httpCategoriesTestManager->createCategory($token, [
        'name' => $name
    ]);

    expect($category['category']['name'])->toEqual($name);
})->group('http');

test('user can delete category', function () {
    $token = $this->httpAuthTestManager->auth($this->user);

    $categoryRetrive = $this->categories->random();

    $category = $this->httpCategoriesTestManager->deleteCategory($categoryRetrive, $token);

    $categoryExist = Category::find($categoryRetrive->id);
    expect($categoryExist)->toEqual(null);

})->group('http');
