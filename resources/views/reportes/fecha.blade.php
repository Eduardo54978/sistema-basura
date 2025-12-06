<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte - Análisis por Mes</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        .filtros {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .filtros select {
            padding: 10px 20px;
            margin: 5px;
            border: 2px solid #11998e;
            border-radius: 5px;
            font-size: 14px;
        }
        .filtros button {
            padding: 10px 20px;
            background: #11998e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .filtros button:hover {
            background: #38ef7d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📅 Reporte Tipo 2: Análisis por Mes</h1>
        
        <!-- FILTRO DE AÑO -->
        <div class="filtros">
            <h3 style="color: #11998e; margin-bottom: 15px;">🔍 Filtrar por Año</h3>
            <form method="GET" action="{{ route('reportes.fecha') }}">
                <select name="anio">
                    <option value="">Todos los años</option>
                    <option value="2024" {{ request('anio') == 2024 ? 'selected' : '' }}>2024</option>
                    <option value="2025" {{ request('anio') == 2025 ? 'selected' : '' }}>2025</option>
                    <option value="2026" {{ request('anio') == 2026 ? 'selected' : '' }}>2026</option>
                </select>
                <button type="submit">Aplicar Filtro</button>
                <a href="{{ route('reportes.fecha') }}" style="margin-left: 10px; color: #11998e; text-decoration: none;">Limpiar</a>
            </form>
        </div>

        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <svg id="graficoFecha" width="800" height="400" style="border: 1px solid #ddd;"></svg>
        </div>

        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <h2 style="color: #11998e; margin-bottom: 20px;">Detecciones por Mes</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #11998e; color: white;">
                        <th style="padding: 15px; text-align: left;">Mes</th>
                        <th style="padding: 15px; text-align: right;">Cantidad Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datos as $dato)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 15px;">
                            @php
                                $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            @endphp
                            {{ $meses[$dato->mes] }}
                        </td>
                        <td style="padding: 15px; text-align: right; font-weight: bold;">{{ $dato->total }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 20px; text-align: center; color: #999;">
                            No hay datos registrados para este año
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

    <script src="{{ asset('modulos/reportes/js/fecha.js') }}"></script>
</body>
</html>