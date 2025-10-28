<?php
/**
 * DocumentoController (Controlador de Documentos)
 *
 * Se encarga de la lógica para el CRUD de documentos.
 */

// 1. Cargamos el "Guardián" y el "Modelo"
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Modelo/Documento.php';

class DocumentoController extends BaseController {

    private $modeloDocumento;

    public function __construct(PDO $db) {
        parent::__construct($db); // Verifica la sesión
        $this->modeloDocumento = new Documento($db);

        // --- REQUISITO: VISTA POR ROL ---
        if ($this->rol != 'Bibliotecario' && $this->rol != 'Hokage') {
            $this->redirigirA('dashboard', 'error', 'No tienes permisos para gestionar documentos.');
            return;
        }
    }

    /**
     * Muestra la lista/inventario de todos los documentos.
     */
    public function index() {
        $datosParaLaVista = [
            'titulo_pagina' => 'Inventario de Documentos',
            'documentos' => $this->modeloDocumento->obtenerTodos()
        ];
        $this->cargarVista('Documentos/index', $datosParaLaVista);
    }

    /**
     * Muestra el formulario de creación.
     */
    public function crear() {
        $datosParaLaVista = [
            'titulo_pagina' => 'Añadir Nuevo Documento',
            'secciones' => $this->modeloDocumento->obtenerSecciones(),
            'niveles' => $this->modeloDocumento->obtenerNiveles()
        ];
        $this->cargarVista('Documentos/crear', $datosParaLaVista);
    }

    /**
     * Procesa los datos del formulario (POST) para crear un nuevo documento.
     */
    public function guardar() {
        if (empty($_POST['titulo']) || empty($_POST['id_seccion']) || empty($_POST['id_nivel'])) {
            $this->redirigirA('documentos/crear', 'error', 'Los campos Título, Sección y Nivel son obligatorios.');
            return;
        }

        $datos = [
            'titulo' => trim($_POST['titulo']),
            'tipo' => trim($_POST['tipo'] ?? 'N/A'),
            'id_seccion' => (int)$_POST['id_seccion'],
            'id_nivel' => (int)$_POST['id_nivel'],
            'estado' => $_POST['estado'] ?? 'Disponible',
            'acceso_restringido' => (int)($_POST['acceso_restringido'] ?? 0),
            'fecha_ingreso' => date('Y-m-d')
        ];

        $exito = $this->modeloDocumento->crear($datos);

        if ($exito) {
            $this->redirigirA('documentos', 'exito', '¡Documento añadido correctamente!');
        } else {
            $this->redirigirA('documentos/crear', 'error', 'Error al guardar en la base de datos.');
        }
    }

    /**
     * Muestra el formulario para editar un documento existente.
     */
    public function editar($id) {
        $documento = $this->modeloDocumento->obtenerPorId($id);

        if (!$documento) {
            $this->redirigirA('documentos', 'error', 'El documento que intentas editar no existe.');
            return;
        }

        $datosParaLaVista = [
            'titulo_pagina' => 'Editar Documento',
            'secciones' => $this->modeloDocumento->obtenerSecciones(),
            'niveles' => $this->modeloDocumento->obtenerNiveles(),
            'documento' => $documento
        ];

        $this->cargarVista('Documentos/editar', $datosParaLaVista);
    }

    /**
     * Procesa los datos (POST) del formulario de edición.
     */
    public function actualizar($id) {
        if (empty($_POST['titulo']) || empty($_POST['id_seccion']) || empty($_POST['id_nivel'])) {
            $this->redirigirA('documentos/editar/' . $id, 'error', 'Los campos Título, Sección y Nivel son obligatorios.');
            return;
        }

        $datos = [
            'titulo' => trim($_POST['titulo']),
            'tipo' => trim($_POST['tipo'] ?? 'N/A'),
            'id_seccion' => (int)$_POST['id_seccion'],
            'id_nivel' => (int)$_POST['id_nivel'],
            'estado' => $_POST['estado'] ?? 'Disponible',
            'acceso_restringido' => (int)($_POST['acceso_restringido'] ?? 0)
        ];

        $exito = $this->modeloDocumento->actualizar($id, $datos);

        if ($exito) {
            $this->redirigirA('documentos', 'exito', '¡Documento actualizado correctamente!');
        } else {
            $this->redirigirA('documentos/editar/' . $id, 'error', 'Error al actualizar en la base de datos.');
        }
    }

    // -----------------------------------------------------------------
    // --- ¡NUEVO! FUNCIÓN: ELIMINAR UN DOCUMENTO ---
    // -----------------------------------------------------------------
    /**
     * Elimina un documento por su ID.
     *
     * @param int $id El ID del documento (de la URL)
     */
    public function eliminar($id) {
        // 1. (Opcional) Validar si el documento existe antes de intentar borrar
        $documento = $this->modeloDocumento->obtenerPorId($id);
        if (!$documento) {
            $this->redirigirA('documentos', 'error', 'El documento que intentas eliminar no existe.');
            return;
        }

        // 2. Llamar al Modelo para eliminar
        $exito = $this->modeloDocumento->eliminar($id);

        if ($exito) {
            // 3. Si tuvo éxito, redirigir al inventario
            $this->redirigirA('documentos', 'exito', '¡Documento eliminado correctamente!');
        } else {
            // 4. Si falló, redirigir de vuelta con error
            $this->redirigirA('documentos', 'error', 'Error al eliminar el documento de la base de datos.');
        }
    }
    // -----------------------------------------------------------------
    // --- ¡NUEVO! FUNCIÓN: BUSCADOR DE DOCUMENTOS (Para Lectores) ---
    // -----------------------------------------------------------------
    /**
     * Muestra los resultados de una búsqueda para el rol Lector.
     * Es la interfaz principal para Ninjas/Investigadores.
     */
    public function buscar() {
        die("¡LA FUNCIÓN BUSCAR SÍ SE ESTÁ LLAMANDO!");
        $termino = trim($_GET['q'] ?? '');

        if (empty($termino)) {
            $this->redirigirA('dashboard', 'error', 'Debes ingresar un término de búsqueda.');
            return;
        }
        
        // 1. Pedirle los resultados al Modelo
        $resultados = $this->modeloDocumento->buscarDocumentos($termino);
        // ----- CÓDIGO DE DEPURACIÓN -----
    echo "<pre style='font-family: monospace; background: #eee; padding: 10px; border: 1px solid #ccc;'>";
    echo "Buscando término: ";
    var_dump($termino);
    echo "<hr>Resultados del Modelo:<br>";
    var_dump($resultados);
    echo "</pre>";
    // ----- FIN DE DEPURACIÓN -----

        // 2. Preparar los datos
        $datosParaLaVista = [
            'titulo_pagina' => 'Resultados de Búsqueda',
            'termino' => htmlspecialchars($termino),
            'documentos' => $resultados
        ];

        // 3. Cargar la vista de resultados
        $this->cargarVista('Documentos/resultados', $datosParaLaVista);
    }
}