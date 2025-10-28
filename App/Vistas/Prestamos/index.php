<?php // Se asume que $prestamos y $titulo_pagina existen ?>

<style>
    body { display: block; justify-content: normal; align-items: normal; min-height: auto; }
    .main-container { max-width: 980px; margin: 20px auto; padding: 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e5e7; padding-bottom: 15px; margin-bottom: 25px; }
    .table-container { margin-top: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background-color: #f7f7f7; font-weight: 600; }
    tr:hover { background-color: #f1f1f1; }
    .btn-devolver { background-color: #007aff; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; text-decoration: none; }
    .estado-vencido { color: #c51f28; font-weight: bold; }
    .exito-box, .error-box { padding: 15px; margin-bottom: 20px; border-radius: 8px; } /* Estilos para mensajes */
    .success { background-color: #e5f7ed; color: #1d7246; border: 1px solid #c3e6cb; }
    .danger { background-color: #fbebed; color: #c51f28; border: 1px solid #f5c6cb; }
</style>

<div class="dashboard-header">
    <div>
        <h2><?php echo htmlspecialchars($titulo_pagina); ?></h2>
        <p style="text-align: left; margin: 0;">Lista de documentos prestados que aún no han sido devueltos.</p>
    </div>
    <a href="/LIBRERIAKONOHA/dashboard" class="btn" style="width: auto; background-color: #e5e5e7; color: #333;">Volver al Dashboard</a>
</div>

<div id="message-box">
    <?php if (isset($_GET['exito'])): ?>
        <div class="exito-box success"><?php echo htmlspecialchars($_GET['exito']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="error-box danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>
</div>

<div class="table-container">
    <?php if (!empty($prestamos)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Documento</th>
                    <th>Ninja (Prestatario)</th>
                    <th>Fecha Préstamo</th>
                    <th>Fecha Estimada Devolución</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestamos as $p): ?>
                <tr>
                    <td><?php echo $p['id_prestamo']; ?></td>
                    <td><?php echo htmlspecialchars($p['titulo_documento']); ?></td>
                    <td><?php echo htmlspecialchars($p['nombre_ninja']); ?></td>
                    <td><?php echo htmlspecialchars($p['fecha_prestamo']); ?></td>
                    <td class="<?php echo (strtotime($p['fecha_devolucion_estimada']) < time()) ? 'estado-vencido' : ''; ?>">
                        <?php echo htmlspecialchars($p['fecha_devolucion_estimada']); ?>
                        <?php if (strtotime($p['fecha_devolucion_estimada']) < time()): ?>
                            (VENCIDO)
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="/LIBRERIAKONOHA/prestamos/devolver/<?php echo $p['id_prestamo']; ?>" method="POST" style="display: inline;">
                            <button type="submit" class="btn-devolver">
                                Marcar Devuelto
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay préstamos activos en este momento. 🌳</p>
    <?php endif; ?>
</div>