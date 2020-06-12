<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function testOperatorUserStoreFail() {
        $user = factory('App\User')->state('operador')->create();

        $this->actingAs($user, 'web');

        $input = $this->dummyUser();

        $response = $this->post(route('users.store'), $input);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => $input['email']]);
    }

    public function testGerenteUserStoreFail() {
        $user = factory('App\User')->state('gerente')->create();

        $this->actingAs($user, 'web');

        $input = $this->dummyUser();

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('users.store'), $input);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => $input['email']]);
    }


    public function testAdminUserStoreSuccess() {
        $user = factory('App\User')->state('admin')->create();

        $this->actingAs($user, 'web');

        $input = $this->dummyUser();

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('users.store'), $input);
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => $input['email']]);
    }

    /**
     * @dataProvider providerTestUserValidationRules
     */
    public function testUserValidationRules(array $input)
    {
        $user = factory('App\User')->state('admin')->create();

        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('users.store'), $input);
        $response->assertJson(['message' => 'The given data was invalid.']);
        $this->assertDatabaseMissing('users', ['email' => $input['email']]);
    }

    public function providerTestUserValidationRules()
    {
        return array (
            array(['name' => '', 'email' => 'uno@gmail.com', 'password' => '12345678', 
                    'password_confirmation' => '12345678', 'role' => 'operador', 'status' => '1']),
            array(['name' => 'Jonatah Jeferson', 'email' => '', 'password' => '12345678', 
                    'password_confirmation' => '12345678', 'role' => 'operador', 'status' => '1']),
            array(['name' => 'Jonatah Jeferson', 'email' => 'wrong_email', 'password' => '12345678', 
                    'password_confirmation' => '12345678', 'role' => 'operador', 'status' => '1']),
            array(['name' => 'Jonatah Jeferson', 'email' => 'dos@email.com', 'password' => '', 
                    'password_confirmation' => '12345678', 'role' => 'operador', 'status' => '1']),
            array(['name' => 'Jonatah Jeferson', 'email' => 'dos@email.com', 'password' => '12345678', 
                    'password_confirmation' => '6846213', 'role' => 'operador', 'status' => '1']),
            array(['name' => 'Jonatah Jeferson', 'email' => 'dos@email.com', 'password' => '12345678', 
                    'password_confirmation' => '12345678', 'role' => '', 'status' => '1']),
            array(['name' => 'Jonatah Jeferson', 'email' => 'dos@email.com', 'password' => '12345678', 
                    'password_confirmation' => '12345678', 'role' => 'wrong_role', 'status' => '1']),
            array(['name' => 'Jonatah Jeferson', 'email' => 'dos@email.com', 'password' => '12345678', 
                    'password_confirmation' => '12345678', 'role' => 'operador', 'status' => '']),
            array(['name' => 'Jonatah Jeferson', 'email' => 'dos@email.com', 'password' => '12345678', 
                    'password_confirmation' => '12345678', 'role' => 'operador', 'status' => '6'])
        );
    }

    public function testUserValidationDuplicatedEmailFail()
    {
        $user = factory('App\User')->state('admin')->create();

        $this->actingAs($user, 'web');

        $input = $this->dummyUser();

        $input['email'] = $user->email;

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('users.store'), $input);
        $response->assertJson(['message' => 'The given data was invalid.']);
        
    }


    public function testUserUpdateSuccess()
    {
        $admin = factory('App\User')->state('admin')->create();
        $user = factory('App\User')->state('operador')->create();

        $this->actingAs($admin, 'web');

        $input = $user->toArray();

        $input['name'] = 'New Name';
        $input['password'] = '12345678';
        $input['password_confirmation'] = '12345678';

        $response = $this->withHeaders(['Accept' => 'application/json'])->put(route('users.update', $user->id), $input);
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['name' => $input['name']]);

    }


    public function dummyUser()
    {
        return ['name' => 'Royal Remix', 'email' => 'uno@gmail.com', 'password' => '12345678', 
        'password_confirmation' => '12345678', 'role' => 'operador', 'status' => '1'];
    }
}
