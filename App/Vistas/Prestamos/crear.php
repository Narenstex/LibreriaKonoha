<?php
// (El header.php ya fue cargado por el controlador)

// Las variables $titulo_pagina, $usuarios (lista de ninjas)
// y $documentos_disponibles vienen del controlador.
?>

<style>
    body { display: block; justify-content: normal; align-items: normal; min-height: auto; }
    .main-container { max-width: 980px; margin: 20px auto; padding: 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e5e7; padding-bottom: 15px; margin-bottom: 25px; }
    .form-group { margin-bottom: 20px; } /* Estilo básico de formulario */
    .form-label { display: block; font-weight: 500; margin-bottom: 8px; color: #333; }
    .form-control { width: 100%; padding: 12px; font-size: 16px; border: 1px solid #d2d2d7; border-radius: 8px; box-sizing: border-box; }
    .form-control:focus { border-color: #007aff; outline: none; box-shadow: 0 0 0 3px rgba(0,122,255,0.25); }
    .btn { display: inline-block; padding: 10px 20px; font-size: 16px; font-weight: 500; text-decoration: none; cursor: pointer; border: none; border-radius: 8px; } /* Estilo básico de botón */
    .btn-primary { background-color: #007aff; color: #ffffff; }
    .btn-secondary { background-color: #e5e5e7; color: #333; }
    .error-box { padding: 15px; margin-bottom: 20px; border-radius: 8px; } /* Estilo de error */
    .danger { background-color: #fbebed; color: #c51f28; border: 1px solid #f5c6cb; }
</style>

<div class="dashboard-header">
    <div>
        <h2><?php echo htmlspecialchars($titulo_pagina); ?></h2>
        <p style="text-align: left; margin: 0;">Selecciona el Ninja, el Documento y la fecha de devolución.</p>
    </div>
    <a href="/LIBRERIAKONOHA/prestamos/index" class="btn btn-secondary" style="width: auto;">Cancelar y Volver</a>
</div>

<form action="/LIBRERIAKONOHA/prestamos/crear" method="POST">

    <div id="error-message-box">
        <?php if (isset($_GET['error'])): ?>
            <div class="error-box danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>

    <div style="display: flex; gap: 20px;">
        <div class="form-group" style="flex: 1;">
            <label for="id_usuario" class="form-label">Ninja Prestatario</label>
            <select id="id_usuario" name="id_usuario" class="form-control" required>
                <option value="">-- Selecciona un Ninja --</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?php echo $usuario['id_usuario']; ?>">
                        <?php echo htmlspecialchars($usuario['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="flex: 2;">
            <label for="id_documento" class="form-label">Documento a Prestar</label>
            <select id="id_documento" name="id_documento" class="form-control" required>
                <option value="">-- Selecciona un documento disponible --</option>
                <?php foreach ($documentos_disponibles as $doc): ?>
                    <option value="<?php echo $doc['id_documento']; ?>">
                        <?php echo htmlspecialchars($doc['titulo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div style="display: flex; gap: 20px;">
        <div class="form-group" style="flex: 1;">
            <label for="fecha_prestamo" class="form-label">Fecha de Préstamo</label>
            <input type="date" id="fecha_prestamo" name="fecha_prestamo" class="form-control" 
                   value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="form-group" style="flex: 1;">
            <label for="fecha_devolucion_estimada" class="form-label">Fecha Estimada Devolución</label>
            <input type="date" id="fecha_devolucion_estimada" name="fecha_devolucion_estimada" class="form-control" required>
        </div>
    </div>

    <div class="form-group">
        <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
        <textarea id="observaciones" name="observaciones" class="form-control" rows="3"></textarea>
    </div>

    <div class="form-group" style="margin-top: 20px; border-top: 1px solid #e5e5e7; padding-top: 20px;">
        <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
    </div>
</form>

<?php
// (El footer.php será cargado por el controlador)
?>