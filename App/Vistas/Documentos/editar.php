<?php
// (El header.php ya fue cargado por el controlador)

// Las variables $titulo_pagina, $secciones, $niveles,
// y $documento (¡el más importante!)
// vienen del $datosParaLaVista en DocumentoController.php
?>

<style>
    body {
        display: block; justify-content: normal; align-items: normal; min-height: auto;
    }
    .main-container {
        max-width: 980px; margin: 20px auto; padding: 30px;
        background-color: #ffffff; border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .dashboard-header {
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid #e5e5e7; padding-bottom: 15px; margin-bottom: 25px;
    }
</style>

<div class="dashboard-header">
    <div>
        <h2><?php echo htmlspecialchars($titulo_pagina); ?></h2>
        <p style="text-align: left; margin: 0;">Editando: <strong><?php echo htmlspecialchars($documento['titulo']); ?></strong></p>
    </div>
    <a href="/LIBRERIAKONOHA/documentos" class="btn" style="width: auto; background-color: #e5e5e7; color: #333;">Cancelar y Volver</a>
</div>

<form action="/LIBRERIAKONOHA/documentos/actualizar/<?php echo $documento['id_documento']; ?>" method="POST">
    
    <div id="error-message-box">
        <?php if (isset($_GET['error'])): ?>
            <div class="error-box danger" style="margin-bottom: 20px;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>

    <div style="display: flex; gap: 20px;">
        <div class="form-group" style="flex: 2;">
            <label for="titulo" class="form-label">Título del Documento</label>
            <input type="text" id="titulo" name="titulo" class="form-control" 
                   value="<?php echo htmlspecialchars($documento['titulo']); ?>" required>
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="tipo" class="form-label">Tipo (Libro, Pergamino, etc.)</label>
            <input type="text" id="tipo" name="tipo" class="form-control" 
                   value="<?php echo htmlspecialchars($documento['tipo']); ?>" required>
        </div>
    </div>

    <div style="display: flex; gap: 20px;">
        <div class="form-group" style="flex: 1;">
            <label for="id_seccion" class="form-label">Sección</label>
            <select id="id_seccion" name="id_seccion" class="form-control" required>
                <option value="">-- Selecciona una sección --</option>
                <?php foreach ($secciones as $seccion): ?>
                    <option value="<?php echo $seccion['id_seccion']; ?>"
                        <?php if ($seccion['id_seccion'] == $documento['id_seccion']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($seccion['nombre_seccion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="id_nivel" class="form-label">Nivel de Peligrosidad (Rango)</label>
            <select id="id_nivel" name="id_nivel" class="form-control" required>
                <option value="">-- Selecciona un rango --</option>
                <?php foreach ($niveles as $nivel): ?>
                    <option value="<?php echo $nivel['id_nivel']; ?>"
                        <?php if ($nivel['id_nivel'] == $documento['id_nivel']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($nivel['rango'] . ' - ' . $nivel['descripcion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div style="display: flex; gap: 20px;">
        <div class="form-group" style="flex: 1;">
            <label for="estado" class="form-label">Estado Actual</label>
            <select id="estado" name="estado" class="form-control" required>
                <option value="Disponible" <?php if ($documento['estado'] == 'Disponible') echo 'selected'; ?>>Disponible</option>
                <option value="Resguardado" <?php if ($documento['estado'] == 'Resguardado') echo 'selected'; ?>>Resguardado</option>
                <option value="En préstamo" <?php if ($documento['estado'] == 'En préstamo') echo 'selected'; ?>>En préstamo</option>
            </select>
        </div>
        <div class="form-group" style="flex: 1; display: flex; align-items: center; padding-top: 20px;">
             <input type="checkbox" id="acceso_restringido" name="acceso_restringido" value="1" 
                    style="width: 20px; height: 20px; margin-right: 10px;"
                    <?php if ($documento['acceso_restringido'] == 1) echo 'checked'; ?>>
            <label for="acceso_restringido" class="form-label" style="margin: 0;">
                ¿Acceso Restringido? (Requiere permiso del Hokage)
            </label>
        </div>
    </div>
    
    <div class="form-group" style="margin-top: 20px; border-top: 1px solid #e5e5e7; padding-top: 20px;">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </div>
</form>

<?php
// (El footer.php será cargado por el controlador) 
?>