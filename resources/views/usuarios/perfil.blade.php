<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
</head>
<body>
    @include('layouts.menu')
    
    <div class="container">
        <h1>👤 Perfil de Usuario</h1>

        <div style="background: white; padding: 40px; border-radius: 15px; margin: 20px 0; max-width: 600px; margin-left: auto; margin-right: auto;">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 50px; color: white;">
                    {{ substr($usuario->name, 0, 1) }}
                </div>
                <h2 style="margin: 0; color: #11998e;">{{ $usuario->name }}</h2>
                <p style="color: #666; margin: 5px 0;">
                    @if($usuario->rol == 'administrador')
                        <span style="background: #eb3349; color: white; padding: 5px 15px; border-radius: 15px; font-size: 12px;">👑 Administrador</span>
                    @else
                        <span style="background: #38ef7d; color: white; padding: 5px 15px; border-radius: 15px; font-size: 12px;">👤 Usuario</span>
                    @endif
                </p>
            </div>

            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
                <strong style="color: #11998e;">📧 Email:</strong>
                <p style="margin: 5px 0 0 0;">{{ $usuario->email }}</p>
            </div>

            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
                <strong style="color: #11998e;">📱 Teléfono:</strong>
                <p style="margin: 5px 0 0 0;">{{ $usuario->telefono ?? 'No registrado' }}</p>
            </div>

            <div style="padding: 20px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
                <strong style="color: #11998e;">📅 Fecha de Registro:</strong>
                <p style="margin: 5px 0 0 0;">{{ date('d/m/Y', strtotime($usuario->created_at)) }}</p>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn" style="flex: 1; text-align: center;">✏️ Editar Perfil</a>
                <a href="{{ route('usuarios.index') }}" class="btn" style="flex: 1; text-align: center; background: #666;">← Volver</a>
            </div>
        </div>
    </div>
</body>
</html>