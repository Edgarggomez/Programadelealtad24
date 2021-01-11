<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:operador|gerente');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bolsaPagar(Request $request)
    {
        $pagares = $this->bolsaPagarQuery($request)->paginate(10);

        $ubicaciones = Location::all();

        return view('location.bolsa_pagar', compact('pagares', 'ubicaciones'));
    }

    public function bolsaPagarExportar(Request $request)
    {
        $pagaresExport = $this->bolsaPagarQuery($request)->get();

        $dateNow = date("YmdHis");

        $fileName = "bolsa_pagar-$dateNow.csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ubicacion', 'monto');

        $callback = function() use($pagaresExport, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($pagaresExport as $i) {
                $row['ubicacion']  = $i->ubicacion;
                $row['monto']    = $i->monto;

                fputcsv($file, array($row['ubicacion'], $row['monto']));
            }

            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bolsaCobrar(Request $request)
    {
        $cobrares = $this->bolsaCobrarQuery($request)->paginate(10);

        $ubicaciones = Location::all();

        return view('location.bolsa_cobrar', compact('cobrares', 'ubicaciones'));
    }



    public function bolsaCobrarExportar(Request $request)
    {
        $cobraresExport = $this->bolsaCobrarQuery($request)->get();

        $dateNow = date("YmdHis");

        $fileName = "bolsa_cobrar-$dateNow.csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ubicacion', 'monto');

        $callback = function() use($cobraresExport, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($cobraresExport as $i) {
                $row['ubicacion']  = $i->ubicacion;
                $row['monto']    = $i->monto;

                fputcsv($file, array($row['ubicacion'], $row['monto']));
            }

            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }


    public function bolsaPagarQuery($request)
    {
        $rs_ubicaciones = Location::search($request->ubicacion)->get()->pluck('id_ubicacion')->toArray();

        $pagaresQuery = DB::table('movimientos_saldo')
            ->leftJoin('ubicaciones', 'movimientos_saldo.id_ubicacion', '=', 'ubicaciones.id_ubicacion')
            ->select('movimientos_saldo.id_ubicacion', 'ubicaciones.ubicacion', DB::raw('ABS(SUM(monto)) AS monto'))
            ->where('tipo', 'cargo')
            ->when($request->ubicacion, function($query) use($rs_ubicaciones){
                return $query->whereIn('movimientos_saldo.id_ubicacion', $rs_ubicaciones);
            })
            ->when($request->inicio, function($query, $fecha_inicio) {
                return $query->where('fecha_mov', '>=', $fecha_inicio);
            })
            ->when($request->fin, function($query, $fecha_fin) {
                return $query->where('fecha_mov', '<=', $fecha_fin);
            })
            ->groupBy('movimientos_saldo.id_ubicacion', 'ubicaciones.ubicacion');

        return $pagaresQuery;
    }

    public function bolsaCobrarQuery($request)
    {
        $rs_ubicaciones = Location::search($request->ubicacion)->get()->pluck('id_ubicacion')->toArray();

        $pagaresQuery = DB::table('movimientos_saldo')
            ->leftJoin('ubicaciones', 'movimientos_saldo.id_ubicacion', '=', 'ubicaciones.id_ubicacion')
            ->select('movimientos_saldo.id_ubicacion', 'ubicaciones.ubicacion', DB::raw('CASE WHEN SUM(monto) > 0 THEN SUM(monto) ELSE 0 END AS monto'))
            ->when($request->ubicacion, function($query) use($rs_ubicaciones){
                return $query->whereIn('movimientos_saldo.id_ubicacion', $rs_ubicaciones);
            })
            ->when($request->inicio, function($query, $fecha_inicio) {
                return $query->where('fecha_mov', '>=', $fecha_inicio);
            })
            ->when($request->fin, function($query, $fecha_fin) {
                return $query->where('fecha_mov', '<=', $fecha_fin);
            })
            ->groupBy('movimientos_saldo.id_ubicacion', 'ubicaciones.ubicacion');

        return $pagaresQuery;
    }
}
