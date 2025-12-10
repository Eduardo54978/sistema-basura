<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="{{ asset('modulos/reportes/css/reportes.css') }}">
</head>
<body>
    @include('layouts.menu')
    
    <div class="container">
        <h1>👤 Gestión de Usuarios</h1>

        @if(session('success'))
        <div style="background: #38ef7d; color: white; padding: 15px; border-radius: 10px; margin: 20px 0;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <div style="background: white; padding: 30px; border-radius: 15px; margin: 20px 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="color: #11998e; margin: 0;">Lista de Usuarios ({{ count($usuarios) }})</h2>
                <a href="{{ route('usuarios.registro') }}" class="btn">➕ Registrar Nuevo Usuario</a>
            </div>

            @if(count($usuarios) > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #11998e; color: white;">
                        <th style="padding: 15px; text-align: left;">Nombre</th>
                        <th style="padding: 15px; text-align: left;">Email</th>
                        <th style="padding: 15px; text-align: left;">Teléfono</th>
                        <th style="padding: 15px; text-align: center;">Rol</th>
                        <th style="padding: 15px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 15px;"><strong>{{ $usuario->name }}</strong></td>
                        <td style="padding: 15px;">{{ $usuario->email }}</td>
                        <td style="padding: 15px;">{{ $usuario->telefono ?? 'N/A' }}</td>
                        <td style="padding: 15px; text-align: center;">
                            @if($usuario->rol == 'administrador')
                                <span style="background: #eb3349; color: white; padding: 5px 15px; border-radius: 15px; font-size: 12px;">👑 Admin</span>
                            @else
                                <span style="background: #38ef7d; color: white; padding: 5px 15px; border-radius: 15px; font-size: 12px;">👤 Usuario</span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="{{ route('usuarios.perfil', $usuario->id) }}" class="btn" style="padding: 8px 15px; margin: 2px;">👁️ Ver</a>
                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn" style="padding: 8px 15px; margin: 2px; background: #f093fb;">✏️ Editar</a>
                            <form method="POST" action="{{ route('usuarios.destroy', $usuario->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 8px 15px; margin: 2px; background: #eb3349;" onclick="return confirm('¿Eliminar usuario?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align: center; padding: 40px; color: #999;">No hay usuarios registrados</p>
            @endif
        </div>

        <div style="background: white; padding: 25px; border-radius: 15px; margin: 20px 0;">
            <h3 style="color: #11998e; margin-bottom: 15px;">🔧 Conceptos Aplicados</h3>
            <ul style="color: #666;">
                <li><strong>Expresiones Regulares:</strong> Validación de formato de email</li>
                <li><strong>Autenticación:</strong> Sistema de login/logout con sesiones</li>
                <li><strong>Roles:</strong> Diferenciación entre administrador y usuario estándar</li>
                <li><strong>CRUD Completo:</strong> Crear, Leer, Actualizar, Eliminar usuarios</li>
            </ul>
        </div>
    </div>
</body>
</html>