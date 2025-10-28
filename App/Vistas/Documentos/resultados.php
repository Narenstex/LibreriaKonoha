<?php 
// Las variables $titulo_pagina, $termino y $documentos vienen del controlador 
?>

<style>
    /* Estilos del Dashboard */
    body { display: block; justify-content: normal; align-items: normal; min-height: auto; }
    .main-container { max-width: 980px; margin: 20px auto; padding: 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e5e7; padding-bottom: 15px; margin-bottom: 25px; }

    /* Estilos de tabla y badges */
    .tabla-inventario { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .tabla-inventario th, .tabla-inventario td { border: 1px solid #e5e5e7; padding: 12px; text-align: left; }
    .tabla-inventario th { background-color: #f5f5f7; font-weight: 600; }
    .badge { padding: 4px 8px; border-radius: 6px; font-size: 13px; font-weight: 500; }
    .badge-rango-S { background-color: #fbebed; color: #c51f28; }
    .badge-rango-A { background-color: #fff0e8; color: #c54a0d; }
    .badge-rango-B { background-color: #fff6e0; color: #b88700; }
    .badge-rango-C { background-color: #e5f7ed; color: #1d7246; }
    .badge-rango-D { background-color: #e6f7ff; color: #0056b3; }
    .badge-estado-disp { background-color: #e5f7ed; color: #1d7246; }
    .badge-estado-resg { background-color: #fff0e8; color: #c54a0d; }
    .acciones a { color: #007aff; text-decoration: none; margin-right: 10px; }
</style>

<div class="dashboard-header">
    <div>
        <h2><?php echo htmlspecialchars($titulo_pagina); ?></h2>
        <p style="text-align: left; margin: 0;">Mostrando resultados para: <strong>"<?php echo $termino; ?>"</strong></p>
    </div>
    <a href="/LIBRERIAKONOHA/dashboard" class="btn" style="width: auto; background-color: #e5e5e7; color: #333;">Volver al Dashboard</a>
</div>

<div class="dashboard-modulo" style="padding: 0; background: #fff;">
    <?php if (empty($documentos)): ?>
        <p style="text-align: center; padding: 20px;">No se encontraron documentos que coincidan con su búsqueda.</p>
    <?php else: ?>
        <table class="tabla-inventario">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Sección</th>
                    <th>Tipo</th>
                    <th>Rango (Peligro)</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documentos as $doc): ?>
                    <tr>
                        <td>
                            <a href="/LIBRERIAKONOHA/documentos/ver/<?php echo $doc['id_documento']; ?>" style="font-weight: 600;"><?php echo htmlspecialchars($doc['titulo']); ?></a>
                        </td>
                        <td><?php echo htmlspecialchars($doc['nombre_seccion']); ?></td>
                        <td><?php echo htmlspecialchars($doc['tipo']); ?></td>
                        <td>
                            <span class="badge badge-rango-<?php echo htmlspecialchars($doc['rango_peligrosidad']); ?>">
                                Rango <?php echo htmlspecialchars($doc['rango_peligrosidad']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($doc['estado'] == 'Disponible'): ?>
                                <span class="badge badge-estado-disp">Disponible</span>
                            <?php else: ?>
                                <span class="badge badge-estado-resg"><?php echo htmlspecialchars($doc['estado']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                           <a href="/LIBRERIAKONOHA/documentos/ver/<?php echo $doc['id_documento']; ?>">Ver Detalle</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>