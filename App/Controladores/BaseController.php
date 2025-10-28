<?php

/**
 * BaseController (El Guardián)
 *
 * Su único trabajo es verificar si el usuario tiene una sesión activa.
 * Si no la tiene, lo expulsa a la página de login.
 * Todos los demás controladores (excepto AuthController) heredarán de este.
 */
class BaseController {

    /**
     * @var PDO La conexión a la base de datos
     */
    protected $db;

    /**
     * @var string El rol del usuario (ej: 'Hokage')
     */
    protected $rol;

    /**
     * @var int El ID del usuario
     */
    protected $usuario_id;

    /**
     * Constructor: Se ejecuta automáticamente.
     * Verifica la sesión.
     */
    public function __construct(PDO $db) {
        $this->db = $db;

        // 1. Verificar si la sesión existe y tiene los datos necesarios
        if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
            // Si NO existe o está incompleta, lo expulsamos al login
            $this->redirigirA('login', 'error', 'Debes iniciar sesión para acceder.');
        }

        // 2. Si existe, guardamos los datos para usarlos fácil
        $this->usuario_id = $_SESSION['usuario_id'];
        $this->rol = $_SESSION['rol'];
    }

    /**
     * Carga una vista, pasándole el header y footer automáticamente.
     *
     * @param string $vista El nombre del archivo de la vista (ej: 'Dashboard/index')
     * @param array $datos (Opcional) Datos para pasar a la vista
     */
    protected function cargarVista(string $vista, array $datos = []) {
        // Extrae el array de datos para que estén disponibles como variables
        // Ej: $datos['titulo'] se convierte en $titulo
        extract($datos);

        // Construye la ruta completa al archivo de la vista
        $rutaVista = __DIR__ . '/../Vistas/' . $vista . '.php';

        // Verifica si el archivo de la vista realmente existe antes de incluirlo
        if (file_exists($rutaVista)) {
            // Carga el header
            require_once __DIR__ . '/../Vistas/Layouts/header.php';
            // Carga la vista principal
            require_once $rutaVista;
            // Carga el footer
            require_once __DIR__ . '/../Vistas/Layouts/footer.php';
        } else {
            // Si el archivo de la vista no existe, muestra un error claro
            // Esto ayuda a depurar problemas como el que tienes
            die("Error Fatal: La vista solicitada no se encontró en la ruta: " . $rutaVista);
        }
    }

    /**
     * Helper para redirigir (lo copiamos de AuthController)
     */
    protected function redirigirA(string $url, string $tipo = null, string $mensaje = null, array $params = []) {
        $location = "/LIBRERIAKONOHA/$url";

        $query = $params;
        if ($tipo && $mensaje) {
            $query[$tipo] = $mensaje;
        }

        if (!empty($query)) {
            $location .= '?' . http_build_query($query);
        }

        header('Location: ' . $location);
        exit;
    }
}