<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    public function test_can_register()
    {
        $this->withExceptionHandling();
        $userData = [
            'fullname' => "test fullname",
            'email' => "test email",
            'stage_name' => "test stage_name",
            'password' => "password",
            'role' => 1,
        ];

        $this->withExceptionHandling();
        $this->post(route('artist_register'), $userData)->assertStatus(201);
    }
}
