<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
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
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        .form-group input:focus {
            border-color: #11998e;
            outline: none;
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    
    <div class="form-container">
        <h1 style="text-align: center; color: #11998e; margin-bottom: 30px;">✏️ Editar Usuario</h1>

        @if($errors->any())
        <div style="background: #eb3349; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nombre Completo:</label>
                <input type="text" name="name" value="{{ $usuario->name }}" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="{{ $usuario->email }}" required>
            </div>

            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="{{ $usuario->telefono }}">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn" style="flex: 1;">✅ Guardar Cambios</button>
                <a href="{{ route('usuarios.index') }}" class="btn" style="flex: 1; text-align: center; background: #eb3349;">❌ Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>