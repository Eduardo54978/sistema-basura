<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ejecuciones</title>
    <link rel="stylesheet" href="{{ asset('modulos/automatizacion/css/automatizacion.css') }}">
</head>
<body>
     @include('layouts.menu')
    <div class="container">
        <h1>📜 Historial de Ejecuciones</h1>

        <div class="card">
            <h2 style="color: #667eea; margin-bottom: 20px;">Últimas 50 Ejecuciones</h2>
            
            @if($historial->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Tarea</th>
                        <th>Estado</th>
                        <th>Mensaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historial as $item)
                    <tr>
                        <td>{{ $item->created_at }}</td>
                        <td><strong>{{ $item->tarea_nombre }}</strong></td>
                        <td>
                            @if($item->estado == 'completada')
                                <span class="badge badge-baja">✅ Completada</span>
                            @elseif($item->estado == 'fallida')
                                <span class="badge badge-alta">❌ Fallida</span>
                            @else
                                <span class="badge badge-media">⏳ En Proceso</span>
                            @endif
                        </td>
                        <td>{{ $item->mensaje }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align: center; color: #999; padding: 40px;">
                No hay ejecuciones registradas aún.
            </p>
            @endif
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('automatizacion.index') }}" class="btn">← Volver a Automatización</a>
        </div>
    </div>
</body>
</html>
