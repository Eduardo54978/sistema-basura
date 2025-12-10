<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutomatizacionController extends Controller
{
    public function index(){
        $tareas = DB::table('tareas_programadas')
            ->orderBy('prioridad', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('automatizacion.index', compact('tareas'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'comando' => 'required|string',
            'frecuencia' => 'required|in:diario,semanal,mensual',
            'hora' => 'required',
            'prioridad' => 'required|in:alta,media,baja'
        ]);

        DB::table('tareas_programadas')->insert([
            'nombre' => $request->nombre,
            'comando' => $request->comando,
            'frecuencia' => $request->frecuencia,
            'hora' => $request->hora,
            'prioridad' => $request->prioridad,
            'estado' => 'activa',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('automatizacion.index')->with('success', 'Tarea creada exitosamente');
    }
    public function destroy($id)
    {
        DB::table('tareas_programadas')->where('id', $id)->delete();
        return redirect()->route('automatizacion.index')->with('success', 'Tarea eliminada');
    }
    public function ejecutar($id)
    {
        $tarea = DB::table('tareas_programadas')->where('id', $id)->first();
        if ($tarea) {
            DB::table('historial_ejecuciones')->insert([
                'tarea_id' => $id,
                'estado' => 'completada',
                'mensaje' => 'Tarea ejecutada manualmente',
                'created_at' => now()
            ]);
            
            return redirect()->route('automatizacion.index')->with('success', 'Tarea ejecutada exitosamente');
        }
        
        return redirect()->route('automatizacion.index')->with('error', 'Tarea no encontrada');
    }
    public function historial()
    {
        $historial = DB::table('historial_ejecuciones')
            ->join('tareas_programadas', 'historial_ejecuciones.tarea_id', '=', 'tareas_programadas.id')
            ->select('historial_ejecuciones.*', 'tareas_programadas.nombre as tarea_nombre')
            ->orderBy('historial_ejecuciones.created_at', 'desc')
            ->limit(50)
            ->get();
            
        return view('automatizacion.historial', compact('historial'));
    }
}
