<?php
/**
 * Front Controller (Punto de Entrada Único)
 * (Versión FINAL con CRUD completo)
 */

// 1. Cargar la Configuración (BD y Sesiones)
require_once __DIR__ . '/../App/Configuracion/database.php';

// 2. Cargar los Controladores Base
require_once __DIR__ . '/../App/Controladores/AuthController.php';
require_once __DIR__ . '/../App/Controladores/BaseController.php';

// 3. Crear instancias de los objetos
$db_conexion = (new Database())->getConnection();
$authController = new AuthController($db_conexion);

// 4. Sistema de Rutas (El "GPS")
$url = $_GET['url'] ?? 'login';
$url = rtrim($url, '/');
$urlPartes = explode('/', $url);

// 5. Switch de Rutas
switch ($urlPartes[0]) {

    // ... (casos login, logout, dashboard, recuperar, reset, registro) ...
    case 'login':
    case 'logout':
    case 'dashboard':
    case 'recuperar':
    case 'reset':
    case 'registro':
        // (El código de estos casos es el mismo que ya tienes)
        switch ($urlPartes[0]) {
            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') { $authController->mostrarLogin(); }
                elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { $authController->procesarLogin(); }
                break;
            case 'logout':
                $authController->logout();
                break;
            case 'dashboard':
                require_once __DIR__ . '/../App/Controladores/DashboardController.php';
                $dashboardController = new DashboardController($db_conexion);
                $dashboardController->index();
                break;
            case 'recuperar':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') { $authController->mostrarRecuperar(); }
                elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { $authController->procesarRecuperar(); }
                break;
            case 'reset':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') { $authController->mostrarReset(); }
                elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { $authController->procesarReset(); }
                break;
            case 'registro':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') { $authController->mostrarRegistro(); }
                elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { $authController->procesarRegistro(); }
                break;
        }
        break; // Fin del bloque de autenticación


    // --- MÓDULO DE DOCUMENTOS (CRUD Completo) ---
            case 'Documentos':
                // 1. Cargamos el controlador
                require_once __DIR__ . '/../App/Controladores/DocumentoController.php';
                $docController = new DocumentoController($db_conexion);
        
                // 2. Revisamos la segunda parte de la URL
                $accion = $urlPartes[1] ?? 'index';
        
                // 3. Capturamos el ID si existe (para editar/eliminar/ver)
                $id = $urlPartes[2] ?? 0;
        
                switch ($accion) {
                    case 'crear':
                        // ... (código existente) ...
                        break;
        
                    case 'editar':
                        // ... (código existente) ...
                        break;
        
                    case 'actualizar':
                        // ... (código existente) ...
                        break;
        
                    case 'eliminar':
                        // ... (código existente) ...
                        break;

                    // --- ¡¡NUEVA RUTA!! ---
                    case 'buscar':
                        // /documentos/buscar (siempre es GET desde el form)
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            $docController->buscar();
                        } else {
                            $docController->redirigirA('dashboard');
                        }
                        break;
                    // --- FIN NUEVA RUTA ---

                    case 'index':
                    default:
                        $docController->index();
                        break;
                }
                break;
            
    // --- FIN DEL MÓDULO DE DOCUMENTOS ---

   // --- MÓDULO DE PRÉSTAMOS ACTUALIZADO ---
    case 'prestamos':
        // 1. Cargamos el controlador
        require_once __DIR__ . '/../App/Controladores/PrestamoController.php';
        $prestamoController = new PrestamoController($db_conexion);

        // 2. Revisamos la segunda parte de la URL
        $accion = $urlPartes[1] ?? 'index';

        // 3. Capturamos el ID si existe
        $id = $urlPartes[2] ?? 0;

        switch ($accion) {
            case 'alertas':
                $prestamoController->alertas();
                break;

            case 'crear':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $prestamoController->crear();
                }
                elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $prestamoController->guardar();
                }
                break;

            case 'devolver':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $prestamoController->devolver($id);
                } else {
                    $prestamoController->redirigirA('prestamos/index', 'error', 'Acción no permitida.');
                }
                break;

            // --- ¡¡NUEVA RUTA!! ---
            case 'historial':
                // /prestamos/historial
                $prestamoController->historial();
                break;
            // --- FIN NUEVA RUTA ---

            case 'index':
            default:
                $prestamoController->index();
                break;
        }
        break;
   
    // --- FIN DEL MÓDULO DE PRÉSTAMOS ---


    case '':
    case 'home':
        header('Location: login');
        exit;

    default:
        http_response_code(404);
        echo "Error 404: Página no encontrada.";
        break;
}
?>
