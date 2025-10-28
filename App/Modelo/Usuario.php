<?php

/**
 * Modelo Usuario
 *
 * Se encarga de consultas relacionadas con la tabla 'usuario'.
 * Por ahora, solo necesitamos obtener una lista de usuarios
 * que pueden tomar prestados documentos.
 */
class Usuario {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Obtiene una lista de usuarios (potenciales prestatarios).
     * Podríamos excluir roles específicos si fuera necesario (ej: Hokage).
     *
     * @return array Lista de usuarios [id_usuario, nombre]
     */
    public function obtenerUsuariosParaPrestamo() {
        // Seleccionamos solo ID y Nombre, ordenados por nombre.
        // Podríamos añadir un WHERE si quisiéramos excluir ciertos roles.
        // WHERE id_rol != 1 (Excluir Hokage)
        $sql = "SELECT id_usuario, nombre 
                FROM usuario 
                ORDER BY nombre ASC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return []; // Devuelve array vacío si hay error
        }
    }
}