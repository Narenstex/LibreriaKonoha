<?php

// ¡Importante! Cargamos el BaseController primero
require_once __DIR__ . '/BaseController.php';

/**
 * DashboardController
 * Hereda de BaseController, por lo que ya está protegido.
 */
class DashboardController extends BaseController {

    /**
     * Muestra la página principal del Dashboard.
     * La lógica de qué mostrar se decide en la VISTA
     * usando la variable $_SESSION['rol'].
     */
    public function index() {
        
        // (En el futuro, aquí podemos buscar datos en la BD)
        // Ej: $alertas = $this->db->query("...");

        // Preparamos los datos para enviar a la vista
        $datos = [
            'titulo_pagina' => 'Panel de Control Shinobi',
            'nombre_usuario' => $_SESSION['nombre'] // Lo guardamos en el login
        ];

        // Usamos nuestro 'helper' para cargar la vista
        // (Esto cargará header, luego dashboard/index, luego footer)
        $this->cargarVista('Dashboard/index', $datos);
    }
}