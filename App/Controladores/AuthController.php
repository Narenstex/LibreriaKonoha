<?php

/**
 * AuthController (Controlador de Autenticación)
 * Maneja login, logout, recuperación y ¡registro!
 */

// Cargar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AuthController {

    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // --- LOGIN ---
    public function mostrarLogin() {
        require_once __DIR__ . '/../Vistas/Autenticacion/login.php';
    }

    public function procesarLogin() {
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $this->redirigirA('login', 'error', 'Email y contraseña son obligatorios.');
            return;
        }

        try {
            $sql = "SELECT u.*, r.nombre_rol 
                    FROM usuario u
                    LEFT JOIN rol r ON u.id_rol = r.id_rol
                    WHERE u.email = :email";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                $this->crearSesionUsuario($usuario);
                $this->redirigirA('dashboard');
            } else {
                $this->redirigirA('login', 'error', 'Credenciales incorrectas.');
            }
        } catch (PDOException $e) {
            $this->redirigirA('login', 'error', 'Error en la base de datos: ' . $e->getMessage());
        }
    }

    // --- LOGOUT ---
    public function logout() {
        session_unset();
        session_destroy();
        $this->redirigirA('login', 'exito', 'Has cerrado sesión correctamente.');
    }

    // --- RECUPERACIÓN (PASO 1) ---
    public function mostrarRecuperar() {
        require_once __DIR__ . '/../Vistas/Autenticacion/recuperar.php';
    }

    public function procesarRecuperar() {
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        if (empty($email)) { /* ... (código de validación) ... */ }

        try {
            $stmt = $this->db->prepare("SELECT id_usuario FROM usuario WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $this->redirigirA('recuperar', 'exito', 'Si tu email existe, recibirás un enlace.');
                return;
            }

            $token = bin2hex(random_bytes(32)); 
            $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour')); 

            $stmt = $this->db->prepare("UPDATE usuario SET token_recuperacion = :token, expiracion_token = :expira WHERE id_usuario = :id");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expira', $expiracion);
            $stmt->bindParam(':id', $usuario['id_usuario']);
            $stmt->execute();

            $enviado = $this->enviarCorreoRecuperacion($email, $token);

            if ($enviado) {
                $this->redirigirA('recuperar', 'exito', 'Si tu email existe, recibirás un enlace.');
            } else {
                $this->redirigirA('recuperar', 'error', 'No se pudo enviar el correo.');
            }
        } catch (PDOException $e) {
            $this->redirigirA('recuperar', 'error', 'Error de base de datos.');
        } catch (Exception $e) {
            $this->redirigirA('recuperar', 'error', 'Error al enviar correo: ' . $e->getMessage());
        }
    }

    private function enviarCorreoRecuperacion($email, $token) {
        require_once __DIR__ . '/../PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; 

        try {
            // --- ¡¡TU CONFIGURACIÓN DE GMAIL!! ---
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tu.correo.real@gmail.com'; // <-- TU EMAIL
            $mail->Password   = 'tu_clave_de_16_letras'; // <-- TU CLAVE
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            $mail->setFrom('tu.correo.real@gmail.com', 'Biblioteca de Konoha');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Restablece tu contraseña - Biblioteca de Konoha';
            $enlace = "http://localhost/LIBRERIAKONOHA/reset?token=" . $token;
            $mail->Body    = "Hola,<br><br>Haz clic en el siguiente enlace para restablecer tu contraseña:<br>" .
                           "<a href='" . $enlace . "'>Restablecer mi contraseña</a><br><br>" .
                           "Este enlace expira en 1 hora.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // --- RECUPERACIÓN (PASO 2) ---
    public function mostrarReset() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            $this->redirigirA('login', 'error', 'Token no proporcionado.');
            return;
        }

        try {
            $sql = "SELECT id_usuario FROM usuario 
                    WHERE token_recuperacion = :token AND expiracion_token > NOW()";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $this->redirigirA('login', 'error', 'El enlace es inválido o ha expirado.');
                return;
            }

            $data['token'] = $token;
            require_once __DIR__ . '/../Vistas/Autenticacion/reset.php';

        } catch (PDOException $e) {
            $this->redirigirA('login', 'error', 'Error de base de datos.');
        }
    }

    public function procesarReset() {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($password) || empty($password_confirm)) {
            $this->redirigirA('reset', 'error', 'Debes llenar ambos campos.', ['token' => $token]);
            return;
        }
        if ($password !== $password_confirm) {
            $this->redirigirA('reset', 'error', 'Las contraseñas no coinciden.', ['token' => $token]);
            return;
        }
        if (strlen($password) < 6) {
             $this->redirigirA('reset', 'error', 'La contraseña debe tener al menos 6 caracteres.', ['token' => $token]);
            return;
        }

        try {
            $sql = "SELECT id_usuario FROM usuario 
                    WHERE token_recuperacion = :token AND expiracion_token > NOW()";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $this->redirigirA('login', 'error', 'El enlace es inválido o ha expirado.');
                return;
            }

            $nuevo_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE usuario SET
                                password_hash = :hash,
                                token_recuperacion = NULL,
                                expiracion_token = NULL
                           WHERE id_usuario = :id";
            
            $stmt_update = $this->db->prepare($sql_update);
            $stmt_update->bindParam(':hash', $nuevo_hash);
            $stmt_update->bindParam(':id', $usuario['id_usuario']);
            $stmt_update->execute();

            $this->redirigirA('login', 'exito', '¡Contraseña actualizada! Ya puedes iniciar sesión.');
        } catch (PDOException $e) {
            $this->redirigirA('login', 'error', 'Error de base de datos.');
        }
    }

    // -----------------------------------------------------------------
    // --- ¡NUEVO! FUNCIÓN 1: MOSTRAR EL FORMULARIO DE REGISTRO ---
    // -----------------------------------------------------------------
    public function mostrarRegistro() {
        require_once __DIR__ . '/../Vistas/Autenticacion/registro.php';
    }

    // -----------------------------------------------------------------
    // --- ¡NUEVO! FUNCIÓN 2: PROCESAR EL NUEVO REGISTRO ---
    // -----------------------------------------------------------------
    public function procesarRegistro() {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        // 1. Validaciones del servidor
        if (empty($nombre) || empty($email) || empty($password)) {
            $this->redirigirA('registro', 'error', 'Todos los campos son obligatorios.');
            return;
        }
        if ($password !== $password_confirm) {
            $this->redirigirA('registro', 'error', 'Las contraseñas no coinciden.');
            return;
        }
        if (strlen($password) < 6) {
             $this->redirigirA('registro', 'error', 'La contraseña debe tener al menos 6 caracteres.');
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirigirA('registro', 'error', 'El email no es válido.');
            return;
        }

        try {
            // 2. Revisar si el email ya existe
            $stmt = $this->db->prepare("SELECT id_usuario FROM usuario WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetch()) {
                $this->redirigirA('registro', 'error', 'Este email ya está registrado.');
                return;
            }

            // 3. Crear el usuario
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $id_rol_default = 2; // ID 2 = 'Ninja' (Lector)

            $sql = "INSERT INTO usuario (nombre, email, password_hash, id_rol) 
                    VALUES (:nombre, :email, :hash, :id_rol)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hash', $hash);
            $stmt->bindParam(':id_rol', $id_rol_default);
            $stmt->execute();

            // 4. Redirigir al login con mensaje de éxito
            $this->redirigirA('login', 'exito', '¡Cuenta creada! Ya puedes iniciar sesión.');

        } catch (PDOException $e) {
            $this->redirigirA('registro', 'error', 'Error de base de datos.');
        }
    }


    /* --- FUNCIONES HELPER (PRIVADAS) --- */
    private function crearSesionUsuario(array $usuario) {
        session_regenerate_id(true); 
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['nombre_rol']; 
    }

    private function redirigirA(string $url, string $tipo = null, string $mensaje = null, array $params = []) {
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