<?php

namespace Tests\Feature;

use App\MovimientoSaldo;
use App\Regla;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovimientoSaldoTest extends TestCase
{
    /**
     *
     */
    use RefreshDatabase;
    use WithFaker;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed();
        factory('App\Location', 2)->create();
    }

    public function setFirstConsumptionFees()
    {
        $porcentage = 20;
        $user = factory('App\User')->state('admin')->create();

        $this->actingAs($user, 'web');

        $input = ['name' => 'PRIMER_CONSUMO', 'value' => $porcentage, 'status' => 1];

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('settings.store'), $input);

        $this->assertDatabaseHas('settings', ['name' => $input['name'], 'value' => $input['value']]);

        return $porcentage;
    }

    public function calculatePts($email, $transactionDate, $amount, $idCliente)
    {
        $percentage = 0;
        $firstPurchase = MovimientoSaldo::where('id_cliente', $idCliente)->doesntExist();
        $firstPurchasePorcentage = Setting::where('name', 'PRIMER_CONSUMO')->where('status', 1)->value('value');
        if($firstPurchase && $firstPurchasePorcentage) {
            return ($firstPurchasePorcentage / 100) * $amount;
        }

        $tDate = strtotime($transactionDate);
        $day = date('w', $tDate);
        $hour = date('H', $tDate);
        $rules = Regla::where('id_ubicacion', User::where('email', $email)->first()->id_ubicacion)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($rules as $rule) {
            if(in_array($day, $rule->dias)) {
                if (($rule->hora_inicial <= $hour) && ($hour <= $rule->hora_final)) {
                    $percentage = $rule->porcentaje;
                    break;
                }
            }
        }

        return ($percentage/100) * $amount;
    }

    public function testCGReceiveInvoices()
    {
        $user = factory('App\User')->state('operador')->create();
        $location = factory('App\Location')->create();
        $user->id_ubicacion = $location->id_ubicacion;
        $user->save();

        $porcentage = $this->setFirstConsumptionFees();

        $client = factory('App\Cliente')->state('tarjeta')->create();

        $invoices = [];

        for ($i=0; $i < 50; $i++) {
            $invoices[] = array('id_cliente' => $client->id_cliente, 'id_tarjeta' => $client->id_tarjeta_principal, 'folio' => $this->faker->postcode,
                                'monto' => $this->faker->numberBetween(100, 99999), 'fecha_consumo' => Carbon::now(), 'hash_local' => $this->faker->md5);
        }

        $input = ['email' => $user->email,
            'consumos' => $invoices];

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('cg.receiveConsumos'), $input);
        foreach ($invoices as $i => $invoice) {
            if ($i == 0) {
                $pts = strval(round($invoice['monto'] * ($porcentage/100), 2));
            } else {
                $pts = 0;
            }
            $this->assertDatabaseHas('movimientos_saldo', ['id_cliente' => $invoice['id_cliente'],
                                                        'id_tarjeta' => $invoice['id_tarjeta'],
                                                        'folio' => $invoice['folio'],
                                                        'monto' => $pts,
                                                        'fecha_consumo' => $invoice['fecha_consumo']
                                                    ]);
        }
    }

    public function testRulesPoints()
    {

        $users = factory('App\User', 10)->state('operador')->create();
        $locations = factory('App\Location', 10)->create();

        foreach ($users as $key => $user) {
            $user->id_ubicacion = $locations[$key]->id_ubicacion;
            $user->save();
        }

        foreach ($locations as $key => $location) {
            $rule[] = factory('App\Regla')->create(['id_ubicacion' => $location->id_ubicacion, 'tipo' => '1']);
            $rule[] = factory('App\Regla', 3)->create(['id_ubicacion' => $location->id_ubicacion, 'tipo' => '0']);
        }

        $inputs = [];

        foreach ($users as $user) {
            $clients = factory('App\Cliente', 20)->state('tarjeta')->create();
            $invoices = [];
            foreach ($clients as $client) {
                $invoices[] = array('id_cliente' => $client->id_cliente, 'id_tarjeta' => $client->id_tarjeta_principal, 'folio' => $this->faker->postcode,
                                    'monto' => $this->faker->numberBetween(100, 99999), 'fecha_consumo' => $this->faker->dateTimeBetween(-1)->format('Y-m-d H:i:s'), 'hash_local' => $this->faker->md5);
            }
            $inputs[] = array('email' => $user->email, 'consumos' => $invoices);
        }

        foreach ($inputs as $input) {
            $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('cg.receiveConsumos'), $input);
        }

        foreach ($inputs as $input) {
            foreach ($input['consumos'] as $invoice) {
                $pts = $this->calculatePts($input['email'], $invoice['fecha_consumo'], $invoice['monto'], $invoice['id_cliente']);
                $this->assertDatabaseHas('movimientos_saldo', ['id_cliente' => $invoice['id_cliente'],
                    'id_tarjeta' => $invoice['id_tarjeta'],
                    'folio' => $invoice['folio'],
                    'monto' => $pts,
                    'fecha_consumo' => $invoice['fecha_consumo']
                ]);
            }
        }
    }

    public function testExpiredPoints()
    {
        $users = factory('App\User', 5)->state('operador')->create();
        $locations = factory('App\Location', 5)->create();

        foreach ($users as $key => $user) {
            $user->id_ubicacion = $locations[$key]->id_ubicacion;
            $user->save();
        }

        $porcentage = $this->setFirstConsumptionFees();

        $inputs = [];

        foreach ($users as $user) {
            $clients = factory('App\Cliente', 20)->state('tarjeta')->create();
            $invoices = [];
            foreach ($clients as $client) {
                $invoices[] = array('id_cliente' => $client->id_cliente, 'id_tarjeta' => $client->id_tarjeta_principal, 'folio' => $this->faker->postcode,
                                    'monto' => $this->faker->numberBetween(100, 99999), 'fecha_consumo' => $this->faker->dateTimeBetween('-2 years', '-1 year')->format('Y-m-d H:i:s'), 'hash_local' => $this->faker->md5);
            }
            $inputs[] = array('email' => $user->email, 'consumos' => $invoices);
        }

        foreach ($inputs as $input) {
            $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('cg.receiveConsumos'), $input);
        }

        foreach ($inputs as $input) {
            foreach ($input['consumos'] as $invoice) {
                $this->assertDatabaseHas('movimientos_saldo', ['id_cliente' => $invoice['id_cliente'],
                    'id_tarjeta' => $invoice['id_tarjeta'],
                    'folio' => $invoice['folio'],
                    'monto' => strval(round($invoice['monto'] * ($porcentage/100), 2)),
                    'saldo_nuevo' => strval(round($invoice['monto'] * ($porcentage/100), 2)),
                    'fecha_consumo' => $invoice['fecha_consumo']
                ]);

                $this->assertDatabaseHas('clientes', ['id_cliente' => $invoice['id_cliente'], 'saldo' => strval(round($invoice['monto'] * ($porcentage/100), 2))]);
            }
        }

        $response = $this->withHeaders(['Accept' => 'application/json'])->post(route('points.expired'));

        foreach ($inputs as $input) {
            foreach ($input['consumos'] as $invoice) {
                $this->assertDatabaseHas('clientes', ['id_cliente' => $invoice['id_cliente'], 'saldo' => 0]);
            }
        }
    }

}
