<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte - Conteo por Categoría</title>
<link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        .filtros {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .filtros input, .filtros select {
            padding: 10px;
            margin: 5px;
            border: 2px solid #11998e;
            border-radius: 5px;
        }
        .filtros button {
            padding: 10px 20px;
            background: #11998e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .filtros button:hover {
            background: #38ef7d;
        }
    </style>
</head>
<body>
     @include('layouts.menu')
    <div class="container">
        <h1>📊 Reporte Tipo 1: Conteo por Categoría</h1>
        <div class="filtros">
            <h3 style="color: #11998e; margin-bottom: 15px;">🔍 Filtrar por Fecha</h3>
            <form method="GET" action="{{ route('reportes.conteo') }}">
                <label>Fecha Exacta:</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}">
                
                <label>O por Mes:</label>
                <select name="mes">
                    <option value="">Todos los meses</option>
                    <option value="1" {{ request('mes') == 1 ? 'selected' : '' }}>Enero</option>
                    <option value="2" {{ request('mes') == 2 ? 'selected' : '' }}>Febrero</option>
                    <option value="3" {{ request('mes') == 3 ? 'selected' : '' }}>Marzo</option>
                    <option value="4" {{ request('mes') == 4 ? 'selected' : '' }}>Abril</option>
                    <option value="5" {{ request('mes') == 5 ? 'selected' : '' }}>Mayo</option>
                    <option value="6" {{ request('mes') == 6 ? 'selected' : '' }}>Junio</option>
                    <option value="7" {{ request('mes') == 7 ? 'selected' : '' }}>Julio</option>
                    <option value="8" {{ request('mes') == 8 ? 'selected' : '' }}>Agosto</option>
                    <option value="9" {{ request('mes') == 9 ? 'selected' : '' }}>Septiembre</option>
                    <option value="10" {{ request('mes') == 10 ? 'selected' : '' }}>Octubre</option>
                    <option value="11" {{ request('mes') == 11 ? 'selected' : '' }}>Noviembre</option>
                    <option value="12" {{ request('mes') == 12 ? 'selected' : '' }}>Diciembre</option>
                </select>
                
                <label>O por Año:</label>
                <input type="number" name="anio" placeholder="2025" value="{{ request('anio') }}" min="2020" max="2030">
                
                <button type="submit">Aplicar Filtros</button>
                <a href="{{ route('reportes.conteo') }}" style="margin-left: 10px; color: #11998e; text-decoration: none;">Limpiar</a>
            </form>
        </div>

        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <svg id="graficoConteo" width="800" height="400" style="border: 1px solid #ddd;"></svg>
        </div>

        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <h2 style="color: #11998e; margin-bottom: 20px;">Datos Detectados</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #11998e; color: white;">
                        <th style="padding: 15px; text-align: left;">Categoría</th>
                        <th style="padding: 15px; text-align: right;">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datos as $dato)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 15px;">{{ ucfirst($dato->categoria) }}</td>
                        <td style="padding: 15px; text-align: right; font-weight: bold;">{{ $dato->total }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 20px; text-align: center; color: #999;">
                            No hay datos para los filtros seleccionados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('reportes.index') }}" class="btn" style="margin-top: 20px;">← Volver a Reportes</a>
    </div>

    <script type="application/json" id="datos-json">
        @json($datos)
    </script>
  <script src="{{ asset('modulos/reportes/js/conteo.js') }}"></script>
</body>
</html>