<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test Register.
     */
    public function testRegisterSuccess(): void
    {
        $data = [
            'name' => 'Jhon Doe',
            'email' => 'jhon@mail.com',
            'role' => 'Admin',
            'password' => 'rahasia'
        ];

        $this->post('/register', $data);

        $this->assertDatabaseHas('users', [
            'name' => 'Jhon Doe',
            'email' => 'jhon@mail.com',
            'role' => 'Admin'
        ]);
    }
}
