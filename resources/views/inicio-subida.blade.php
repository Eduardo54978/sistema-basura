<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Detección de Basura</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .upload-container {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .upload-zone {
            border: 4px dashed #11998e;
            border-radius: 15px;
            padding: 60px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, rgba(17,153,142,0.05) 0%, rgba(56,239,125,0.05) 100%);
        }
        
        .upload-zone:hover {
            border-color: #38ef7d;
            background: linear-gradient(135deg, rgba(17,153,142,0.1) 0%, rgba(56,239,125,0.1) 100%);
            transform: scale(1.02);
        }
        
        .upload-zone input {
            display: none;
        }
        
        .resultado {
            margin-top: 30px;
            padding: 30px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border-radius: 15px;
        }
        
        .deteccion-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }
        
        .deteccion-item:last-child {
            border-bottom: none;
        }
        
        .btn-ver-reportes {
            margin-top: 20px;
            padding: 15px 40px;
            background: white;
            color: #11998e;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .btn-ver-reportes:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    
    <div class="upload-container">
        <h1 style="text-align: center; color: #11998e; margin-bottom: 10px;">🗑️ Sistema de Detección de Basura</h1>
        <p style="text-align: center; color: #666; margin-bottom: 40px;">Sube una imagen o video para detectar automáticamente los residuos</p>
        
        @if(session('error'))
        <div style="background: #eb3349; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            ❌ {{ session('error') }}
        </div>
        @endif
        
        <form method="POST" action="{{ route('archivo.subir') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="upload-zone" onclick="document.getElementById('archivoInput').click()">
                <h2 style="color: #11998e; margin-bottom: 20px;">📤 Arrastra o Haz Clic para Subir</h2>
                <p style="color: #666; font-size: 18px; margin-bottom: 15px;">
                    Formatos permitidos: JPG, PNG, MP4, AVI
                </p>
                <p style="color: #999; font-size: 14px;">
                    Tamaño máximo: 50MB
                </p>
                <input type="file" id="archivoInput" name="archivo" accept=".jpg,.jpeg,.png,.mp4,.avi" onchange="document.getElementById('uploadForm').submit()">
            </div>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: #666; font-size: 13px;">
            <strong>Validación por Expresión Regular:</strong> <code>^[a-zA-Z0-9_-]+\.(jpg|jpeg|png|mp4|avi)$</code>
        </p>
        
        @if(session('success'))
        <div class="resultado">
            <h2 style="margin: 0 0 20px 0;">✅ Detección Completada</h2>
            <p style="margin: 0 0 20px 0; opacity: 0.9;">Archivo: <strong>{{ session('archivo') }}</strong></p>
            
            <h3 style="margin: 20px 0 15px 0;">Residuos Detectados:</h3>
            
            @php
                $total = 0;
                $detecciones = session('detecciones');
            @endphp
            
            @foreach($detecciones as $categoria => $cantidad)
                @if($cantidad > 0)
                    @php $total += $cantidad; @endphp
                    <div class="deteccion-item">
                        <span style="font-size: 18px;">
                            @if($categoria == 'plastico') 🔴
                            @elseif($categoria == 'vidrio') 🔵
                            @elseif($categoria == 'metal') ⚪
                            @elseif($categoria == 'papel') 🟡
                            @else 🟢
                            @endif
                            {{ ucfirst($categoria) }}
                        </span>
                        <strong style="font-size: 20px;">{{ $cantidad }}</strong>
                    </div>
                @endif
            @endforeach
            
            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid rgba(255,255,255,0.5);">
                <div class="deteccion-item" style="border: none;">
                    <span style="font-size: 20px;"><strong>TOTAL:</strong></span>
                    <strong style="font-size: 24px;">{{ $total }}</strong>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('reportes.index') }}">
                    <button class="btn-ver-reportes">📊 Ver Estadísticas Completas</button>
                </a>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
