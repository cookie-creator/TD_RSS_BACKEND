<?php

namespace Tests\Helpers;

use App\Models\Category;

class HttpCategoriesTestManager
{
    /**
     * @param $test
     */
    public function __construct($test)
    {
        $this->test = $test;
        $this->baseUrl = '/api/v1';
    }

    public function getUserCategories(string $token)
    {
        $url = '/categories';

        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson($this->baseUrl.$url);

        $response->assertStatus(200);
        $categoryData = $response->json();

        return $categoryData;
    }

    public function getUserCategory(Category $category, string $token)
    {
        $url = '/categories/' . $category->id;

        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson($this->baseUrl.$url);

        $response->assertStatus(200);
        $categoryData = $response->json();

        return $categoryData;
    }

    public function createCategory(string $token, array $categoryData)
    {
        $url = '/categories';

        $name = $categoryData['name'] ?? fake()->text(100);

        $data = [
            'name' => $name,
        ];

        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson($this->baseUrl.$url, $data);

        $response->assertStatus(201);
        $categoryData = $response->json();

        return $categoryData;
    }

    public function updateCategory(Category $category, string $token, array $categoryData)
    {
        $url = '/categories/' . $category->id;

        $name = $categoryData['name'] ?? fake()->text(100);

        $data = [
            'name' => $name,
        ];

        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson($this->baseUrl.$url, $data);

        $response->assertStatus(200);
        $categoryData = $response->json();

        return $categoryData;
    }

    public function deleteCategory(Category $category, string $token)
    {
        $url = '/categories/' . $category->id;
        $response = $this->test->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson($this->baseUrl.$url);

        $response->assertStatus(200);
        $categoryData = $response->json();

        return $categoryData;
    }
}
