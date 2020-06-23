<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();
        factory('App\Location', 2)->create();
    }

    public function testUnauthorizedUsersStoreFails() {
        
        $user = factory('App\User')->state('admin')->create();
        $input = $this->dummyClient();
        
        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('clients.store'), $input);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('clientes', ['correo' => $input['correo']]);
    }

    /**
     * @dataProvider providertestAuthorizedUsersStorePass
     */
    public function testAuthorizedUsersStorePass($role) {
        
        $user = factory('App\User')->state($role)->create();
        $input = $this->dummyClient();
        
        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('clients.store'), $input);
        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clientes', ['correo' => $input['correo']]);
    }

    /**
     * @dataProvider providerTestValidationRules
     */
    public function testValidationRules($newInput)
    {
        $user = factory('App\User')->state($this->faker->randomElement(array ('gerente', 'operador')))->create();
        $input = $this->dummyClient();

        $input = array_replace($input, $newInput);

        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('clients.store'), $input);
        $response->assertJson(['message' => 'The given data was invalid.']);
        $this->assertDatabaseMissing('clientes', ['correo' => $input['correo']]);
        
    }

    public function testClientValidationDuplicatedEmailFail()
    {
        $user = factory('App\User')->state($this->faker->randomElement(array ('gerente', 'operador')))->create();
        $firstInput = $this->dummyClient();
        $secondInput = $this->dummyClient();

        $secondInput['correo'] = $firstInput['correo'];

        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('clients.store'), $firstInput);
        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clientes', ['correo' => $firstInput['correo']]);

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('clients.store'), $secondInput);
        $response->assertJson(['message' => 'The given data was invalid.']);
        $this->assertDatabaseMissing('clientes', ['rfc' => $secondInput['rfc']]);
    }

    public function testClientUpdateSuccess(Type $var = null)
    {
        $user = factory('App\User')->state($this->faker->randomElement(array ('gerente', 'operador')))->create();
        $client = factory('App\Cliente')->create();

        $input = $client->toArray();
        $input['tarjeta'] = $this->faker->regexify('[0-9]{15}');
        $input['nombre'] = 'New Name357951';
        $input['add_balance'] = 100;
        
        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->put(route('clients.update', $client->id_cliente), $input);
        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clientes', ['nombre' => $input['nombre'], 'saldo' => $input['add_balance']]);

    }

    public function providerTestValidationRules()
    {
        return array(
            array('Missing Email' => ['correo' => '']),
            array('Missing Name' => ['nombre' => '']),
            array('Missing Phone' => ['celular' => '']),
            array('Missing Sex' => ['sexo' => '']),
            array('Missing rfc' => ['rfc' => '']),
            array('Missing Location' => ['id_ubicacion' => '']),
            array('Missing Card' => ['tarjeta' => '']),
            array('Wrong Email' => ['correo' => 'asfdas'])
        );
    }

    public function providertestAuthorizedUsersStorePass()
    {
        return array (
            array('Usuario Operador' => 'operador'),
            array('Usuario Gerente' => 'gerente')
        );
    }
    
    public function dummyClient() {
        return [
            'nombre' => $this->faker->name(),
            'celular' => $this->faker->phoneNumber(),
            'correo' => $this->faker->unique()->safeEmail,
            'sexo' => $this->faker->randomElement(array ('M', 'F')),
            'flotilla' => $this->faker->boolean(),
            'rfc' => $this->faker->regexify('[A-Z]{4}[0-9]{6}[A-Z0-9]{3}'),
            'id_ubicacion' => '1',
            'tarjeta' => $this->faker->regexify('[0-9]{15}')
        ];
    }
    
}
