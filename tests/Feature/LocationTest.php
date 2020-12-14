<?php

namespace Tests\Feature;

use App\Location;
use App\Regla;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationTest extends TestCase
{

    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();
    }

    public function arrayRulesDB($rule)
    {
        return ['id_ubicacion' => $rule['id_ubicacion'], 'porcentaje' => $rule['porcentaje'],
                'hora_inicial' => $rule['hora_inicial'], 'hora_final' => $rule['hora_final'], 'dias' => json_encode($rule['dias'])];
    }

    public function testCreateLocationPass()
    {
        $user = factory('App\User')->state('admin')->create();

        $input = factory(Location::class)->make();

        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('locations.store'), $input->toArray());
        $response->assertStatus(302);
        $this->assertDatabaseHas('ubicaciones', ['ubicacion' => $input['ubicacion'], 'id_bd' => $input['id_bd']]);
    }

    public function testUpdateLocationPass()
    {
        $user = factory('App\User')->state('admin')->create();

        $location = factory(Location::class)->create();


        $input = $location->toArray();

        $storecc = factory('App\TiendaCC')->create();
        $dbcc = factory('App\BdCC')->create();

        $input['ubicacion'] = 'Nueva Ubi986571';
        $input['id_tda'] = $storecc->id_tda;
        $input['id_bd'] = $dbcc->id_bd;

        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->put(route('locations.update', $location->id_ubicacion), $input);
        $response->assertStatus(302);
        $this->assertDatabaseHas('ubicaciones', ['ubicacion' => $input['ubicacion'], 'id_bd' => $input['id_bd'], 'id_tda' => $input['id_tda']]);

    }

    /**
     * @dataProvider providertestUnauthorizedUsers
     */
    public function testUnauthorizedRolFail($role)
    {
        $user = factory('App\User')->state($role)->create();

        $input = factory(Location::class)->make();

        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('locations.store'), $input->toArray());
        $response->assertStatus(403);
        $this->assertDatabaseMissing('ubicaciones', ['ubicacion' => $input['ubicacion'], 'id_bd' => $input['id_bd']]);
    }

    public function testDuplicateLocationsFail()
    {
        $user = factory('App\User')->state('admin')->create();

        $location = factory(Location::class)->create();

        $input = factory(Location::class)->make();
        $input->ubicacion = $location->ubicacion;
        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('locations.store'), $input->toArray());
        $response->assertJson(['message' => 'The given data was invalid.']);
        $this->assertDatabaseMissing('ubicaciones', ['id_tda' => $input['id_tda'], 'id_bd' => $input['id_bd']]);
    }

    public function testDuplicateStoreInLocationsFail()
    {
        $user = factory('App\User')->state('admin')->create();

        $location = factory(Location::class)->create();

        $input = factory(Location::class)->make();
        $input->id_tda = $location->id_tda;
        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('locations.store'), $input->toArray());
        $response->assertJson(['message' => 'The given data was invalid.']);
        $this->assertDatabaseMissing('ubicaciones', ['ubicacion' => $input['ubicacion'], 'id_tda' => $input['id_tda'], 'id_bd' => $input['id_bd']]);
    }

    public function testIndexLocationsPass()
    {
        $user = factory('App\User')->state('admin')->create();
        $locations = factory(Location::class, 10)->create();
        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->get(route('locations.index'));
        foreach ($locations as $location) {
            $response->assertSee($location->ubicacion);
            $this->assertDatabaseHas('ubicaciones', ['ubicacion' => $location->ubicacion, 'id_bd' => $location->id_bd, 'id_tda' => $location->id_tda]);
        }
    }

    public function testSearchLocationsPass()
    {
        $user = factory('App\User')->state('admin')->create();
        $locations = factory(Location::class, 20)->create();
        $this->actingAs($user, 'web');

        foreach ($locations as $location) {
            $response = $this->withHeaders(['Accept' => 'application/json'])->get(route('locations.index', ['search' => $location->ubicacion]));
            $response->assertSee($location->ubicacion);
            $this->assertDatabaseHas('ubicaciones', ['ubicacion' => $location->ubicacion, 'id_bd' => $location->id_bd, 'id_tda' => $location->id_tda]);
        }
    }

    public function testIndexLocationsFail()
    {
        $user = factory('App\User')->state('admin')->create();
        $locations_fail = factory(Location::class, 5)->create();

        foreach ($locations_fail as $location) {
            $location->estatus = '0';
            $location->save();
        }
        $this->actingAs($user, 'web');

        $response = $this->withHeaders(['Accept' => 'application/json'])->get(route('locations.index'));
        foreach ($locations_fail as $location) {
            $response->assertDontSee($location->ubicacion);
            $this->assertDatabaseHas('ubicaciones', ['ubicacion' => $location->ubicacion, 'id_bd' => $location->id_bd, 'id_tda' => $location->id_tda]);
        }
    }


    public function testCreateDefaultRulesPass()
    {
        $user = factory('App\User')->state('admin')->create();

        $locations = factory(Location::class, 10)->create();

        $this->actingAs($user, 'web');

        foreach ($locations as $i) {
            $rule = factory(Regla::class)->make(['id_ubicacion' => $i->id_ubicacion, 'tipo' => '1']);
            $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('rules.store', $i->id_ubicacion), $rule->toArray());
            $response->assertStatus(302);
            $this->assertDatabaseHas('reglas', $this->arrayRulesDB($rule));
        }
    }

    public function testValidateTwoRulesByDefault()
    {
        $user = factory('App\User')->state('admin')->create();

        $location = factory(Location::class)->create();
        $rule1 = factory(Regla::class)->create(['id_ubicacion' => $location->id_ubicacion, 'tipo' => 1]);



        $this->actingAs($user, 'web');

        $rule2 = factory(Regla::class)->make(['id_ubicacion' => $location->id_ubicacion, 'tipo' => 1]);
        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('rules.store', $location->id_ubicacion), $rule2->toArray());
        $response->assertJson(['message' => 'The given data was invalid.']);
        $this->assertDatabaseMissing('reglas', $this->arrayRulesDB($rule2));
    }

    /**
     * @dataProvider providerTestValidationRules
     */
    public function testValidationRulesFail($invalidData)
    {
        $user = factory('App\User')->state('admin')->create();

        $location = factory(Location::class)->create();

        $invalidData['tipo'] = '1';
        $invalidData['id_ubicacion'] = $location->id_ubicacion;

        $this->actingAs($user, 'web');

        $rule = factory(Regla::class)->make($invalidData);
        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('rules.store', $location->id_ubicacion), $rule->toArray());
        $response->assertJson(['message' => 'The given data was invalid.']);
        $this->assertDatabaseMissing('reglas', $this->arrayRulesDB($rule));
    }

    public function providerTestValidationRules()
    {
        return array(
            array('Start hour older than end hour' => ['hora_inicial' => 22, 'hora_final' => 10]),
            array('Porcentage greater than 100' => ['porcentaje' => 120]),
            array('Empty porcentage' => ['porcentaje' => '']),
            array('Empty days' => ['dias' => '']),
            array('Empty initial hour' => ['hora_inicial' => '']),
            array('Empty final hour' => ['hora_final' => ''])
        );
    }

    public function providertestUnauthorizedUsers()
    {
        return array (
            array('Usuario Operador' => 'operador'),
            array('Usuario Gerente' => 'gerente')
        );
    }
}
