<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use function Pest\Faker\fake;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->baseUrl = env('APP_URL').'/api/v1';

    $this->user = User::factory()->create([
        'email' => "success.login@dev.test",
        'password' => Hash::make('password')
    ]);
});

test('user can successfully login', function () {

    $response = $this->postJson("$this->baseUrl/auth/login", [
        'email' => 'success.login@dev.test',
        'password' => 'password'
    ]);
    $response->assertOk();

    // Checking the validity of the returned token
    $token = $response->json('user.token');
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->getJson("$this->baseUrl/auth/me");
    $response->assertOk();

})->group('login');


test('cannot login with wrong password', function () {

    $response = $this->postJson("$this->baseUrl/auth/login", ['email' => 'success.login@dev.test', 'password' => 'wrongPassword']);

    $response->assertStatus(422);

})->group('login');

test('cannot login with invalid email', function () {

    $response = $this->postJson("$this->baseUrl/auth/login", ['email' => 'invalid.email', 'password' => 'password']);

    $response->assertStatus(422);

})->group('login');
