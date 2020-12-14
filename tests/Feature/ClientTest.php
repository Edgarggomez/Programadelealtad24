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

    public function testUpdateBalancePass()
    {
        $user = factory('App\User')->state('gerente')->create();
        $client = factory('App\Cliente')->state('tarjeta')->create();

        $this->actingAs($user, 'web');

        $input = ['saldo_adicional' => $this->faker->numberBetween(10, 30000)];

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('client.updateBalance', $client->id_cliente), $input);
        $response->assertStatus(302);
        $this->assertDatabaseHas('clientes', ['nombre' => $client->nombre, 'celular' => $client->celular,
                'saldo' => $input['saldo_adicional']]);
    }

    /**
     * @dataProvider providerTestValidationRulesAddBalance
     */
    public function testValidataionRulesUpdateBalanceFail($input)
    {
        $user = factory('App\User')->state('gerente')->create();
        $client = factory('App\Cliente')->state('tarjeta')->create();

        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('client.updateBalance', $client->id_cliente), $input);
        $response->assertJson(['message' => 'The given data was invalid.']);
    }

    public function testClientUpdatePass()
    {
        $user = factory('App\User')->state($this->faker->randomElement(array ('gerente', 'operador')))->create();
        $client = factory('App\Cliente')->create();
        $cardCC = factory('App\TarjetaCC')->create();

        $input = $client->toArray();
        $input['tarjeta'] = $cardCC->tarjeta;
        $input['nombre'] = 'New Name357951';
        $input['celular'] = $this->faker->regexify('[0-9]{11}');

        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->put(route('clients.update', $client->id_cliente), $input);
        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clientes', ['nombre' => $input['nombre'], 'celular' => $input['celular']]);

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

    public function providerTestValidationRulesAddBalance()
    {
        return array(
            array('Negative value' => ['saldo_adicional' => -500]),
            array('Wrong value' => ['saldo_adicional' => 'asdff']),
            array('Empty value' => ['saldo_adicional' => ''])
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
        $tarjetaCC = factory('App\TarjetaCC')->create();
        return [
            'nombre' => $this->faker->name(),
            'celular' => $this->faker->regexify('[0-9]{11}'),
            'correo' => $this->faker->unique()->safeEmail,
            'sexo' => $this->faker->randomElement(array ('M', 'F')),
            'flotilla' => $this->faker->boolean(),
            'rfc' => $this->faker->regexify('[A-Z]{4}[0-9]{6}[A-Z0-9]{3}'),
            'id_ubicacion' => '1',
            'tarjeta' => $tarjetaCC->tarjeta
        ];
    }

}
