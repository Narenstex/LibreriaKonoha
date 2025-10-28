<?php
// (El header.php ya fue cargado por el controlador)

// Obtenemos el rol de la sesión
$rol = $_SESSION['rol'] ?? 'Ninja'; 
?>

<style>
    /* Sobreescribimos el estilo de 'body' del login */
    body {
        display: block; /* Restaura el flujo normal */
        justify-content: normal;
        align-items: normal;
        min-height: auto;
    }
    /* Estilo del contenedor principal del dashboard */
    .main-container {
        max-width: 980px;
        margin: 20px auto; /* Centrado */
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e5e7;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .dashboard-modulo {
        background-color: #f5f5f7;
        border: 1px solid #e5e5e7;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .dashboard-modulo h3 {
        text-align: left;
        margin-top: 0;
    }
</style>

<div class="dashboard-header">
    <div>
        <h2><?php echo htmlspecialchars($titulo_pagina); ?></h2>
        <p style="text-align: left; margin: 0;">Bienvenido, <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong> (Rol: <?php echo htmlspecialchars($rol); ?>)</p>
    </div>
    <a href="/LIBRERIAKONOHA/logout" class="btn btn-primary" style="width: auto;">Cerrar Sesión</a>
</div>

<?php switch ($rol):
    
    // --- VISTA DEL HOKAGE (Rol 1) ---
    case 'Hokage': ?>
        <div class="dashboard-modulo">
            <h3>Reportes de Consultas Sospechosas (RF13)</h3>
            <p>Viendo actividad de alto riesgo en Jutsus Rango S.</p>
            </div>
        <div class="dashboard-modulo">
            <h3>Gestión de Jutsus Prohibidos</h3>
            <a href="#" class="btn" style="width: auto;">Ver Jutsus Prohibidos</a>
        </div>
        <?php break; ?>

    <?php case 'ANBU': ?>
        <div class="dashboard-modulo">
            <h3>Alertas de Devolución</h3>
            <p>Lista de pergaminos con la fecha de devolución vencida.</p>
            <a href="/LIBRERIAKONOHA/prestamos/alertas" class="btn" style="width: auto; background-color: #c51f28; color: #fff;">Ver Alertas Urgentes</a>
        </div>
        <div class="dashboard-modulo">
            <h3>Historial de Consultas (RF09)</h3>
            <a href="#" class="btn" style="width: auto;">Ver Historial Completo</a>
        </div>
        <?php break; ?>

    <?php case 'Bibliotecario': ?>
        <div class="dashboard-modulo">
            <h3>Registro de Préstamos</h3>
            <p>Registra el retiro y la devolución de pergaminos por los ninjas.</p>
            <a href="/LIBRERIAKONOHA/prestamos/crear" class="btn" style="width: auto; margin-right: 10px; background-color: #555555; color: #fff;">Registrar Préstamo</a>
            <a href="/LIBRERIAKONOHA/prestamos/index" class="btn" style="width: auto; background-color: #e5e5e7; color: #333; margin-right: 10px;">Ver Préstamos Activos</a>
            <a href="/LIBRERIAKONOHA/prestamos/historial" class="btn" style="width: auto; background-color: #f0f0f0; color: #333;">Ver Historial Completo</a>
        </div>
        
        <div class="dashboard-modulo">
            <h3>Gestión de Documentos (CRUD)</h3>
            <p>Ver el inventario, añadir o editar documentos en el archivo.</p>
            <a href="/LIBRERIAKONOHA/documentos" class="btn btn-primary" style="width: auto;">Gestionar Inventario</a>
        </div>
        
        <?php break; ?>
        

    
    <?php case 'Ninja': 
    case 'Investigador': 
    default:
     ?>
        <h3 style="margin-top: 0;">Módulos de Búsqueda y Consulta</h3>

        <div class="dashboard-modulo">
            <h3>Búsqueda de Archivos Shinobi</h3>
            <p>Acceso de solo lectura a jutsus, historia y estudios médicos.</p>
            
            <form action="/LIBRERIAKONOHA/Documentos/buscar" method="GET">
                <input type="text" name="q" class="form-control" placeholder="Buscar por título, tipo, rango o sección (Ej: Genjutsu, Fuego, Nara, S)" style="margin-bottom: 15px;" >
                <button type="submit" class="btn btn-primary" style="width: auto;">Buscar Archivos</button>
            </form>
            </div>

        <div class="dashboard-modulo">
            <h3>Documentos Destacados</h3>
            <p>Vínculos rápidos a documentos de investigación (Clan Hyūga/Yamanaka).</p>
            <a href="#" style="color: #007aff;">Ver Estudios Médicos</a> | <a href="#" style="color: #007aff;">Ver Historia Shinobi</a>
        </div>
        <?php break; ?>

<?php endswitch; ?>


<?php
// (El footer.php será cargado por el controlador) 
?>