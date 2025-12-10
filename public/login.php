<?php
// public/login.php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../admin/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burmex - Panel de Administración</title>
    <link rel="stylesheet" href="login.css">
    <link rel="icon" type="image/x-icon" href="../img/img-inicio/logo-icon.ico">
    <link rel="stylesheet" href="../styles/css/login.css"/>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo y título -->
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="../img/img-inicio/header-logo.png" alt="Burmex Logo">
                </div>
                <h1 class="logo-title">Burmex Admin</h1>
                <p class="logo-subtitle">Accede a tu panel de administración</p>
            </div>

            <!-- Mensajes de error/éxito -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php 
                    $errors = [
                        'credenciales' => 'Correo o contraseña incorrectos',
                        'sesion' => 'La sesión ha expirado',
                        'acceso' => 'Acceso denegado',
                        'permisos' => 'No tienes permisos para acceder'
                    ];
                    echo $errors[$_GET['error']] ?? 'Error desconocido';
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    $success = [
                        'logout' => 'Sesión cerrada correctamente',
                        'password' => 'Contraseña actualizada',
                        'password_changed' => 'Contraseña cambiada exitosamente'
                    ];
                    echo $success[$_GET['success']] ?? 'Operación exitosa';
                    ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de login -->
            <form class="login-form" action="auth.php" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           placeholder="Ingresa tu correo electrónico" required autofocus>
                </div>

                <div class="form-group">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-input" 
                           placeholder="Ingresa tu contraseña" required>
                </div>

                <button type="submit" class="login-button">Iniciar sesión</button>

                <div class="login-footer">
         
                    <a href="recuperar_password.php" class="forgot-link">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../js/login.js"></script>
</body>
</html>