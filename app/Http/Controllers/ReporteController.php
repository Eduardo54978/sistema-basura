<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }
    public function conteoCategoria(Request $request)
    {
        $query = DB::table('detecciones')
            ->select('categoria', DB::raw('SUM(cantidad) as total'))
            ->groupBy('categoria');
        if ($request->has('fecha') && $request->fecha) {
            $query->whereDate('fecha_deteccion', $request->fecha);
        }
        if ($request->has('mes') && $request->mes) {
            $query->whereMonth('fecha_deteccion', $request->mes);
        }
        if ($request->has('anio') && $request->anio) {
            $query->whereYear('fecha_deteccion', $request->anio);
        }

        $datos = $query->get();
        
        return view('reportes.conteo', compact('datos'));
    }
   public function analisisFecha(Request $request)
{
    $query = DB::table('detecciones')
        ->select(
            DB::raw('MONTH(fecha_deteccion) as mes'),
            DB::raw('SUM(cantidad) as total')
        )
        ->groupBy('mes')
        ->orderBy('mes');
    if ($request->has('anio') && $request->anio) {
        $query->whereYear('fecha_deteccion', $request->anio);
    }
    
    $datos = $query->get();

    return view('reportes.fecha', compact('datos'));
}
    public function visualizacion3d(Request $request)
{
    $anioSeleccionado = $request->get('anio', date('Y'));
    $mesesDisponibles = DB::table('detecciones')
        ->select(DB::raw('DISTINCT MONTH(fecha_deteccion) as mes'))
        ->whereYear('fecha_deteccion', $anioSeleccionado)
        ->orderBy('mes')
        ->get()
        ->pluck('mes');
    $mesSeleccionado = $request->get('mes', date('n'));
    $datos = DB::table('detecciones')
        ->select('categoria', DB::raw('SUM(cantidad) as total'))
        ->whereMonth('fecha_deteccion', $mesSeleccionado)
        ->whereYear('fecha_deteccion', $anioSeleccionado)
        ->groupBy('categoria')
        ->get();

    return view('reportes.3d', compact('datos', 'mesesDisponibles', 'mesSeleccionado', 'anioSeleccionado'));
}
}