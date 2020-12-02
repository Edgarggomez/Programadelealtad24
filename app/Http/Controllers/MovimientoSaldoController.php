<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Location;
use App\MovimientoSaldo;
use App\Tarjeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoSaldoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cards = Tarjeta::search($request->search)->get()->pluck('id_tarjeta')->toArray();
        $clients = Cliente::search($request->search)->get()->pluck('id_cliente')->toArray();
        $rs_ubicaciones = Location::search($request->ubicacion)->get()->pluck('id_ubicacion')->toArray();
        $mov_query = MovimientoSaldo::where(function($query) use($clients, $cards) {
            $query->whereIn('id_cliente', $clients)
                ->orWhereIn('id_tarjeta', $cards);
        })
        ->when($request->ubicacion, function($query, $rs_ubicaciones){
            return $query->where('id_ubicacion', $rs_ubicaciones);
        })
        ->when($request->inicio, function($query, $fecha_inicio) {
            return $query->where('fecha_mov', '>=', $fecha_inicio);
        })
        ->when($request->fin, function($query, $fecha_fin) {
            return $query->where('fecha_mov', '<=', $fecha_fin);
        });

        $id_clientes = $mov_query->get()->unique('id_cliente')->pluck('id_cliente')->toArray();

        if (count($id_clientes) == 1) {
            $unCliente = Cliente::find($id_clientes[0]);
        } else {
            $unCliente = null;
        }

        $movimientos = $mov_query->paginate(10);
        $ubicaciones = Location::all();
        return view('client.balance_history', compact(['movimientos', 'ubicaciones', 'unCliente']));

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {

        $cards = Tarjeta::search($request->search)->get()->pluck('id_tarjeta')->toArray();
        $clients = Cliente::search($request->search)->get()->pluck('id_cliente')->toArray();
        $rs_ubicaciones = Location::search($request->ubicacion)->get()->pluck('id_ubicacion')->toArray();
        $mov_query = MovimientoSaldo::where(function($query) use($clients, $cards) {
            $query->whereIn('id_cliente', $clients)
                ->orWhereIn('id_tarjeta', $cards);
        })
        ->when($request->ubicacion, function($query, $rs_ubicaciones){
            return $query->where('id_ubicacion', $rs_ubicaciones);
        })
        ->when($request->inicio, function($query, $fecha_inicio) {
            return $query->where('fecha_mov', '>=', $fecha_inicio);
        })
        ->when($request->fin, function($query, $fecha_fin) {
            return $query->where('fecha_mov', '<=', $fecha_fin);
        });

        $movimientos = $mov_query->get();

        $fileName = 'movimientos.csv';


        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('tarjeta', 'estatus tarjeta', 'fecha mov', 'ubicacion', 'operacion', 'origen', 'folio', 'monto', 'saldo', 'autor', 'usuario');

        $callback = function() use($movimientos, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($movimientos as $mov) {
                $row['tarjeta']  = $mov->tarjeta->tarjeta;
                $row['estatus tarjeta']    = $mov->tarjeta->estatus ? 'Activo' : 'Inactivo';
                $row['fecha mov']    = $mov->fecha_mov;
                $row['ubicacion']  = $mov->ubicacion->ubicacion ?? 'N/A';
                $row['operacion']  = $mov->tipo;
                $row['origen']  = $mov->origen;
                $row['folio']  = $mov->folio;
                $row['monto']  = $mov->monto;
                $row['saldo']  = $mov->saldo_nuevo;
                $row['autor']  = $mov->tipo_usuario;
                $row['usuario']  = $mov->email_usuario ?? 'N/A';

                fputcsv($file, array($row['tarjeta'], $row['estatus tarjeta'], $row['fecha mov'], $row['ubicacion'],
                $row['operacion'], $row['origen'], $row['folio'], $row['monto'], $row['saldo'], $row['autor'], $row['usuario']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MovimientoSaldo  $movimientoSaldo
     * @return \Illuminate\Http\Response
     */
    public function show(MovimientoSaldo $movimientoSaldo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MovimientoSaldo  $movimientoSaldo
     * @return \Illuminate\Http\Response
     */
    public function edit(MovimientoSaldo $movimientoSaldo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MovimientoSaldo  $movimientoSaldo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MovimientoSaldo $movimientoSaldo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MovimientoSaldo  $movimientoSaldo
     * @return \Illuminate\Http\Response
     */
    public function destroy(MovimientoSaldo $movimientoSaldo)
    {
        //
    }

	public function expiredPoints(Request $request) {
        $rs = DB::table('movimientos_saldo')
            ->select(DB::raw('id_tarjeta, id_cliente, SUM(CASE WHEN tipo="abono" THEN monto ELSE 0 END) AS puntos_abonados,
                SUM(CASE WHEN tipo="cargo" THEN monto ELSE 0 END) AS puntos_gastados,
                (SUM(CASE WHEN tipo="abono" THEN monto ELSE 0 END) + SUM(CASE WHEN tipo="cargo" THEN monto ELSE 0 END)) AS puntos_vencidos'))
            ->where('fecha_consumo', '<=', Carbon::now()->subYears(1)->toDateTimeString())
            ->groupBy('id_tarjeta', 'id_cliente')
            ->get();

        foreach ($rs as $r) {
            if ($r->puntos_vencidos > 0) {
                $client = Cliente::find($r->id_cliente);
                $movSaldo = new MovimientoSaldo;

                $monto = $r->puntos_vencidos * -1;

                $movSaldo->id_cliente = $r->id_cliente;
                $movSaldo->id_tarjeta = $r->id_tarjeta;
                $movSaldo->tipo = "cargo";
                $movSaldo->origen = 'vencidos';
                $movSaldo->monto = $monto;
                $movSaldo->tipo_usuario = 'S';
                $movSaldo->saldo_anterior = $client->saldo ?? 0;
                $movSaldo->saldo_nuevo = $client->saldo + $monto;
                $movSaldo->save();
                $client->fecha_actualizacion_saldo = Carbon::now();
                $client->saldo = $movSaldo->saldo_nuevo;
                $client->save();
            }
        }
        return response()->json(array("method"=>__FUNCTION__,"response"=>$rs), 200);

	}
}
