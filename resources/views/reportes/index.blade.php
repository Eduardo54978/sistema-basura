<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Detección de Basura - Reportes</title>
  <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
</head>
<body>
     @include('layouts.menu')
    <div class="container">
        <h1>🗑️ Sistema de Detección y Clasificación de Basura</h1>
        <h2 style="color: white; text-align: center; margin-bottom: 50px;">Módulo de Reportes y Estadísticas</h2>
        
        <div class="reportes-grid">
            <div class="reporte-card">
                <h2>📊 Reporte Tipo 1</h2>
                <h3>Conteo por Categoría</h3>
                <p>Visualiza cuántos residuos se detectaron de cada tipo: plástico, vidrio, metal, papel y orgánicos.</p>
                <a href="{{ route('reportes.conteo') }}" class="btn">Ver Reporte</a>
            </div>

            <div class="reporte-card">
                <h2>📅 Reporte Tipo 2</h2>
                <h3>Análisis por Fecha</h3>
                <p>Observa cómo varían las detecciones a lo largo del tiempo con gráficos temporales.</p>
                <a href="{{ route('reportes.fecha') }}" class="btn">Ver Reporte</a>
            </div>

            <div class="reporte-card">
                <h2>🎮 Reporte Tipo 3</h2>
                <h3>Visualización 3D</h3>
                <p>Explora los datos en un gráfico tridimensional interactivo con SVG y JavaScript.</p>
                <a href="{{ route('reportes.3d') }}" class="btn">Ver Reporte</a>
            </div>
        </div>
    </div>
</body>
</html>