<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Automatización</title>
    <link rel="stylesheet" href="{{ asset('modulos/automatizacion/css/automatizacion.css') }}">
</head>
<body>
     @include('layouts.menu')
    <div class="container">
        <h1>⚙️ Módulo de Automatización de Tareas</h1>

        @if(session('success'))
        <div style="background: #38ef7d; color: white; padding: 15px; border-radius: 10px; margin: 20px 0;">
            ✅ {{ session('success') }}
        </div>
        @endif
        <div class="card">
            <h2 style="color: #667eea; margin-bottom: 20px;">➕ Crear Nueva Tarea Programada</h2>
            
            <form method="POST" action="{{ route('automatizacion.store') }}">
                @csrf
                
                <div class="form-group">
                    <label>Nombre de la Tarea:</label>
                    <input type="text" name="nombre" required placeholder="Ej: Generar reporte diario">
                </div>

                <div class="form-group">
                    <label>Comando (Usa gramática):</label>
                    <textarea name="comando" rows="3" required placeholder='Ej: generar_reporte cada día a las 08:00'></textarea>
                    <small style="color: #666;">
                        Ejemplos: "analizar imagen.jpg cada semana a las 10:00" | "detectar basura cada mes a las 15:30"
                    </small>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Frecuencia:</label>
                        <select name="frecuencia" required>
                            <option value="diario">Diario</option>
                            <option value="semanal">Semanal</option>
                            <option value="mensual">Mensual</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Hora de Ejecución:</label>
                        <input type="time" name="hora" required>
                    </div>

                    <div class="form-group">
                        <label>Prioridad:</label>
                        <select name="prioridad" required>
                            <option value="alta">🔴 Alta (Urgente)</option>
                            <option value="media" selected>🟡 Media (Normal)</option>
                            <option value="baja">🟢 Baja (Diferible)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Crear Tarea</button>
            </form>
        </div>
        <div class="card">
            <h2 style="color: #667eea; margin-bottom: 20px;">📋 Tareas Programadas</h2>
            
            @if($tareas->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Comando</th>
                        <th>Frecuencia</th>
                        <th>Hora</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tareas as $tarea)
                    <tr>
                        <td><strong>{{ $tarea->nombre }}</strong></td>
                        <td><code>{{ $tarea->comando }}</code></td>
                        <td>{{ ucfirst($tarea->frecuencia) }}</td>
                        <td>{{ $tarea->hora }}</td>
                        <td>
                            <span class="badge badge-{{ $tarea->prioridad }}">
                                {{ strtoupper($tarea->prioridad) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($tarea->estado) }}</td>
                        <td>
                            <form method="POST" action="{{ route('automatizacion.ejecutar', $tarea->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning" style="padding: 8px 15px;">▶️ Ejecutar</button>
                            </form>
                            
                            <form method="POST" action="{{ route('automatizacion.destroy', $tarea->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 8px 15px;" onclick="return confirm('¿Eliminar esta tarea?')">🗑️ Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align: center; color: #999; padding: 40px;">
                No hay tareas programadas aún. Crea una arriba.
            </p>
            @endif
        </div>

        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('automatizacion.historial') }}" class="btn">📜 Ver Historial de Ejecuciones</a>
            <a href="/" class="btn btn-success">🏠 Volver al Inicio</a>
        </div>
    </div>
   
</body>
</html>