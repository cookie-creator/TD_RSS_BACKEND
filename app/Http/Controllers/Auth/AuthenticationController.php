<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\Http\ResponseCodes;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\UserAuthService;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\CurrentUserResource;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function __construct(private UserAuthService $userAuthService)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userAuthService->login($data);

        return Response::json([
            'user' => $user,
        ], ResponseCodes::HTTP_SUCCESS);
    }

    public function me(Request $request): CurrentUserResource
    {
        $user = $request->user();

        return new CurrentUserResource($user);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userAuthService->register($request->validated());

        return Response::json([
            'user' => $user,
        ], ResponseCodes::HTTP_SUCCESS);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $result = $this->userAuthService->resetPassword($request->validated('email'));

        if ($result['statusCode'] == ResponseCodes::HTTP_OK) {
            return Response::json([
                'message' => __($result['message']),
            ], $result['statusCode']);
        }

        return throw ValidationException::withMessages([
            'email' => __($result['message']),
        ]);
    }

    public function new(NewPasswordRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $result = $this->userAuthService->newPassword($credentials);

        return Response::json([
            'message' => __($result['message']),
        ], $result['statusCode']);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();

    }
}
