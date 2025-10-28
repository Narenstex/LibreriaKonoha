<?php

/**
 * Modelo Documento
 *
 * Se encarga de todas las consultas a la base de datos
 * relacionadas con la tabla 'documento'.
 * Cumple con el Req. de "ver el inventario" y "gestionar libros".
 */
class Documento {

    /**
     * @var PDO La conexión a la base de datos
     */
    private $db;

    /**
     * Constructor: Recibe la conexión PDO.
     * @param PDO $db El objeto de conexión a la BD.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Obtiene TODOS los documentos para mostrar en el inventario.
     * (El "Leer" de CRUD)
     *
     * Usamos JOIN para traernos los nombres de la sección y el nivel,
     * no solo sus IDs.
     */
    public function obtenerTodos() {
        // Tu requisito: "separado por secciones, como autor, titulo"
        // Esta consulta trae todo eso.
        $sql = "SELECT 
                    d.id_documento,
                    d.titulo,
                    d.tipo,
                    d.estado,
                    d.acceso_restringido,
                    s.nombre_seccion,
                    n.rango AS rango_peligrosidad
                FROM 
                    documento d
                LEFT JOIN 
                    seccion s ON d.id_seccion = s.id_seccion
                LEFT JOIN 
                    nivel_peligrosidad n ON d.id_nivel = n.id_nivel
                ORDER BY 
                    d.titulo ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * (Próximamente) Obtiene un solo documento por su ID.
     */
    /**
     * Obtiene un solo documento por su ID.
     * Usado para llenar el formulario de "Editar".
     *
     * @param int $id El ID del documento
     * @return array|false Los datos del documento o false si no se encuentra
     */
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM documento WHERE id_documento = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el documento

        } catch (PDOException $e) {
            return false; // Devuelve false si hubo un error
        }
    }

    /**
     * (Próximamente) Crea un nuevo documento.
     * (El "Crear" de CRUD)
     */
    /**
     * Crea un nuevo documento en la base de datos.
     * (El "Crear" de CRUD)
     *
     * @param array $datos Los datos del formulario
     * @return bool True si tuvo éxito, False si falló
     */
    public function crear(array $datos) {
        $sql = "INSERT INTO documento (
                    titulo, 
                    tipo, 
                    id_seccion, 
                    id_nivel, 
                    fecha_ingreso, 
                    acceso_restringido, 
                    estado
                ) VALUES (
                    :titulo, 
                    :tipo, 
                    :id_seccion, 
                    :id_nivel, 
                    :fecha_ingreso, 
                    :acceso_restringido, 
                    :estado
                )";
        
        try {
            $stmt = $this->db->prepare($sql);
            
            // Vincular todos los parámetros
            $stmt->bindParam(':titulo', $datos['titulo']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':id_seccion', $datos['id_seccion']);
            $stmt->bindParam(':id_nivel', $datos['id_nivel']);
            $stmt->bindParam(':fecha_ingreso', $datos['fecha_ingreso']);
            $stmt->bindParam(':acceso_restringido', $datos['acceso_restringido']);
            $stmt->bindParam(':estado', $datos['estado']);
            
            return $stmt->execute(); // Devuelve true si la inserción fue exitosa

        } catch (PDOException $e) {
            // (Opcional: registrar el error $e->getMessage())
            return false; // Devuelve false si hubo un error
        }
    }

    /**
     * (Próximamente) Actualiza un documento existente.
     * (El "Actualizar" de CRUD)
     */
    /**
     * Actualiza un documento existente en la base de datos.
     * (El "Actualizar" de CRUD)
     *
     * @param int $id El ID del documento a actualizar
     * @param array $datos Los nuevos datos del formulario
     * @return bool True si tuvo éxito, False si falló
     */
    public function actualizar($id, array $datos) {
        $sql = "UPDATE documento SET
                    titulo = :titulo,
                    tipo = :tipo,
                    id_seccion = :id_seccion,
                    id_nivel = :id_nivel,
                    acceso_restringido = :acceso_restringido,
                    estado = :estado
                WHERE
                    id_documento = :id_documento";
        
        try {
            $stmt = $this->db->prepare($sql);
            
            // Vincular todos los parámetros
            $stmt->bindParam(':titulo', $datos['titulo']);
            $stmt->bindParam(':tipo', $datos['tipo']);
            $stmt->bindParam(':id_seccion', $datos['id_seccion']);
            $stmt->bindParam(':id_nivel', $datos['id_nivel']);
            $stmt->bindParam(':acceso_restringido', $datos['acceso_restringido']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':id_documento', $id, PDO::PARAM_INT);
            
            return $stmt->execute(); // Devuelve true si la actualización fue exitosa

        } catch (PDOException $e) {
            // (Opcional: registrar el error $e->getMessage())
            return false; // Devuelve false si hubo un error
        }
    }

    /**
     * Elimina un documento de la base de datos por su ID.
     * (El "Eliminar" de CRUD)
     *
     * @param int $id El ID del documento a eliminar
     * @return bool True si tuvo éxito, False si falló
     */
    public function eliminar($id) {
        $sql = "DELETE FROM documento WHERE id_documento = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute(); // Devuelve true si la eliminación fue exitosa

        } catch (PDOException $e) {
            // (Opcional: registrar el error $e->getMessage())
            return false; // Devuelve false si hubo un error
        }
    }

    /**
     * (Helper) Obtiene todas las secciones para un formulario <select>
     */
    public function obtenerSecciones() {
         $stmt = $this->db->prepare("SELECT id_seccion, nombre_seccion FROM seccion ORDER BY nombre_seccion");
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * (Helper) Obtiene todos los niveles para un formulario <select>
     */
    public function obtenerNiveles() {
         $stmt = $this->db->prepare("SELECT id_nivel, rango, descripcion FROM nivel_peligrosidad ORDER BY id_nivel");
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}