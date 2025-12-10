<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ArchivosController extends Controller
{
    public function index()
    {
        return view('inicio-subida');
    }

    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:jpg,jpeg,png,mp4,avi|max:51200'
        ]);

        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        
        // Validar con expresión regular
        if (!preg_match('/^[a-zA-Z0-9_-]+\.(jpg|jpeg|png|mp4|avi)$/i', $nombreOriginal)) {
            return back()->with('error', 'Nombre de archivo inválido. Use solo letras, números, guiones y guiones bajos.');
        }

        // Guardar archivo
        $ruta = $archivo->storeAs('uploads', $nombreOriginal, 'public');
        
        // SIMULAR DETECCIÓN AUTOMÁTICA DE BASURA (IA)
        $detecciones = $this->detectarBasura($nombreOriginal);
        
        // Guardar detecciones en la base de datos
        foreach ($detecciones as $categoria => $cantidad) {
            if ($cantidad > 0) {
                DB::table('detecciones')->insert([
                    'nombre_archivo' => $nombreOriginal,
                    'categoria' => $categoria,
                    'cantidad' => $cantidad,
                    'fecha_deteccion' => now()->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        // Guardar metadatos en XML
        $this->guardarMetadatosXML($nombreOriginal, $archivo, $detecciones);

        return back()->with([
            'success' => true,
            'archivo' => $nombreOriginal,
            'detecciones' => $detecciones
        ]);
    }

    private function detectarBasura($nombreArchivo)
    {
        // SIMULACIÓN DE IA: Genera cantidades aleatorias para cada categoría
        // En un sistema real, aquí iría el algoritmo de visión por computadora
        
        $detecciones = [
            'plastico' => rand(0, 15),
            'vidrio' => rand(0, 10),
            'metal' => rand(0, 8),
            'papel' => rand(0, 12),
            'organico' => rand(0, 7)
        ];
        
        return $detecciones;
    }

    private function guardarMetadatosXML($nombre, $archivo, $detecciones)
    {
        $metadatosDir = storage_path('app/metadatos');
        if (!file_exists($metadatosDir)) {
            mkdir($metadatosDir, 0755, true);
        }

        $xml = new \SimpleXMLElement('<archivo></archivo>');
        $xml->addChild('nombre', $nombre);
        $xml->addChild('tamano', $archivo->getSize());
        $xml->addChild('tipo', $archivo->getMimeType());
        $xml->addChild('extension', $archivo->getClientOriginalExtension());
        $xml->addChild('fecha_subida', date('Y-m-d H:i:s'));
        
        $deteccionesNode = $xml->addChild('detecciones');
        foreach ($detecciones as $categoria => $cantidad) {
            $item = $deteccionesNode->addChild('item');
            $item->addChild('categoria', $categoria);
            $item->addChild('cantidad', $cantidad);
        }
        
        $xmlPath = $metadatosDir . '/' . pathinfo($nombre, PATHINFO_FILENAME) . '.xml';
        $xml->asXML($xmlPath);
    }
}