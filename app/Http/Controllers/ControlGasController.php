<?php

namespace App\Http\Controllers;

use App\Cliente ;
use App\ClienteSyncCG ;
use App\Consumo ;
use App\Tarjeta ;
use App\TarjetaSyncCG ;
use App\User;
use App\MovimientoSaldo;
use App\Location;
use App\Regla;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
class ControlGasController extends Controller
{
    public function getUbicacion(Request $request,$metodo)
    {
		if($user=User::where('email', $request['email'])->first())
		{
			if(!$user->id_ubicacion)
					return response()->json(
				array("request"=>$request->all(),
          	   "method"=>$metodo,
          	   "response"=>["error"=>"Ubicación Invalida Usuario: ".$request['email']]), 200);
			else
				return $user->id_ubicacion;

		}
		else
			return response()->json(
				array("request"=>$request->all(),
          	   "method"=>$metodo,
          	   "response"=>["error"=>"Mail Invalido: ".$request['email']]), 200);


    }
    public function sendClientes(Request $request)
    {
		//Cliente actualizados  en nube aun no sincronizado en CG
		//Retorna Clientes

		$response=$this->getUbicacion($request,__FUNCTION__);
		if(is_numeric($response))
			$id_ubicacion=$response;
		else
			return $response;
		$response = DB::table('clientes')
		->leftJoin('clientes_sync_cg', function($join) use ($id_ubicacion) {
            $join->on('clientes.id_cliente', '=', 'clientes_sync_cg.id_cliente');
           	$join->on('clientes_sync_cg.id_ubicacion','=',DB::raw($id_ubicacion));
        })
		->leftJoin('estados', 'clientes.id_estado', '=', 'estados.id_estado')
		->leftJoin('ciudades', 'clientes.id_ciudad', '=', 'ciudades.id_ciudad')
		->select('clientes.id_cliente', 'nombre','paterno','materno','domicilio','colonia','cp',
			'ciudades.ciudad','estados.estado','persona_fisica','rfc','correo','celular','estatus')
		->where('clientes_sync_cg.fecha_sync_cliente','<=',DB::raw('clientes.fecha_actualizacion'))
		->where('clientes_sync_cg.id_ubicacion','=',$id_ubicacion)
		->orWhereNull('clientes_sync_cg.id_ubicacion')
		->get();


		foreach ($response as $cliente)
		{
			//Mandar fecha actual de sincronizacion
			$cliente->fecha_sync_cliente= date('Y-m-d H:i:s');

			/*$cliente->tarjetas= DB::table('tarjetas')
			->select('tarjeta')
			->where('id_cliente','=',$cliente->id_cliente)
			->orderBy('adicional', 'asc')
			->get();*/

		}

		$response=["id_ubicacion"=>$id_ubicacion,"clientes"=>$response];

        return response()->json(

         array("request"=>$request->all(),
          	   "method"=>__FUNCTION__,
          	   "response"=>$response), 200);

    }

	public function receiveSyncOkClientes(Request $request)
    {
		//Recibe estatus de clientes actualizados
		//Recibe Estatus
		//Retorna vacio

		$response=$this->getUbicacion($request,__FUNCTION__);
		if(is_numeric($response))
			$id_ubicacion=$response;
		else
			return $response;

    	$i=0;
		$response=array();
		if(is_array($request->clientes))
		{
				foreach ($request->clientes as  $req)
				{

					if($cli =Cliente::where('id_cliente', $req['id_cliente'])->first())
					{

						If(!$cliCG =ClienteSyncCG::where('id_cliente', $req['id_cliente'])
						->where('id_ubicacion', $id_ubicacion)
						->first())
						{
							$cliCG = new ClienteSyncCG;
							$cliCG->id_cliente=$req['id_cliente'];
							$cliCG->id_ubicacion=$id_ubicacion;
						}


						//Guardar fecha sincronizacion
						$cliCG->fecha_sync_cliente = $req["fecha_sync_cliente"];
				    	$cliCG->save();

				    	$response[$i]["id_cliente"]=$cliCG->id_cliente;
						$response[$i]["fecha_sync_cliente"]=$cliCG->fecha_sync_cliente;

						$i++;



					}


				}

		}
		$response=["id_ubicacion"=>$id_ubicacion,"clientes"=>$response];
          return response()->json(
        	array("method"=>__FUNCTION__,
				"request"=>$request->all(),
				"response"=>$response
			 ), 200);



	}
	  public function sendTarjetas(Request $request)
    {
		//Tarjetas actualizadas  en nube aun no sincronizado en CG
		//Retorna Tarjetas

		$response=$this->getUbicacion($request,__FUNCTION__);
		if(is_numeric($response))
			$id_ubicacion=$response;
		else
			return $response;


		$response = DB::table('tarjetas')
		->leftJoin('tarjetas_sync_cg', function($join) use ($id_ubicacion) {

            $join->on('tarjetas.id_tarjeta', '=', 'tarjetas_sync_cg.id_tarjeta');
           	$join->on('tarjetas_sync_cg.id_ubicacion','=',DB::raw($id_ubicacion));
        })
		->select('id_cliente', 'tarjetas.id_tarjeta','tarjetas.tarjeta','tarjetas.estatus','tarjetas.nombre')
		->where('tarjetas_sync_cg.fecha_sync_tarjeta','<=',DB::raw('tarjetas.fecha_actualizacion'))
		->where('tarjetas_sync_cg.id_ubicacion','=',$id_ubicacion)
		->orWhereNull('tarjetas_sync_cg.id_ubicacion')
		->get();

		//Mandar fecha actual de sincronizacion
		foreach ($response as $tarjeta)
			$tarjeta->fecha_sync_tarjeta= date('Y-m-d H:i:s');

		$response=["id_ubicacion"=>$id_ubicacion,"tarjetas"=>$response];

        return response()->json(

         array("request"=>$request->all(),
          	   "method"=>__FUNCTION__,
          	   "response"=>$response), 200);


    }

	public function receiveSyncOkTarjetas(Request $request)
    {
		//Recibe estatus de Tarjetas actualizados
		//Recibe Estatus
		//Retorna vacio

		$response=$this->getUbicacion($request,__FUNCTION__);
		if(is_numeric($response))
			$id_ubicacion=$response;
		else
			return $response;

    	$i=0;
		$response=array();
		if(is_array($request->tarjetas))
		{
				foreach ($request->tarjetas as  $req)
				{

					if($tar =Tarjeta::where('id_tarjeta', $req['id_tarjeta'])->first())
					{

						If(!$tarCG =TarjetaSyncCG::where('id_tarjeta', $req['id_tarjeta'])
						->where('id_ubicacion', $id_ubicacion)
						->first())
						{
							$tarCG = new TarjetaSyncCG;
							$tarCG->id_tarjeta=$req['id_tarjeta'];
							$tarCG->id_ubicacion=$id_ubicacion;
						}


						//Guardar fecha sincronizacion
						$tarCG->fecha_sync_tarjeta = $req["fecha_sync_tarjeta"];
				    	$tarCG->save();

				    	$response[$i]["id_tarjeta"]=$tarCG->id_tarjeta;
						$response[$i]["fecha_sync_tarjeta"]=$tarCG->fecha_sync_tarjeta;

						$i++;



					}


				}

		}
		$response=["id_ubicacion"=>$id_ubicacion,"tarjetas"=>$response];
          return response()->json(
        	array("method"=>__FUNCTION__,
				"request"=>$request->all(),
				"response"=>$response
			 ), 200);




	}
	public function receiveConsumos(Request $request)
	{
		//Recibe consumos controlgas
		//Recibe arreglo de consumos en conctrolgas
		//Retorna consumos registrados


		$response=$this->getUbicacion($request,__FUNCTION__);
		if(is_numeric($response))
			$id_ubicacion=$response;
		else
			return $response;

		$i=0;
		$response=array();
		if(is_array($request->consumos))
		{
				foreach ($request->consumos as $consumo)
				{
                    $consumoNuevo = false;

					if(!$con =
						Consumo::where('origen', "gasolinera")
						->where('id_ubicacion', $id_ubicacion)
						->where('hash_local', $consumo["hash_local"])->first()
                       )
                       {
                            $con = new Consumo;
                            $consumoNuevo = true;
                       }

					$con->origen = "gasolinera";
					$con->id_ubicacion = $id_ubicacion;
					$con->hash_local = $consumo["hash_local"];
					$con->id_cliente = $consumo["id_cliente"];
					$con->id_tarjeta = $consumo["id_tarjeta"];
					$con->folio = $consumo["folio"];
					$con->monto = $consumo["monto"];
					$con->tipo = "despacho";
					$con->fecha_consumo = $consumo["fecha_consumo"];
				    $con->fecha_sync_consumo = date('Y-m-d H:i:s');
                    $con->save();

                    if ($consumoNuevo) {
                        $movSaldo = new MovimientoSaldo;
                        $monto = $this->getMonto($id_ubicacion, $consumo['fecha_consumo'], $consumo['monto'], $consumo['id_cliente']);
                        $client = Cliente::find($consumo['id_cliente']);
                        $movSaldo->id_cliente = $consumo["id_cliente"];
                        $movSaldo->id_tarjeta = $consumo["id_tarjeta"];
                        $movSaldo->id_ubicacion = $id_ubicacion;
                        $movSaldo->id_consumo = $con->id_consumo;
                        $movSaldo->tipo = 'abono';
                        $movSaldo->origen = 'gasolinera';
                        $movSaldo->monto = $monto;
                        $movSaldo->saldo_anterior = $client->saldo ?? 0;
                        $movSaldo->saldo_nuevo = $client->saldo + $monto;
                        $movSaldo->tipo_usuario = 'S';
                        $movSaldo->folio = $consumo['folio'];
                        $movSaldo->fecha_consumo = $consumo['fecha_consumo'];
                        $movSaldo->save();
                        $client->fecha_actualizacion_saldo = Carbon::now();
                        $client->saldo = $movSaldo->saldo_nuevo;
                        $client->save();
                    }


					$response[$i]["hash_local"]=$con->hash_local;
					$response[$i]["fecha_sync_consumo"]=$con->fecha_sync_consumo;

					$i++;

				}

		}


     	$response=["id_ubicacion"=>$id_ubicacion,"consumos"=>$response];
          return response()->json(
        	array("method"=>__FUNCTION__,
				"request"=>$request->all(),
				"response"=>$response
			 ), 200);



    }

    public function getMonto ($idLocation, $transactionDate, $amount, $idCliente) {
        $percentage = 0;
        $firstPurchase = MovimientoSaldo::where('id_cliente', $idCliente)->doesntExist();
        $firstPurchasePorcentage = Setting::where('name', 'PRIMER_CONSUMO')->where('status', 1)->value('value');
        if($firstPurchase && $firstPurchasePorcentage) {
            return ($firstPurchasePorcentage / 100) * $amount;
        }
        $tDate = strtotime($transactionDate);
        $day = date('w', $tDate);
        $hour = date('H', $tDate);
        $rules = Regla::where('id_ubicacion', $idLocation)
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


}
