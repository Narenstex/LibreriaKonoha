<?php

class Prestamo {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Obtiene todos los préstamos activos (sin fecha_devolucion_real).
     */
    public function obtenerActivos() {
        $sql = "SELECT 
                    p.id_prestamo, 
                    u.nombre AS nombre_ninja,
                    d.titulo AS titulo_documento,
                    p.fecha_prestamo,
                    p.fecha_devolucion_estimada,
                    p.estado
                FROM prestamo p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                JOIN documento d ON p.id_documento = d.id_documento
                WHERE p.fecha_devolucion_real IS NULL
                ORDER BY p.fecha_devolucion_estimada ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los préstamos vencidos (alerta para ANBU).
     */
    public function obtenerVencidos() {
        $sql = "SELECT 
                    p.id_prestamo, 
                    u.nombre AS nombre_ninja,
                    d.titulo AS titulo_documento,
                    p.fecha_prestamo,
                    p.fecha_devolucion_estimada
                FROM prestamo p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                JOIN documento d ON p.id_documento = d.id_documento
                WHERE p.fecha_devolucion_estimada < CURDATE() 
                AND p.fecha_devolucion_real IS NULL
                ORDER BY p.fecha_devolucion_estimada ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } // <-- ¡AQUÍ FALTABA PROBABLEMENTE LA LLAVE!

    /**
     * Crea un nuevo registro de préstamo en la base de datos.
     * También actualiza el estado del documento a 'En préstamo'.
     *
     * @param array $datos Los datos del formulario
     * @return bool True si tuvo éxito, False si falló
     */
    public function crear(array $datos) {
        // SQL para insertar el préstamo
        $sqlPrestamo = "INSERT INTO prestamo (
                            id_usuario, 
                            id_documento, 
                            fecha_prestamo, 
                            fecha_devolucion_estimada, 
                            estado, 
                            observaciones
                        ) VALUES (
                            :id_usuario, 
                            :id_documento, 
                            :fecha_prestamo, 
                            :fecha_devolucion_estimada, 
                            :estado, 
                            :observaciones
                        )";

        // SQL para actualizar el estado del documento
        $sqlDocumento = "UPDATE documento SET estado = 'En préstamo' 
                         WHERE id_documento = :id_documento";

        try {
            // Iniciar una transacción para asegurar que ambas operaciones funcionen
            $this->db->beginTransaction();

            // 1. Insertar el préstamo
            $stmtPrestamo = $this->db->prepare($sqlPrestamo);
            $stmtPrestamo->bindParam(':id_usuario', $datos['id_usuario']);
            $stmtPrestamo->bindParam(':id_documento', $datos['id_documento']);
            $stmtPrestamo->bindParam(':fecha_prestamo', $datos['fecha_prestamo']);
            $stmtPrestamo->bindParam(':fecha_devolucion_estimada', $datos['fecha_devolucion_estimada']);
            $stmtPrestamo->bindParam(':estado', $datos['estado']);
            $stmtPrestamo->bindParam(':observaciones', $datos['observaciones']);
            $exitoPrestamo = $stmtPrestamo->execute();

            // 2. Actualizar el estado del documento
            $stmtDocumento = $this->db->prepare($sqlDocumento);
            $stmtDocumento->bindParam(':id_documento', $datos['id_documento']);
            $exitoDocumento = $stmtDocumento->execute();

            // 3. Si ambas operaciones tuvieron éxito, confirmar la transacción
            if ($exitoPrestamo && $exitoDocumento) {
                $this->db->commit();
                return true;
            } else {
                // Si alguna falló, deshacer todo
                $this->db->rollBack();
                return false;
            }

        } catch (PDOException $e) {
            // Si hubo un error de BD, deshacer y devolver false
            $this->db->rollBack();
            // (Opcional: registrar el error $e->getMessage())
            return false;
        }
    } // <-- Llave de cierre para la función crear()
    /**
     * Marca un préstamo como devuelto y actualiza el estado del documento.
     *
     * @param int $idPrestamo El ID del préstamo a marcar como devuelto.
     * @return bool True si tuvo éxito, False si falló.
     */
    public function marcarComoDevuelto($idPrestamo) {
        // SQL para obtener el ID del documento asociado al préstamo
        $sqlObtenerDocumento = "SELECT id_documento FROM prestamo WHERE id_prestamo = :id_prestamo";

        // SQL para actualizar el préstamo
        $sqlActualizarPrestamo = "UPDATE prestamo SET 
                                    fecha_devolucion_real = CURDATE(), 
                                    estado = 'Devuelto' 
                                WHERE id_prestamo = :id_prestamo";

        // SQL para actualizar el estado del documento a Disponible
        $sqlActualizarDocumento = "UPDATE documento SET estado = 'Disponible' 
                                   WHERE id_documento = :id_documento";

        try {
            // Iniciar transacción
            $this->db->beginTransaction();

            // 1. Obtener el id_documento del préstamo
            $stmtDocId = $this->db->prepare($sqlObtenerDocumento);
            $stmtDocId->bindParam(':id_prestamo', $idPrestamo, PDO::PARAM_INT);
            $stmtDocId->execute();
            $resultado = $stmtDocId->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                // Si no se encuentra el préstamo, deshacer y retornar false
                $this->db->rollBack();
                return false;
            }
            $idDocumento = $resultado['id_documento'];

            // 2. Actualizar la tabla prestamo
            $stmtPrestamo = $this->db->prepare($sqlActualizarPrestamo);
            $stmtPrestamo->bindParam(':id_prestamo', $idPrestamo, PDO::PARAM_INT);
            $exitoPrestamo = $stmtPrestamo->execute();

            // 3. Actualizar la tabla documento
            $stmtDocumento = $this->db->prepare($sqlActualizarDocumento);
            $stmtDocumento->bindParam(':id_documento', $idDocumento, PDO::PARAM_INT);
            $exitoDocumento = $stmtDocumento->execute();

            // 4. Si ambas actualizaciones funcionaron, confirmar
            if ($exitoPrestamo && $exitoDocumento) {
                $this->db->commit();
                return true;
            } else {
                // Si alguna falló, deshacer
                $this->db->rollBack();
                return false;
            }

        } catch (PDOException $e) {
            // Si hubo error de BD, deshacer
            $this->db->rollBack();
            // (Opcional: registrar el error $e->getMessage())
            return false;
        }
    }
    /**
     * Obtiene el historial completo de préstamos (activos y devueltos).
     * Ordenado por fecha de préstamo más reciente primero.
     *
     * @return array Lista completa de préstamos.
     */
    public function obtenerHistorial() {
        $sql = "SELECT 
                    p.id_prestamo, 
                    u.nombre AS nombre_ninja,
                    d.titulo AS titulo_documento,
                    p.fecha_prestamo,
                    p.fecha_devolucion_estimada,
                    p.fecha_devolucion_real, -- Incluimos la fecha real
                    p.estado
                FROM prestamo p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                JOIN documento d ON p.id_documento = d.id_documento
                ORDER BY p.fecha_prestamo DESC"; // Ordenar por más reciente
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // (Opcional: registrar el error $e->getMessage())
            return []; // Devuelve array vacío si hay error
        }
    } // <-- Llave de cierre para obtenerHistorial()

} // <-- Llave de cierre final de la clase Prestamo

