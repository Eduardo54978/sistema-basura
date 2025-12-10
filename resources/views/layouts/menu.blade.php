<style>
    .menu-hamburguesa {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .menu-btn {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        padding: 15px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 24px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }
    
    .menu-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }
    
    .menu-desplegable {
        display: none;
        position: absolute;
        top: 70px;
        right: 0;
        background: white;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        min-width: 250px;
        overflow: hidden;
    }
    
    .menu-desplegable.activo {
        display: block;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .menu-desplegable a {
        display: block;
        padding: 15px 20px;
        color: #333;
        text-decoration: none;
        transition: all 0.3s;
        border-bottom: 1px solid #eee;
    }
    
    .menu-desplegable a:hover {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }
    
    .menu-desplegable a:last-child {
        border-bottom: none;
    }
</style>

<div class="menu-hamburguesa">
    <button class="menu-btn" onclick="toggleMenu()">☰</button>
    
    <div class="menu-desplegable" id="menuDesplegable">
        <a href="/">🏠 Inicio</a>
        <a href="{{ route('reportes.index') }}">📊 Reportes y Estadísticas</a>
        <a href="{{ route('automatizacion.index') }}">⚙️ Automatización de Tareas</a>
        <a href="{{ route('usuarios.index') }}">👤 Gestión de Usuarios</a>
        <a href="{{ route('historial.index') }}">📜 Historial de Análisis</a>
        <a href="{{ route('configuracion.index') }}">⚙️ Configuración</a>
    </div>
</div>

<script>
    function toggleMenu() {
        const menu = document.getElementById('menuDesplegable');
        menu.classList.toggle('activo');
    }
    
    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('menuDesplegable');
        const btn = document.querySelector('.menu-btn');
        
        if (!menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.remove('activo');
        }
    });
</script>