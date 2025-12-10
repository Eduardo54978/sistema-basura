<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sistema</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        .config-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .config-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .config-item:last-child {
            border-bottom: none;
        }
        .config-label {
            flex: 1;
        }
        .config-label h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .config-label p {
            margin: 0;
            color: #666;
            font-size: 13px;
        }
        .config-control {
            min-width: 150px;
            text-align: right;
        }
        .config-control input[type="number"],
        .config-control input[type="text"],
        .config-control select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #11998e;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    
    <div class="container">
        <h1>⚙️ Configuración del Sistema</h1>

        @if(session('success'))
        <div style="background: #38ef7d; color: white; padding: 15px; border-radius: 10px; margin: 20px 0;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('configuracion.update') }}">
            @csrf

            <!-- DETECCIÓN -->
            <div class="config-section">
                <h2 style="color: #11998e; margin-bottom: 20px;">🎯 Parámetros de Detección</h2>
                
                @foreach($configuraciones as $config)
                    @if($config->clave == 'umbral_confianza')
                    <div class="config-item">
                        <div class="config-label">
                            <h4>Umbral de Confianza</h4>
                            <p>{{ $config->descripcion }}</p>
                        </div>
                        <div class="config-control">
                            <input type="number" name="{{ $config->clave }}" value="{{ $config->valor }}" min="0" max="1" step="0.05">
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- CATEGORÍAS ACTIVAS -->
            <div class="config-section">
                <h2 style="color: #11998e; margin-bottom: 20px;">📦 Categorías de Detección</h2>
                <p style="color: #666; margin-bottom: 20px;">Activa o desactiva las categorías que deseas detectar</p>
                
                @foreach($configuraciones as $config)
                    @if(str_starts_with($config->clave, 'categoria_'))
                    <div class="config-item">
                        <div class="config-label">
                            <h4>{{ str_replace('categoria_', '', ucfirst($config->clave)) }}</h4>
                            <p>{{ $config->descripcion }}</p>
                        </div>
                        <div class="config-control">
                            <label class="switch">
                                <input type="hidden" name="{{ $config->clave }}" value="0">
                                <input type="checkbox" name="{{ $config->clave }}" value="1" {{ $config->valor == '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- NOTIFICACIONES -->
            <div class="config-section">
                <h2 style="color: #11998e; margin-bottom: 20px;">🔔 Notificaciones y Alertas</h2>
                
                @foreach($configuraciones as $config)
                    @if($config->clave == 'notificaciones_activas')
                    <div class="config-item">
                        <div class="config-label">
                            <h4>Notificaciones Activas</h4>
                            <p>{{ $config->descripcion }}</p>
                        </div>
                        <div class="config-control">
                            <label class="switch">
                                <input type="hidden" name="{{ $config->clave }}" value="0">
                                <input type="checkbox" name="{{ $config->clave }}" value="1" {{ $config->valor == '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    @endif

                    @if($config->clave == 'alertas_umbral')
                    <div class="config-item">
                        <div class="config-label">
                            <h4>Umbral de Alertas</h4>
                            <p>{{ $config->descripcion }}</p>
                        </div>
                        <div class="config-control">
                            <input type="number" name="{{ $config->clave }}" value="{{ $config->valor }}" min="1" max="1000">
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- PARÁMETROS DE VIDEO -->
            <div class="config-section">
                <h2 style="color: #11998e; margin-bottom: 20px;">🎬 Procesamiento de Video</h2>
                
                @foreach($configuraciones as $config)
                    @if($config->clave == 'video_fps')
                    <div class="config-item">
                        <div class="config-label">
                            <h4>FPS (Frames por Segundo)</h4>
                            <p>{{ $config->descripcion }}</p>
                        </div>
                        <div class="config-control">
                            <select name="{{ $config->clave }}">
                                <option value="15" {{ $config->valor == '15' ? 'selected' : '' }}>15 FPS</option>
                                <option value="24" {{ $config->valor == '24' ? 'selected' : '' }}>24 FPS</option>
                                <option value="30" {{ $config->valor == '30' ? 'selected' : '' }}>30 FPS</option>
                                <option value="60" {{ $config->valor == '60' ? 'selected' : '' }}>60 FPS</option>
                            </select>
                        </div>
                    </div>
                    @endif

                    @if($config->clave == 'video_resolucion')
                    <div class="config-item">
                        <div class="config-label">
                            <h4>Resolución de Video</h4>
                            <p>{{ $config->descripcion }}</p>
                        </div>
                        <div class="config-control">
                            <select name="{{ $config->clave }}">
                                <option value="720" {{ $config->valor == '720' ? 'selected' : '' }}>720p (HD)</option>
                                <option value="1080" {{ $config->valor == '1080' ? 'selected' : '' }}>1080p (Full HD)</option>
                                <option value="1440" {{ $config->valor == '1440' ? 'selected' : '' }}>1440p (2K)</option>
                                <option value="2160" {{ $config->valor == '2160' ? 'selected' : '' }}>2160p (4K)</option>
                            </select>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- INFORMACIÓN XML -->
            <div class="config-section">
                <h3 style="color: #11998e; margin-bottom: 15px;">📄 Almacenamiento en XML</h3>
                <p style="color: #666; margin-bottom: 15px;">
                    Las configuraciones se guardan automáticamente en formato XML en: <br>
                    <code style="background: #f5f5f5; padding: 5px 10px; border-radius: 5px;">storage/app/configuracion_sistema.xml</code>
                </p>
                <a href="{{ route('configuracion.xml') }}" class="btn">📥 Descargar XML de Configuración</a>
            </div>

            <!-- BOTONES -->
            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn" style="flex: 1;">💾 Guardar Configuración</button>
                <a href="/" class="btn" style="flex: 1; text-align: center; background: #666;">← Volver al Inicio</a>
            </div>
        </form>

        <!-- CONCEPTOS APLICADOS -->
        <div class="config-section" style="margin-top: 30px;">
            <h3 style="color: #11998e; margin-bottom: 15px;">🔧 Conceptos Aplicados en este Módulo</h3>
            <ul style="color: #666; line-height: 1.8;">
                <li><strong>XML:</strong> Almacenamiento persistente de configuraciones del sistema</li>
                <li><strong>Patrón Strategy:</strong> Diferentes estrategias de procesamiento según configuración</li>
                <li><strong>Validación:</strong> Rangos y valores permitidos para cada parámetro</li>
                <li><strong>Persistencia:</strong> Las configuraciones se mantienen entre sesiones</li>
            </ul>
        </div>
    </div>
</body>
</html>