<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Análisis</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 48px;
            font-weight: bold;
            color: #11998e;
            margin: 10px 0;
        }
        .filtros {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .filtros input, .filtros select {
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin: 5px;
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    
    <div class="container">
        <h1>📜 Historial de Análisis</h1>

        @if(session('success'))
        <div style="background: #38ef7d; color: white; padding: 15px; border-radius: 10px; margin: 20px 0;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <!-- ESTADÍSTICAS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div style="font-size: 40px;">📊</div>
                <div class="stat-number">{{ $total_analisis }}</div>
                <div style="color: #666;">Total Análisis</div>
            </div>
            <div class="stat-card">
                <div style="font-size: 40px;">📁</div>
                <div class="stat-number">{{ $total_archivos }}</div>
                <div style="color: #666;">Archivos Procesados</div>
            </div>
            <div class="stat-card">
                <div style="font-size: 40px;">🗑️</div>
                <div class="stat-number">{{ $total_residuos }}</div>
                <div style="color: #666;">Residuos Detectados</div>
            </div>
        </div>

        <!-- FILTROS -->
        <div class="filtros">
            <h3 style="color: #11998e; margin-bottom: 15px;">🔍 Filtrar Análisis</h3>
            <form method="GET" action="{{ route('historial.index') }}">
                <input type="date" name="fecha" value="{{ request('fecha') }}" placeholder="Fecha">
                
                <select name="categoria">
                    <option value="">Todas las categorías</option>
                    <option value="plastico" {{ request('categoria') == 'plastico' ? 'selected' : '' }}>Plástico</option>
                    <option value="vidrio" {{ request('categoria') == 'vidrio' ? 'selected' : '' }}>Vidrio</option>
                    <option value="metal" {{ request('categoria') == 'metal' ? 'selected' : '' }}>Metal</option>
                    <option value="papel" {{ request('categoria') == 'papel' ? 'selected' : '' }}>Papel</option>
                    <option value="organico" {{ request('categoria') == 'organico' ? 'selected' : '' }}>Orgánico</option>
                </select>
                
                <input type="text" name="archivo" value="{{ request('archivo') }}" placeholder="Nombre de archivo">
                
                <button type="submit" class="btn" style="padding: 10px 20px;">Aplicar Filtros</button>
                <a href="{{ route('historial.index') }}" class="btn" style="padding: 10px 20px; background: #666;">Limpiar</a>
            </form>
            <p style="color: #666; margin-top: 10px; font-size: 13px;">
                <strong>Expresión Regular aplicada:</strong> Filtrado de texto con patrones LIKE
            </p>
        </div>

        <!-- ACCIONES -->
        <div style="background: white; padding: 20px; border-radius: 15px; margin: 20px 0; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <a href="{{ route('historial.exportar.xml') }}" class="btn">📄 Exportar XML</a>
                <a href="{{ route('historial.exportar.pdf') }}" class="btn">📑 Exportar HTML/PDF</a>
            </div>
            <form method="POST" action="{{ route('historial.destroyAll') }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="background: #eb3349;" onclick="return confirm('¿Eliminar TODO el historial?')">
                    🗑️ Eliminar Todo
                </button>
            </form>
        </div>

        <!-- TABLA DE ANÁLISIS -->
        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <h2 style="color: #11998e; margin-bottom: 20px;">Registros de Análisis ({{ $analisis->total() }})</h2>
            
            @if($analisis->count() > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #11998e; color: white;">
                        <th style="padding: 15px; text-align: left;">Archivo</th>
                        <th style="padding: 15px; text-align: left;">Categoría</th>
                        <th style="padding: 15px; text-align: center;">Cantidad</th>
                        <th style="padding: 15px; text-align: center;">Fecha</th>
                        <th style="padding: 15px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analisis as $item)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 15px;">
                            <strong>{{ $item->nombre_archivo }}</strong>
                        </td>
                        <td style="padding: 15px;">
                            @if($item->categoria == 'plastico') 🔴
                            @elseif($item->categoria == 'vidrio') 🔵
                            @elseif($item->categoria == 'metal') ⚪
                            @elseif($item->categoria == 'papel') 🟡
                            @else 🟢
                            @endif
                            {{ ucfirst($item->categoria) }}
                        </td>
                        <td style="padding: 15px; text-align: center; font-weight: bold; font-size: 18px;">
                            {{ $item->cantidad }}
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            {{ date('d/m/Y', strtotime($item->fecha_deteccion)) }}
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="{{ route('historial.show', $item->id) }}" class="btn" style="padding: 8px 15px;">👁️ Ver</a>
                            <form method="POST" action="{{ route('historial.destroy', $item->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 8px 15px; background: #eb3349;" onclick="return confirm('¿Eliminar?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- PAGINACIÓN -->
            <div style="margin-top: 20px; text-align: center;">
                {{ $analisis->links() }}
            </div>
            @else
            <p style="text-align: center; padding: 40px; color: #999;">
                No hay análisis registrados
            </p>
            @endif
        </div>
    </div>
</body>
</html>