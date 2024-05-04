<?php

namespace Tests\Helpers;

class HttpAuthTestManager
{

    /**
     * @param $test pest
     */
    public function __construct($test)
    {
        $this->test = $test;
        $this->baseUrl = '/api/v1';
        $this->token = false;
    }

    /**
     * @param $user
     * @return string token
     */
    public function auth($user): string
    {
        $response = $this->test->postJson("$this->baseUrl/auth/login", [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertOk();
        return $this->token = $response->json('user.token');
    }
}
