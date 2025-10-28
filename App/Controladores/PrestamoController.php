<?php
/**
 * PrestamoController (Controlador de Préstamos)
 */

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Modelo/Prestamo.php';
// NUEVO: Cargar los modelos necesarios para el formulario de crear
require_once __DIR__ . '/../Modelo/Usuario.php';
require_once __DIR__ . '/../Modelo/Documento.php';

class PrestamoController extends BaseController {

    private $modeloPrestamo;
    // NUEVO: Añadir propiedades para los otros modelos
    private $modeloUsuario;
    private $modeloDocumento;

    public function __construct(PDO $db) {
        parent::__construct($db); // Verifica la sesión
        $this->modeloPrestamo = new Prestamo($db);
        // NUEVO: Crear instancias de los otros modelos
        $this->modeloUsuario = new Usuario($db);
        $this->modeloDocumento = new Documento($db);

        // --- VERIFICACIÓN DE ROL (Movida a métodos específicos donde se necesita) ---
    }

    /**
     * Muestra la lista de préstamos activos (para Bibliotecario).
     */
    public function index() {
        if ($this->rol != 'Bibliotecario' && $this->rol != 'Hokage') {
            $this->redirigirA('dashboard', 'error', 'No tienes permisos para ver esta sección.');
            return;
        }

        $datosParaLaVista = [
            'titulo_pagina' => 'Préstamos Activos',
            'prestamos' => $this->modeloPrestamo->obtenerActivos()
        ];
        $this->cargarVista('Prestamos/index', $datosParaLaVista);
    }

    /**
     * Muestra la lista de préstamos vencidos (para ANBU y Hokage).
     */
    public function alertas() {
        if ($this->rol != 'ANBU' && $this->rol != 'Hokage') {
            $this->redirigirA('dashboard', 'error', 'Solo el ANBU y el Hokage pueden ver las alertas.');
            return;
        }

        $datosParaLaVista = [
            'titulo_pagina' => 'Alertas de Devolución Vencida',
            'prestamos_vencidos' => $this->modeloPrestamo->obtenerVencidos()
        ];
        $this->cargarVista('Prestamos/alertas', $datosParaLaVista);
    }

    // -----------------------------------------------------------------
    // --- ¡NUEVO! FUNCIÓN 1: MOSTRAR FORMULARIO DE CREAR PRÉSTAMO ---
    // -----------------------------------------------------------------
    /**
     * Muestra el formulario para registrar un nuevo préstamo.
     */
    public function crear() {
        // Solo permitir a Bibliotecario o Hokage
        if ($this->rol != 'Bibliotecario' && $this->rol != 'Hokage') {
            $this->redirigirA('dashboard', 'error', 'No tienes permisos para registrar préstamos.');
            return;
        }

        // Obtener datos para los <select> del formulario
        $datosParaLaVista = [
            'titulo_pagina' => 'Registrar Nuevo Préstamo',
            'usuarios' => $this->modeloUsuario->obtenerUsuariosParaPrestamo(),
            'documentos_disponibles' => $this->modeloDocumento->obtenerDisponiblesParaPrestamo()
        ];

        // Cargar la vista que creamos en el paso anterior
        $this->cargarVista('Prestamos/crear', $datosParaLaVista);
    }

    // -----------------------------------------------------------------
    // --- ¡NUEVO! FUNCIÓN 2: GUARDAR EL NUEVO PRÉSTAMO ---
    // -----------------------------------------------------------------
    /**
     * Procesa los datos POST del formulario de crear préstamo.
     */
    public function guardar() {
        // Solo permitir a Bibliotecario o Hokage
        if ($this->rol != 'Bibliotecario' && $this->rol != 'Hokage') {
            $this->redirigirA('dashboard', 'error', 'No tienes permisos para registrar préstamos.');
            return;
        }

        // 1. Validar que los datos POST esenciales no estén vacíos
        if (empty($_POST['id_usuario']) || empty($_POST['id_documento']) || empty($_POST['fecha_devolucion_estimada'])) {
            $this->redirigirA('prestamos/crear', 'error', 'Debes seleccionar un Ninja, un Documento y la Fecha de Devolución.');
            return;
        }
        
        // 2. Validar que la fecha de devolución no sea pasada (opcional pero buena idea)
        if (strtotime($_POST['fecha_devolucion_estimada']) < strtotime(date('Y-m-d'))) {
            $this->redirigirA('prestamos/crear', 'error', 'La fecha de devolución no puede ser una fecha pasada.');
             return;
        }

        // 3. Preparar los datos para el Modelo
        $datos = [
            'id_usuario' => (int)$_POST['id_usuario'],
            'id_documento' => (int)$_POST['id_documento'],
            'fecha_prestamo' => $_POST['fecha_prestamo'] ?? date('Y-m-d'), // Usar hoy si no viene
            'fecha_devolucion_estimada' => $_POST['fecha_devolucion_estimada'],
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'estado' => 'Activo' // Estado inicial
        ];

        // 4. Llamar al Modelo para guardar
        // (Aún no hemos creado la función 'crear' en Prestamo.php)
        $exito = $this->modeloPrestamo->crear($datos); 

        if ($exito) {
            // 5. Si tuvo éxito, redirigir a la lista de préstamos activos
            $this->redirigirA('prestamos/index', 'exito', '¡Préstamo registrado correctamente!');
        } else {
            // 6. Si falló, redirigir de vuelta al formulario de crear
            $this->redirigirA('prestamos/crear', 'error', 'Error al guardar el préstamo en la base de datos.');
        }
    }
    // -----------------------------------------------------------------
    // --- ¡NUEVO! FUNCIÓN: MARCAR UN PRÉSTAMO COMO DEVUELTO ---
    // -----------------------------------------------------------------
    /**
     * Procesa la solicitud para marcar un préstamo como devuelto.
     * Se llama mediante POST desde el botón en la lista de préstamos.
     *
     * @param int $id El ID del préstamo (de la URL)
     */
    public function devolver($id) {
        // Solo permitir a Bibliotecario o Hokage
        if ($this->rol != 'Bibliotecario' && $this->rol != 'Hokage') {
            $this->redirigirA('dashboard', 'error', 'No tienes permisos para esta acción.');
            return;
        }

        // 1. Llamar al Modelo para marcar como devuelto
        $exito = $this->modeloPrestamo->marcarComoDevuelto($id);

        if ($exito) {
            // 2. Si tuvo éxito, redirigir de vuelta a la lista de préstamos activos
            $this->redirigirA('prestamos/index', 'exito', '¡Préstamo marcado como devuelto correctamente!');
        } else {
            // 3. Si falló, redirigir de vuelta con error
            $this->redirigirA('prestamos/index', 'error', 'Error al marcar el préstamo como devuelto.');
        }
    } // <-- Llave de cierre para devolver()

    // -----------------------------------------------------------------
    // --- ¡NUEVO! FUNCIÓN: MOSTRAR HISTORIAL DE PRÉSTAMOS ---
    // -----------------------------------------------------------------
    /**
     * Muestra el historial completo de préstamos (activos y devueltos).
     * Accesible para Bibliotecario y Hokage.
     */
    public function historial() {
        // Solo permitir a Bibliotecario o Hokage
        if ($this->rol != 'Bibliotecario' && $this->rol != 'Hokage') {
            $this->redirigirA('dashboard', 'error', 'No tienes permisos para ver el historial.');
            return;
        }

        // 1. Pedirle los datos al Modelo
        $historialCompleto = $this->modeloPrestamo->obtenerHistorial();

        // 2. Preparar los datos para la Vista
        $datosParaLaVista = [
            'titulo_pagina' => 'Historial de Préstamos',
            'historial' => $historialCompleto // Pasamos el historial
        ];

        // 3. Cargar la nueva vista (que crearemos a continuación)
        $this->cargarVista('Prestamos/historial', $datosParaLaVista);
    } // <-- Llave de cierre para historial()

} // <-- Llave de cierre final de la clase PrestamoController


?>