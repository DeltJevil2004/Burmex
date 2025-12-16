<?php
require_once '../includes/config.php';

// INICIAR SESIÓN
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php?error=acceso');
    exit();
}

// Obtener datos del formulario
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$contrasena = $_POST['contrasena'] ?? '';

// Validar datos
if (empty($email) || empty($contrasena)) {
    header('Location: login.php?error=credenciales');
    exit();
}

try {
    // Buscar usuario en la base de datos 
    $stmt = $conn->prepare("
        SELECT * FROM usuarios 
        WHERE email = ? 
        AND password = ?
        AND activo = 1
    ");
    $stmt->execute([$email, $contrasena]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el usuario
    if (!$usuario) {
        header('Location: login.php?error=credenciales');
        exit();
    }

    // Login exitoso
    $_SESSION['usuario_id'] = $usuario['id_usuario'];
    $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
    $_SESSION['usuario_rol'] = $usuario['rol'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['login_time'] = time();
    
    // Redirigir al dashboard
    header('Location: ../admin/dashboard.php');
    exit();
    
} catch (PDOException $e) {
    error_log("Error en auth.php: " . $e->getMessage());
    header('Location: login.php?error=bd');
    exit();
}
?>