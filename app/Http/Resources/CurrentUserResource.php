<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrentUserResource extends JsonResource
{
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'email_verified' => filter_var($this->email_verified_at, FILTER_VALIDATE_BOOLEAN),
            'token'          => $this->when(
                $this->additional['meta']['token'] ?? false,
                fn() => $this->additional['meta']['token']
            ),
            'created_at'     => $this->created_at,
            'has_password'   => (boolean) $this->has_password,
        ];
    }
}
