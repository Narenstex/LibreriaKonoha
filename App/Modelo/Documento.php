<?php

/**
 * Modelo Documento
 *
 * Se encarga de todas las consultas a la base de datos
 * relacionadas con la tabla 'documento'.
 */
class Documento {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Obtiene TODOS los documentos para mostrar en el inventario.
     */
    public function obtenerTodos() {
        $sql = "SELECT 
                    d.id_documento, d.titulo, d.tipo, d.estado, d.acceso_restringido,
                    s.nombre_seccion, n.rango AS rango_peligrosidad
                FROM documento d
                LEFT JOIN seccion s ON d.id_seccion = s.id_seccion
                LEFT JOIN nivel_peligrosidad n ON d.id_nivel = n.id_nivel
                ORDER BY d.titulo ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un solo documento por su ID.
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM documento WHERE id_documento = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Crea un nuevo documento en la base de datos.
     */
    public function crear(array $datos) {
        $sql = "INSERT INTO documento (
                    titulo, tipo, id_seccion, id_nivel, fecha_ingreso, 
                    acceso_restringido, estado
                ) VALUES (
                    :titulo, :tipo, :id_seccion, :id_nivel, :fecha_ingreso, 
                    :acceso_restringido, :estado
                )";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':titulo', $datos['titulo']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':id_seccion', $datos['id_seccion']);
            $stmt->bindParam(':id_nivel', $datos['id_nivel']);
            $stmt->bindParam(':fecha_ingreso', $datos['fecha_ingreso']);
            $stmt->bindParam(':acceso_restringido', $datos['acceso_restringido']);
            $stmt->bindParam(':estado', $datos['estado']);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualiza un documento existente en la base de datos.
     */
    public function actualizar($id, array $datos) {
        $sql = "UPDATE documento SET
                    titulo = :titulo, tipo = :tipo, id_seccion = :id_seccion, 
                    id_nivel = :id_nivel, acceso_restringido = :acceso_restringido, estado = :estado
                WHERE id_documento = :id_documento";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':titulo', $datos['titulo']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':id_seccion', $datos['id_seccion']);
            $stmt->bindParam(':id_nivel', $datos['id_nivel']);
            $stmt->bindParam(':acceso_restringido', $datos['acceso_restringido']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':id_documento', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Elimina un documento de la base de datos por su ID.
     */
    public function eliminar($id) {
        $sql = "DELETE FROM documento WHERE id_documento = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Obtiene todas las secciones para un formulario <select>
     */
    public function obtenerSecciones() {
         $stmt = $this->db->prepare("SELECT id_seccion, nombre_seccion FROM seccion ORDER BY nombre_seccion");
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los niveles para un formulario <select>
     */
    public function obtenerNiveles() {
         $stmt = $this->db->prepare("SELECT id_nivel, rango, descripcion FROM nivel_peligrosidad ORDER BY id_nivel");
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una lista de documentos que están actualmente disponibles.
     */
    public function obtenerDisponiblesParaPrestamo() {
        $sql = "SELECT id_documento, titulo 
                FROM documento 
                WHERE estado = 'Disponible' 
                ORDER BY titulo ASC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return []; 
        }
    } // <-- Llave de cierre para la función obtenerDisponiblesParaPrestamo()
/**
     * Busca documentos por término de búsqueda, elemento, o clan.
     * Usado por el rol Lector (Ninja).
     *
     * @param string $termino Termino de busqueda
     * @return array Resultados de la búsqueda
     */
    public function buscarDocumentos(string $termino) {
        $sql = "SELECT 
                    d.id_documento,
                    d.titulo,
                    d.tipo,
                    d.estado,
                    s.nombre_seccion,
                    n.rango AS rango_peligrosidad
                FROM documento d
                LEFT JOIN seccion s ON d.id_seccion = s.id_seccion
                LEFT JOIN nivel_peligrosidad n ON d.id_nivel = n.id_nivel
                WHERE 
                    d.titulo LIKE :termino OR
                    d.tipo LIKE :termino OR
                    s.nombre_seccion LIKE :termino OR
                    n.rango LIKE :termino OR
                    d.estado LIKE :termino
                ORDER BY d.titulo ASC";
        
        $paramTermino = '%' . $termino . '%'; // Para la búsqueda LIKE

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':termino', $paramTermino, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
} // <-- ¡¡AQUÍ FALTABA LA LLAVE DE CIERRE FINAL DE LA CLASE!!