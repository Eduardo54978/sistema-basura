<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Análisis</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
</head>
<body>
    @include('layouts.menu')
    
    <div class="container">
        <h1>🔍 Detalle del Análisis</h1>

        <div style="background: white; padding: 40px; border-radius: 15px; margin: 20px 0; max-width: 800px; margin-left: auto; margin-right: auto;">
            <h2 style="color: #11998e; margin-bottom: 20px;">📁 {{ $deteccion->nombre_archivo }}</h2>
            
            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
                <strong style="color: #11998e;">Categoría:</strong>
                <p style="margin: 5px 0 0 0; font-size: 18px;">
                    @if($deteccion->categoria == 'plastico') 🔴
                    @elseif($deteccion->categoria == 'vidrio') 🔵
                    @elseif($deteccion->categoria == 'metal') ⚪
                    @elseif($deteccion->categoria == 'papel') 🟡
                    @else 🟢
                    @endif
                    {{ ucfirst($deteccion->categoria) }}
                </p>
            </div>

            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
                <strong style="color: #11998e;">Cantidad Detectada:</strong>
                <p style="margin: 5px 0 0 0; font-size: 24px; font-weight: bold;">{{ $deteccion->cantidad }}</p>
            </div>

            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
                <strong style="color: #11998e;">Fecha de Detección:</strong>
                <p style="margin: 5px 0 0 0;">{{ date('d/m/Y', strtotime($deteccion->fecha_deteccion)) }}</p>
            </div>

            <h3 style="color: #11998e; margin: 30px 0 15px 0;">Todas las detecciones de este archivo:</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #11998e; color: white;">
                        <th style="padding: 10px; text-align: left;">Categoría</th>
                        <th style="padding: 10px; text-align: center;">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detecciones_archivo as $item)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">{{ ucfirst($item->categoria) }}</td>
                        <td style="padding: 10px; text-align: center; font-weight: bold;">{{ $item->cantidad }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 30px;">
                <a href="{{ route('historial.index') }}" class="btn">← Volver al Historial</a>
            </div>
        </div>
    </div>
</body>
</html>