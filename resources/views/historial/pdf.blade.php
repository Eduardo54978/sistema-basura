<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Análisis - Exportación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: white;
        }
        h1 {
            color: #11998e;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #11998e;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>📜 Historial de Análisis Completo</h1>
    <p style="text-align: center; color: #666;">Generado el: {{ date('d/m/Y H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Archivo</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Fecha Detección</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detecciones as $item)
            <tr>
                <td>{{ $item->nombre_archivo }}</td>
                <td>{{ ucfirst($item->categoria) }}</td>
                <td style="text-align: center;"><strong>{{ $item->cantidad }}</strong></td>
                <td>{{ date('d/m/Y', strtotime($item->fecha_deteccion)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Total de registros: {{ count($detecciones) }}</p>
        <p>Sistema de Detección y Clasificación de Basura</p>
    </div>
</body>
</html>