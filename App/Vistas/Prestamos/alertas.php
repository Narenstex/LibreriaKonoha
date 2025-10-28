<?php // Se asume que $prestamos_vencidos y $titulo_pagina existen ?>

<style>
    body { display: block; justify-content: normal; align-items: normal; min-height: auto; }
    .main-container { max-width: 980px; margin: 20px auto; padding: 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e5e7; padding-bottom: 15px; margin-bottom: 25px; }
    .table-container { margin-top: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background-color: #c51f28; color: white; font-weight: 600; }
    tr:nth-child(even) { background-color: #fdf5f5; }
    .alerta { background-color: #ffdddd; }
    .exito-box { padding: 15px; margin-bottom: 20px; border-radius: 8px; background-color: #ddffdd; border: 1px solid #00aa00; color: #006600; } /* Estilo para mensaje de éxito */
</style>

<div class="dashboard-header">
    <div>
        <h2><?php echo htmlspecialchars($titulo_pagina); ?> 🚨</h2>
        <p style="text-align: left; margin: 0;">¡ALERTA! Documentos que debieron ser devueltos y están retrasados.</p>
    </div>
    <a href="/LIBRERIAKONOHA/dashboard" class="btn" style="width: auto; background-color: #e5e5e7; color: #333;">Volver al Dashboard</a>
</div>

<div class="table-container">
    <?php if (!empty($prestamos_vencidos)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Documento Vencido</th>
                    <th>Ninja (Responsable)</th>
                    <th>Fecha Préstamo</th>
                    <th>Fecha Límite</th>
                    <th>Días de Retraso</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestamos_vencidos as $p): 
                    $dias_retraso = floor((time() - strtotime($p['fecha_devolucion_estimada'])) / (60 * 60 * 24));
                ?>
                <tr class="alerta">
                    <td><?php echo $p['id_prestamo']; ?></td>
                    <td><?php echo htmlspecialchars($p['titulo_documento']); ?></td>
                    <td><?php echo htmlspecialchars($p['nombre_ninja']); ?></td>
                    <td><?php echo htmlspecialchars($p['fecha_prestamo']); ?></td>
                    <td><?php echo htmlspecialchars($p['fecha_devolucion_estimada']); ?></td>
                    <td>**<?php echo $dias_retraso; ?> días**</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="exito-box">
            <p>✅ No hay alertas de devolución vencida.</p>
        </div>
    <?php endif; ?>
</div>