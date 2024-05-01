<?php

use App\Http\Controllers\Post\PostController;

Route::apiResource('posts', PostController::class);

//Route::apiResources([
//    'projects'       => PostController::class,
//]);

// php artisan make:controller PostController --api --resource --requests

