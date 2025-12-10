<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('detecciones')
            ->select('detecciones.*')
            ->orderBy('created_at', 'desc');

        // Filtros con expresiones regulares
        if ($request->has('fecha') && $request->fecha) {
            $query->whereDate('fecha_deteccion', $request->fecha);
        }

        if ($request->has('categoria') && $request->categoria) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->has('archivo') && $request->archivo) {
            // Filtrar por nombre de archivo usando LIKE (similar a regex)
            $query->where('nombre_archivo', 'like', '%' . $request->archivo . '%');
        }

        $analisis = $query->paginate(20);
        
        // Obtener estadísticas
        $total_analisis = DB::table('detecciones')->count();
        $total_archivos = DB::table('detecciones')->distinct('nombre_archivo')->count('nombre_archivo');
        $total_residuos = DB::table('detecciones')->sum('cantidad');

        return view('historial.index', compact('analisis', 'total_analisis', 'total_archivos', 'total_residuos'));
    }

    public function show($id)
    {
        $deteccion = DB::table('detecciones')->where('id', $id)->first();
        
        if (!$deteccion) {
            return redirect()->route('historial.index')->with('error', 'Análisis no encontrado');
        }

        // Obtener todas las detecciones del mismo archivo
        $detecciones_archivo = DB::table('detecciones')
            ->where('nombre_archivo', $deteccion->nombre_archivo)
            ->get();

        return view('historial.detalle', compact('deteccion', 'detecciones_archivo'));
    }

    public function destroy($id)
    {
        DB::table('detecciones')->where('id', $id)->delete();
        return redirect()->route('historial.index')->with('success', 'Análisis eliminado correctamente');
    }

    public function destroyAll()
    {
        DB::table('detecciones')->truncate();
        return redirect()->route('historial.index')->with('success', 'Todo el historial ha sido eliminado');
    }

    public function exportarXML()
    {
        $detecciones = DB::table('detecciones')->get();

        $xml = new \SimpleXMLElement('<historial></historial>');
        $xml->addChild('fecha_exportacion', date('Y-m-d H:i:s'));
        $xml->addChild('total_registros', count($detecciones));

        $analisisNode = $xml->addChild('analisis');

        foreach ($detecciones as $deteccion) {
            $item = $analisisNode->addChild('registro');
            $item->addChild('id', $deteccion->id);
            $item->addChild('archivo', $deteccion->nombre_archivo);
            $item->addChild('categoria', $deteccion->categoria);
            $item->addChild('cantidad', $deteccion->cantidad);
            $item->addChild('fecha', $deteccion->fecha_deteccion);
            $item->addChild('fecha_registro', $deteccion->created_at);
        }

        $nombreArchivo = 'historial_' . date('Y-m-d_His') . '.xml';
        $rutaArchivo = storage_path('app/public/exports/' . $nombreArchivo);

        if (!file_exists(storage_path('app/public/exports'))) {
            mkdir(storage_path('app/public/exports'), 0755, true);
        }

        $xml->asXML($rutaArchivo);

        return response()->download($rutaArchivo)->deleteFileAfterSend(true);
    }

    public function exportarPDF()
    {
        $detecciones = DB::table('detecciones')
            ->orderBy('fecha_deteccion', 'desc')
            ->get();

        // Generar HTML para PDF
        $html = view('historial.pdf', compact('detecciones'))->render();

        // Guardar como archivo HTML (simulación de PDF)
        $nombreArchivo = 'historial_' . date('Y-m-d_His') . '.html';
        $rutaArchivo = storage_path('app/public/exports/' . $nombreArchivo);

        if (!file_exists(storage_path('app/public/exports'))) {
            mkdir(storage_path('app/public/exports'), 0755, true);
        }

        file_put_contents($rutaArchivo, $html);

        return response()->download($rutaArchivo)->deleteFileAfterSend(true);
    }
}