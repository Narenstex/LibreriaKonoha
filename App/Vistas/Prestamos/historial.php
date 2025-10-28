<?php
// (El header.php ya fue cargado por el controlador)

// Las variables $titulo_pagina y $historial (lista completa de préstamos)
// vienen del controlador.
?>

<style>
    body { display: block; justify-content: normal; align-items: normal; min-height: auto; }
    .main-container { max-width: 1080px; /* Un poco más ancho para más columnas */ margin: 20px auto; padding: 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e5e7; padding-bottom: 15px; margin-bottom: 25px; }
    .table-container { margin-top: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #ddd; font-size: 14px; /* Un poco más pequeño */ }
    th { background-color: #f7f7f7; font-weight: 600; }
    tr:nth-child(even) { background-color: #f9f9f9; } /* Filas alternas */
    tr:hover { background-color: #f1f1f1; }
    .estado-devuelto { color: #1d7246; /* Verde */ }
    .estado-activo { color: #b88700; /* Naranja/Amarillo */ font-weight: bold; }
    .estado-vencido { color: #c51f28; /* Rojo */ font-weight: bold; }
</style>

<div class="dashboard-header">
    <div>
        <h2><?php echo htmlspecialchars($titulo_pagina); ?> 📜</h2>
        <p style="text-align: left; margin: 0;">Registro completo de todos los préstamos realizados en la biblioteca.</p>
    </div>
    <a href="/LIBRERIAKONOHA/dashboard" class="btn" style="width: auto; background-color: #e5e5e7; color: #333;">Volver al Dashboard</a>
</div>

<div class="table-container">
    <?php if (!empty($historial)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Préstamo</th>
                    <th>Documento</th>
                    <th>Ninja</th>
                    <th>Fecha Préstamo</th>
                    <th>Fecha Estimada Dev.</th>
                    <th>Fecha Real Dev.</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $item): 
                    // Determinar clase CSS para el estado
                    $claseEstado = '';
                    $textoEstado = htmlspecialchars($item['estado']);
                    if ($item['estado'] == 'Devuelto') {
                        $claseEstado = 'estado-devuelto';
                    } elseif (strtotime($item['fecha_devolucion_estimada']) < time() && $item['fecha_devolucion_real'] === null) {
                        $claseEstado = 'estado-vencido';
                        $textoEstado = 'VENCIDO';
                    } else {
                         $claseEstado = 'estado-activo';
                    }
                ?>
                <tr>
                    <td><?php echo $item['id_prestamo']; ?></td>
                    <td><?php echo htmlspecialchars($item['titulo_documento']); ?></td>
                    <td><?php echo htmlspecialchars($item['nombre_ninja']); ?></td>
                    <td><?php echo htmlspecialchars($item['fecha_prestamo']); ?></td>
                    <td><?php echo htmlspecialchars($item['fecha_devolucion_estimada']); ?></td>
                    <td><?php echo $item['fecha_devolucion_real'] ? htmlspecialchars($item['fecha_devolucion_real']) : '--'; ?></td>
                    <td class="<?php echo $claseEstado; ?>"><?php echo $textoEstado; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aún no se han registrado préstamos en el sistema.</p>
    <?php endif; ?>
</div>

<?php
// (El footer.php será cargado por el controlador)
?>