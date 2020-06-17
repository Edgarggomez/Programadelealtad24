<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientTest extends TestCase
{

    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();
    }

    public function testUnauthenticatedUserRedirectsToLoggin() {
        
        $input = $this->dummyUser();

        $response = $this->post(route('users.store'), $input);
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('users', ['email' => $input['email']]);
    }
    
}
