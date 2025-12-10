<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Archivos</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        .file-upload-zone {
            border: 3px dashed #11998e;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            background: white;
            margin: 20px 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-upload-zone:hover {
            border-color: #38ef7d;
            background: #f0fff4;
        }
        
        .file-upload-zone input[type="file"] {
            display: none;
        }
        
        .file-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .file-info {
            flex: 1;
        }
        
        .file-actions button {
            margin-left: 10px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .alert-success {
            background: #38ef7d;
            color: white;
        }
        
        .alert-error {
            background: #eb3349;
            color: white;
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    
    <div class="container">
        <h1>📁 Gestión de Archivos Multimedia</h1>

        @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            ❌ {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            ❌ {{ $errors->first() }}
        </div>
        @endif

        <!-- ZONA DE SUBIDA -->
        <div class="file-upload-zone" onclick="document.getElementById('fileInput').click()">
            <h2 style="color: #11998e; margin-bottom: 15px;">📤 Subir Archivo</h2>
            <p style="color: #666; margin-bottom: 20px;">
                Haz clic aquí o arrastra archivos<br>
                <small>Formatos permitidos: JPG, PNG, MP4, AVI (Máx. 50MB)</small>
            </p>
            
            <form id="uploadForm" method="POST" action="{{ route('archivos.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" id="fileInput" name="archivo" accept=".jpg,.jpeg,.png,.mp4,.avi" onchange="document.getElementById('uploadForm').submit()">
            </form>
            
            <div style="margin-top: 20px;">
                <strong style="color: #11998e;">Validación con Expresión Regular:</strong><br>
                <code style="background: #f0f0f0; padding: 5px 10px; border-radius: 5px;">
                    ^[a-zA-Z0-9_-]+\.(jpg|jpeg|png|mp4|avi)$
                </code>
            </div>
        </div>

        <!-- INFORMACIÓN DE CONCEPTOS APLICADOS -->
        <div style="background: white; padding: 25px; border-radius: 15px; margin: 20px 0;">
            <h3 style="color: #11998e; margin-bottom: 15px;">🔧 Conceptos Aplicados en este Módulo</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div style="padding: 15px; background: #f0fff4; border-left: 4px solid #11998e; border-radius: 5px;">
                    <strong>✅ Expresiones Regulares</strong>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">
                        Validación de nombres de archivo y formatos permitidos
                    </p>
                </div>
                
                <div style="padding: 15px; background: #f0fff4; border-left: 4px solid #38ef7d; border-radius: 5px;">
                    <strong>✅ XML</strong>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">
                        Almacenamiento de metadatos de archivos subidos
                    </p>
                </div>
                
                <div style="padding: 15px; background: #f0fff4; border-left: 4px solid #11998e; border-radius: 5px;">
                    <strong>✅ Patrón Strategy</strong>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">
                        Diferentes estrategias de procesamiento según tipo
                    </p>
                </div>
            </div>
        </div>

        <!-- LISTA DE ARCHIVOS -->
        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <h2 style="color: #11998e; margin-bottom: 20px;">📋 Archivos Subidos ({{ count($listaArchivos) }})</h2>
            
            @if(count($listaArchivos) > 0)
                @foreach($listaArchivos as $archivo)
                <div class="file-card">
                    <div class="file-info">
                        <h3 style="margin: 0 0 10px 0; color: #333;">
                            @if(in_array(pathinfo($archivo['nombre'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                🖼️
                            @else
                                🎬
                            @endif
                            {{ $archivo['nombre'] }}
                        </h3>
                        <p style="margin: 0; color: #666; font-size: 14px;">
                            <strong>Tamaño:</strong> {{ number_format($archivo['tamano'] / 1024, 2) }} KB |
                            <strong>Fecha:</strong> {{ date('d/m/Y H:i', $archivo['fecha']) }}
                        </p>
                    </div>
                    
                    <div class="file-actions">
                        <a href="{{ $archivo['url'] }}" target="_blank" class="btn" style="padding: 10px 20px;">👁️ Ver</a>
                        
                        <form method="POST" action="{{ route('archivos.destroy', $archivo['nombre']) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="padding: 10px 20px; background: #eb3349;" onclick="return confirm('¿Eliminar este archivo?')">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            @else
                <p style="text-align: center; color: #999; padding: 40px;">
                    No hay archivos subidos aún. Sube uno usando el área de arriba.
                </p>
            @endif
        </div>

        <!-- METADATOS XML -->
        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <h3 style="color: #11998e; margin-bottom: 15px;">📄 Estructura XML de Metadatos</h3>
            <p style="color: #666; margin-bottom: 15px;">
                Cada archivo subido genera un archivo XML con sus metadatos en: <code>storage/app/metadatos/</code>
            </p>
            <pre style="background: #f5f5f5; padding: 20px; border-radius: 10px; overflow-x: auto; border: 1px solid #ddd;">
&lt;archivo&gt;
    &lt;nombre&gt;imagen1.jpg&lt;/nombre&gt;
    &lt;tamano&gt;245678&lt;/tamano&gt;
    &lt;tipo&gt;image/jpeg&lt;/tipo&gt;
    &lt;extension&gt;jpg&lt;/extension&gt;
    &lt;fecha_subida&gt;2025-11-29 10:30:00&lt;/fecha_subida&gt;
&lt;/archivo&gt;</pre>
        </div>
    </div>
</body>
</html>