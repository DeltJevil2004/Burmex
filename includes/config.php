<?php
// CONFIGURACIÓN BÁSICA

define('ENVIRONMENT', 'development');
date_default_timezone_set('America/Mexico_City');

// CONFIGURACIÓN SESIÓN 

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'use_strict_mode' => true,
        'use_only_cookies' => true
    ]);
}


// CONFIGURACIÓN DE BASE DE DATOS

define('DB_HOST', 'localhost');
define('DB_NAME', 'burmex');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


// FUNCIONES BÁSICAS

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function requireAdmin() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ../public/login.php?error=sesion');
        exit();
    }
    
    if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
        header('Location: ../public/index.php?error=permisos');
        exit();
    }
}


// FUNCIONES PARA CONTRASEÑAS 
/**
 * Cambiar contraseña de un usuario 
 * Solo para admin/gerente cambiando contraseñas de otros
 */
function cambiarPasswordUsuario($usuario_id, $nueva_password) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
        return $stmt->execute([$nueva_password, $usuario_id]);
    } catch (PDOException $e) {
        error_log("Error al cambiar password: " . $e->getMessage());
        return false;
    }
}

/**
 * Verificar si el usuario tiene permiso para cambiar contraseñas
 */
function puedeCambiarPassword($usuario_actual_id, $usuario_a_cambiar_id) {
    global $conn;
    
    try {
        // Obtener roles de ambos usuarios
        $stmt = $conn->prepare("
            SELECT u1.rol as rol_actual, u2.rol as rol_a_cambiar 
            FROM usuarios u1, usuarios u2 
            WHERE u1.id_usuario = ? AND u2.id_usuario = ?
        ");
        $stmt->execute([$usuario_actual_id, $usuario_a_cambiar_id]);
        $roles = $stmt->fetch();
        
        if (!$roles) return false;
        
        $rol_actual = $roles['rol_actual'];
        $rol_a_cambiar = $roles['rol_a_cambiar'];
        
        // Admin puede cambiar a cualquiera
        if ($rol_actual === 'admin') return true;
        
        // Gerente solo puede cambiar a empleados
        if ($rol_actual === 'gerente' && $rol_a_cambiar === 'empleado') return true;
        
        // Usuario solo puede cambiar su propia contraseña
        return false;
        
    } catch (PDOException $e) {
        error_log("Error verificando permisos: " . $e->getMessage());
        return false;
    }
}
?>