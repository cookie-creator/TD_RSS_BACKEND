<?php

namespace App\Providers;

use App\Helpers\Http\ResponseCodes;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('resource', function (JsonResource $resource, $status = 200) {
            return Response::json([$resource::$wrap => $resource], $status);
        });

        Response::macro('success', function ($data) {
            return Response::json($data);
        });

        Response::macro('created', function ($data) {
            return Response::json($data, ResponseCodes::HTTP_CREATED);
        });

        Response::macro('error', function ($message, $status = 400) {
            return Response::json([
                'data' => [
                    'errors'  => true,
                    'message' => $message,
                ]
            ], $status);
        });
    }
}
