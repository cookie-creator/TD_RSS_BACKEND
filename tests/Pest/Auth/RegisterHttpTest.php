<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->baseUrl = env('APP_URL').'/api/v1';

    $this->user = User::factory()->create([
        'email' => "success.login@dev.test",
        'password' => Hash::make('password')
    ]);
});


test('user can register', function () {

    $response = $this->postJson("$this->baseUrl/auth/register", [
        'email'                 => 'test.register@dev.test',
        'password'              => 'password',
        'password_confirmation' => 'password',
        'name'                  => 'Test',
    ]);
    $response->assertOk();

})->group('register');
