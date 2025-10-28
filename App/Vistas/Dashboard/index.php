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
            <h3>Alertas de Préstamos Vencidos (RF11, RF12)</h3>
            <p>Pergamino 'Rasen-Shuriken' (ID 45) tiene 2 días de retraso.</p>
        </div>
        <div class="dashboard-modulo">
            <h3>Historial de Consultas (RF09)</h3>
            <a href="#" class="btn" style="width: auto;">Ver Historial Completo</a>
        </div>
        <?php break; ?>

    <?php case 'Bibliotecario': ?>
        <div class="dashboard-modulo">
            <h3>Gestión de Préstamos</h3>
            <p>Administrar préstamos de pergaminos.</p>
            <a href="#" class="btn" style="width: auto; margin-right: 10px;">Registrar Préstamo</a>
            <a href="#" class="btn" style="width: auto;">Ver Préstamos Activos</a>
        </div>
        
        <div class="dashboard-modulo">
            <h3>Gestión de Documentos (CRUD)</h3>
            <p>Ver el inventario, añadir o editar documentos en el archivo.</p>
            <a href="/LIBRERIAKONOHA/documentos" class="btn btn-primary" style="width: auto;">Gestionar Inventario</a>
        </div>
        <?php break; ?>
        

    <?php case 'Ninja': ?>
    <?php case 'Investigador': ?>
    <?php default: ?>
        <div class="dashboard-modulo">
            <h3>Búsqueda de Documentos (RF04, RF05)</h3>
            <p>Acceso de solo lectura a los archivos permitidos.</p>
            <input type="text" class="form-control" placeholder="Buscar por nombre, tipo o rango...">
        </div>
        <?php break; ?>

<?php endswitch; ?>


<?php
// (El footer.php será cargado por el controlador) 
?>