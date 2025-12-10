<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configuraciones = DB::table('configuraciones')->get();
        
        // Si no hay configuraciones, crear las predeterminadas
        if ($configuraciones->isEmpty()) {
            $this->crearConfiguracionesPredeterminadas();
            $configuraciones = DB::table('configuraciones')->get();
        }

        return view('configuracion.index', compact('configuraciones'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $clave => $valor) {
            DB::table('configuraciones')
                ->where('clave', $clave)
                ->update(['valor' => $valor, 'updated_at' => now()]);
        }

        // Exportar a XML
        $this->exportarConfiguracionXML();

        return redirect()->route('configuracion.index')->with('success', 'Configuración guardada correctamente');
    }

    private function crearConfiguracionesPredeterminadas()
    {
        $configs = [
            ['clave' => 'umbral_confianza', 'valor' => '0.75', 'tipo' => 'numero', 'descripcion' => 'Umbral de confianza para detección (0.0 - 1.0)'],
            ['clave' => 'categoria_plastico', 'valor' => '1', 'tipo' => 'booleano', 'descripcion' => 'Detectar plástico'],
            ['clave' => 'categoria_vidrio', 'valor' => '1', 'tipo' => 'booleano', 'descripcion' => 'Detectar vidrio'],
            ['clave' => 'categoria_metal', 'valor' => '1', 'tipo' => 'booleano', 'descripcion' => 'Detectar metal'],
            ['clave' => 'categoria_papel', 'valor' => '1', 'tipo' => 'booleano', 'descripcion' => 'Detectar papel'],
            ['clave' => 'categoria_organico', 'valor' => '1', 'tipo' => 'booleano', 'descripcion' => 'Detectar orgánico'],
            ['clave' => 'notificaciones_activas', 'valor' => '1', 'tipo' => 'booleano', 'descripcion' => 'Activar notificaciones'],
            ['clave' => 'alertas_umbral', 'valor' => '50', 'tipo' => 'numero', 'descripcion' => 'Cantidad para alertas'],
            ['clave' => 'video_fps', 'valor' => '30', 'tipo' => 'numero', 'descripcion' => 'FPS para procesamiento de video'],
            ['clave' => 'video_resolucion', 'valor' => '1080', 'tipo' => 'texto', 'descripcion' => 'Resolución de video (720, 1080, 1440)'],
        ];

        foreach ($configs as $config) {
            DB::table('configuraciones')->insert(array_merge($config, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }

    private function exportarConfiguracionXML()
    {
        $configuraciones = DB::table('configuraciones')->get();

        $xml = new \SimpleXMLElement('<configuracion></configuracion>');
        $xml->addChild('fecha_actualizacion', date('Y-m-d H:i:s'));

        foreach ($configuraciones as $config) {
            $item = $xml->addChild('parametro');
            $item->addChild('clave', $config->clave);
            $item->addChild('valor', $config->valor);
            $item->addChild('tipo', $config->tipo);
            $item->addChild('descripcion', $config->descripcion);
        }

        $xmlPath = storage_path('app/configuracion_sistema.xml');
        $xml->asXML($xmlPath);
    }

    public function descargarXML()
    {
        $xmlPath = storage_path('app/configuracion_sistema.xml');
        
        if (file_exists($xmlPath)) {
            return response()->download($xmlPath);
        }
        
        return redirect()->route('configuracion.index')->with('error', 'Archivo XML no encontrado');
    }
}
