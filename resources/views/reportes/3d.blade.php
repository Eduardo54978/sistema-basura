<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte - Comparación Animada</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        .selector-mes {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .selector-mes select {
            padding: 12px 20px;
            font-size: 16px;
            border: 3px solid #11998e;
            border-radius: 8px;
            background: white;
            color: #333;
            cursor: pointer;
            min-width: 150px;
            margin: 0 10px;
        }
        .selector-mes select:hover {
            border-color: #38ef7d;
        }
        .leyenda {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .leyenda-item {
            display: inline-flex;
            align-items: center;
            margin: 10px 15px;
        }
        .leyenda-color {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
     @include('layouts.menu')
    <div class="container">
        <h1>🎯 Reporte Tipo 3: Comparación por Categoría</h1>
        <div class="selector-mes">
            <h3 style="color: #11998e; margin-bottom: 15px;">📅 Selecciona Mes y Año</h3>
            
            <select id="selectAnio">
                <option value="2024" {{ $anioSeleccionado == 2024 ? 'selected' : '' }}>2024</option>
                <option value="2025" {{ $anioSeleccionado == 2025 ? 'selected' : '' }}>2025</option>
                <option value="2026" {{ $anioSeleccionado == 2026 ? 'selected' : '' }}>2026</option>
            </select>
            
            <select id="selectMes">
                <option value="1" {{ $mesSeleccionado == 1 ? 'selected' : '' }}>Enero</option>
                <option value="2" {{ $mesSeleccionado == 2 ? 'selected' : '' }}>Febrero</option>
                <option value="3" {{ $mesSeleccionado == 3 ? 'selected' : '' }}>Marzo</option>
                <option value="4" {{ $mesSeleccionado == 4 ? 'selected' : '' }}>Abril</option>
                <option value="5" {{ $mesSeleccionado == 5 ? 'selected' : '' }}>Mayo</option>
                <option value="6" {{ $mesSeleccionado == 6 ? 'selected' : '' }}>Junio</option>
                <option value="7" {{ $mesSeleccionado == 7 ? 'selected' : '' }}>Julio</option>
                <option value="8" {{ $mesSeleccionado == 8 ? 'selected' : '' }}>Agosto</option>
                <option value="9" {{ $mesSeleccionado == 9 ? 'selected' : '' }}>Septiembre</option>
                <option value="10" {{ $mesSeleccionado == 10 ? 'selected' : '' }}>Octubre</option>
                <option value="11" {{ $mesSeleccionado == 11 ? 'selected' : '' }}>Noviembre</option>
                <option value="12" {{ $mesSeleccionado == 12 ? 'selected' : '' }}>Diciembre</option>
            </select>
            
            <p style="color: #666; margin-top: 10px; font-size: 14px;">
                Las barras se animarán al cambiar de mes ✨
            </p>
        </div>
        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <svg id="grafico3d" width="800" height="500" style="border: 2px solid #e9ecef; border-radius: 10px;"></svg>
        </div>
        <div class="leyenda">
            <h3 style="color: #11998e; margin-bottom: 15px;">🎨 Leyenda de Categorías</h3>
            <div style="text-align: center;">
                <div class="leyenda-item">
                    <div class="leyenda-color" style="background: #FF6B6B;"></div>
                    <span><strong>Plástico</strong></span>
                </div>
                <div class="leyenda-item">
                    <div class="leyenda-color" style="background: #4ECDC4;"></div>
                    <span><strong>Vidrio</strong></span>
                </div>
                <div class="leyenda-item">
                    <div class="leyenda-color" style="background: #95A5A6;"></div>
                    <span><strong>Metal</strong></span>
                </div>
                <div class="leyenda-item">
                    <div class="leyenda-color" style="background: #F4D03F;"></div>
                    <span><strong>Papel</strong></span>
                </div>
                <div class="leyenda-item">
                    <div class="leyenda-color" style="background: #52B788;"></div>
                    <span><strong>Orgánico</strong></span>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <h2 style="color: #11998e; margin-bottom: 20px;">📊 Datos del Mes Seleccionado</h2>
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
                        <td style="padding: 15px;">
                            <span style="display: inline-block; width: 15px; height: 15px; border-radius: 3px; margin-right: 8px;
                                @if($dato->categoria == 'plastico') background: #FF6B6B;
                                @elseif($dato->categoria == 'vidrio') background: #4ECDC4;
                                @elseif($dato->categoria == 'metal') background: #95A5A6;
                                @elseif($dato->categoria == 'papel') background: #F4D03F;
                                @else background: #52B788;
                                @endif
                            "></span>
                            {{ ucfirst($dato->categoria) }}
                        </td>
                        <td style="padding: 15px; text-align: right; font-weight: bold; font-size: 18px;">
                            {{ $dato->total }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 20px; text-align: center; color: #999;">
                            No hay datos para este mes
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
    <script>
        const selectMes = document.getElementById('selectMes');
        const selectAnio = document.getElementById('selectAnio');
        
        if (selectMes) {
            selectMes.addEventListener('change', function() {
                const anio = selectAnio.value;
                window.location.href = `/reportes/3d?mes=${this.value}&anio=${anio}`;
            });
        }
        
        if (selectAnio) {
            selectAnio.addEventListener('change', function() {
                const mes = selectMes.value;
                window.location.href = `/reportes/3d?mes=${mes}&anio=${this.value}`;
            });
        }
    </script>
    <script src="{{ asset('modulos/reportes/js/3d.js') }}"></script>
</body>
</html>
