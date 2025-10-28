<?php

/**
 * Archivo de Configuración de la Base de Datos (Req. 2)
 *
 * Define las constantes para la conexión y crea
 * una clase 'Database' para manejarla usando PDO.
 */

// --- CONFIGURACIÓN DE TU XAMPP ---
define('DB_HOST', '127.0.0.1'); // Es lo mismo que 'localhost'
define('DB_USER', 'root');     // Usuario por defecto de XAMPP
define('DB_PASS', '');         // Contraseña por defecto (vacía)
define('DB_NAME', 'biblioteca_konoha'); // El nombre de tu BD

// --- CLASE DE CONEXIÓN ---
class Database {
    
    // Propiedades de la conexión
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    // Propiedades internas
    private $dbh; // Database Handler (Manejador de la BD)
    private $stmt; // Statement (Sentencia SQL)
    private $error; // Para guardar errores

    /**
     * Constructor: Se ejecuta automáticamente al crear un objeto Database.
     * Intenta conectarse a la BD.
     */
    public function __construct() {
        // 1. Configurar el DSN (Data Source Name)
        // ¡¡IMPORTANTE: Usamos el puerto 3309 que usa tu XAMPP!!
        $dsn = 'mysql:host=' . $this->host . ';port=3306;dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // 2. Opciones de PDO
        $options = [
            PDO::ATTR_PERSISTENT => true, // Conexiones persistentes
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Manejo de errores
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Traer datos como array asociativo
        ];

        // 3. Crear la instancia de PDO (el intento de conexión)
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->dbh->exec("SET CHARACTER SET utf8mb4");

        } catch (PDOException $e) {
            // Si la conexión falla, guarda el error y detiene la app
            $this->error = $e->getMessage();
            die('Error de conexión: ' . $this->error);
        }
    }

    /**
     * Método para obtener el manejador de la base de datos.
     * Lo usaremos en los Modelos.
     *
     * @return PDO El objeto de conexión PDO.
     */
    public function getConnection() {
        return $this->dbh;
    }
}

// --- INICIO DE LA APP ---
// Iniciar la sesión aquí para que esté disponible en toda la aplicación.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

