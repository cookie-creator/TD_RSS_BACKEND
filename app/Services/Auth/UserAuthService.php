<?php

namespace App\Services\Auth;

use App\Helpers\Http\ResponseCodes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CurrentUserResource;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserAuthService
{
    public function login(array $credentials): CurrentUserResource
    {
        $user = User::whereEmail($credentials['email'])->first();

        if (Hash::check($credentials['password'], $user->password ?? '')) {
            return $this->loginByEntity($user);
        }
        throw ValidationException::withMessages([
            'email'    => __('auth.failed'),
            'password' => __('auth.failed'),
        ]);
    }

    public function register(array $data)
    {
        $user = User::create([
            ...$data,
        ]);

        $token = $this->generateToken($user);

        return (new CurrentUserResource($user))->additional([
            'meta' => [
                'token' => $token,
            ],
        ]);
    }

    public function resetPassword(string $email): array
    {
        $status = Password::sendResetLink(['email' => $email]);

        return [
            'message'    => __($status),
            'statusCode' => $status === Password::RESET_LINK_SENT
                ? ResponseCodes::HTTP_OK
                : ResponseCodes::HTTP_UNPROCESSABLE,
        ];
    }

    public function newPassword(array $credentials): array
    {
        $handler = function (User $user, string $password) {
            $newPassword = ['password' => Hash::make($password), 'has_password' => true];
            $user->forceFill($newPassword)->setRememberToken(Str::random(60));
            $user->save();
        };

        $status = Password::reset($credentials, $handler);

        return [
            'message'    => __($status),
            'statusCode' => $status === Password::PASSWORD_RESET
                ? ResponseCodes::HTTP_OK
                : ResponseCodes::HTTP_UNPROCESSABLE,
        ];
    }

    public function loginByEntity(User $user): CurrentUserResource
    {
        $token = $this->generateToken($user);

        return (new CurrentUserResource($user))->additional([
            'meta' => [
                'token' => $token,
            ],
        ]);
    }


    private function generateToken(User $user, bool $clearOld = false): string
    {
        if ($clearOld) {
            $user->tokens()->delete();
        }

        $token = $user->createToken(request()->ip());

        return $token->plainTextToken;
    }
}
