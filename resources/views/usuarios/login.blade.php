<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-container {
            max-width: 450px;
            width: 100%;
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .form-group input:focus {
            border-color: #11998e;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 style="text-align: center; color: #11998e; margin-bottom: 10px;">🔐 Iniciar Sesión</h1>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">Sistema de Detección de Basura</p>

        @if(session('success'))
        <div style="background: #38ef7d; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="background: #eb3349; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
            ❌ {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('usuarios.login.post') }}">
            @csrf

            <div class="form-group">
                <label>📧 Email:</label>
                <input type="email" name="email" required placeholder="tu@email.com">
            </div>

            <div class="form-group">
                <label>🔑 Contraseña:</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 15px; font-size: 16px; margin-top: 10px;">
                Iniciar Sesión
            </button>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('usuarios.recuperar') }}" style="color: #11998e; text-decoration: none;">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <div style="text-align: center; margin-top: 15px;">
                <a href="/" style="color: #666; text-decoration: none;">
                    ← Volver al Inicio
                </a>
            </div>
        </form>
    </div>
</body>
</html>