<?php
// public/recuperar_password.php - VERSIÓN SIN HASH
session_start();
require_once '../includes/config.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: ../admin/dashboard.php');
    exit();
}

$mensaje = '';
$error = '';
$etapa = 'solicitud';

// Etapa 1: Solicitar recuperación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    if (empty($email)) {
        $error = 'Ingresa tu correo electrónico';
    } else {
        try {
            // Verificar si el email existe y está activo
            $stmt = $conn->prepare("
                SELECT id_usuario, email, nombre 
                FROM usuarios 
                WHERE email = ? AND activo = 1
            ");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            
            if ($usuario) {
                // Generar token único (válido por 1 hora)
                $token = bin2hex(random_bytes(32));
                $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Guardar token en BD
                $stmt = $conn->prepare("
                    UPDATE usuarios 
                    SET reset_token = ?, reset_token_expira = ? 
                    WHERE id_usuario = ?
                ");
                $stmt->execute([$token, $expiracion, $usuario['id_usuario']]);
                
                // En desarrollo, mostramos el token
                if (ENVIRONMENT === 'development') {
                    $mensaje = "Token generado (solo desarrollo): $token<br>";
                    $mensaje .= "Enlace: reset_password.php?token=$token";
                } else {
                    $mensaje = 'Si el correo existe en nuestro sistema, recibirás un enlace de recuperación.';
                }
            } else {
                // Por seguridad, no revelamos si el email existe
                $mensaje = 'Si el correo existe en nuestro sistema, recibirás un enlace de recuperación.';
            }
        } catch (PDOException $e) {
            error_log("Error en recuperación: " . $e->getMessage());
            $error = 'Error interno. Intenta más tarde.';
        }
    }
}

// Verificar si hay token en URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $etapa = 'token';
    
    // Validar token
    try {
        $stmt = $conn->prepare("
            SELECT id_usuario, email, reset_token_expira 
            FROM usuarios 
            WHERE reset_token = ? AND reset_token_expira > NOW()
        ");
        $stmt->execute([$token]);
        $usuario = $stmt->fetch();
        
        if (!$usuario) {
            $error = 'Token inválido o expirado';
            $etapa = 'solicitud';
        } else {
            // Token válido, mostrar formulario de cambio
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar'])) {
                $nueva_password = $_POST['nueva_password'] ?? '';
                $confirmar_password = $_POST['confirmar_password'] ?? '';
                
                if (empty($nueva_password) || empty($confirmar_password)) {
                    $error = 'Completa ambos campos';
                } elseif ($nueva_password !== $confirmar_password) {
                    $error = 'Las contraseñas no coinciden';
                } elseif (strlen($nueva_password) < 6) {
                    $error = 'La contraseña debe tener al menos 6 caracteres';
                } else {
                    // Cambiar contraseña (en texto plano)
                    $stmt = $conn->prepare("
                        UPDATE usuarios 
                        SET contrasena_plano = ?, 
                            reset_token = NULL, 
                            reset_token_expira = NULL 
                        WHERE id_usuario = ?
                    ");
                    $stmt->execute([$nueva_password, $usuario['id_usuario']]);
                    
                    $mensaje = 'Contraseña cambiada exitosamente. Ahora puedes iniciar sesión.';
                    $etapa = 'completado';
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Error validando token: " . $e->getMessage());
        $error = 'Error interno';
        $etapa = 'solicitud';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Burmex</title>
    <link rel="stylesheet" href="../styles/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="../img/img-inicio/header-logo.png" alt="Burmex Logo">
                </div>
                <h1 class="logo-title">
                    <?php 
                    if ($etapa === 'solicitud') echo 'Recuperar Contraseña';
                    elseif ($etapa === 'token' || $etapa === 'completado') echo 'Nueva Contraseña';
                    ?>
                </h1>
                <p class="logo-subtitle">
                    <?php 
                    if ($etapa === 'solicitud') echo 'Ingresa tu correo para recuperar acceso';
                    elseif ($etapa === 'token') echo 'Crea una nueva contraseña';
                    elseif ($etapa === 'completado') echo 'Contraseña restablecida';
                    ?>
                </p>
            </div>

            <?php if ($mensaje): ?>
                <div class="alert alert-success">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($etapa === 'solicitud'): ?>
            <form class="login-form" method="POST" action="">
                <input type="hidden" name="solicitar" value="1">
                
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           placeholder="Ingresa tu correo registrado" required autofocus>
                </div>

                <button type="submit" class="login-button">Enviar enlace de recuperación</button>

                <div class="login-footer">
                    <a href="login.php" class="forgot-link">← Volver al inicio de sesión</a>
                </div>
            </form>
            
            <?php elseif ($etapa === 'token'): ?>
            <form class="login-form" method="POST" action="">
                <input type="hidden" name="cambiar" value="1">
                
                <div class="form-group">
                    <label for="nueva_password" class="form-label">Nueva Contraseña</label>
                    <input type="password" id="nueva_password" name="nueva_password" 
                           class="form-input" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_password" class="form-label">Confirmar Contraseña</label>
                    <input type="password" id="confirmar_password" name="confirmar_password" 
                           class="form-input" placeholder="Repite la contraseña" required>
                </div>

                <button type="submit" class="login-button">Cambiar Contraseña</button>
            </form>
            
            <?php elseif ($etapa === 'completado'): ?>
            <div class="login-form">
                <div class="form-group" style="text-align: center;">
                    <p style="margin-bottom: 20px;">Tu contraseña ha sido restablecida exitosamente.</p>
                    <a href="login.php" class="login-button" style="display: inline-block; text-decoration: none;">
                        Iniciar Sesión
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>