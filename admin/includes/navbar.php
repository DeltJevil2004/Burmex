<?php
// Verificar si usuario está logueado (la sesión ya debe estar activa)
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al login
    $redirectUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $redirectUrl .= "://" . $_SERVER['HTTP_HOST'];
    $redirectUrl .= str_replace('/admin/includes/navbar.php', '/public/login.php', $_SERVER['SCRIPT_NAME']);
    $redirectUrl .= '?error=sesion';
    
    echo '<script>window.location.href = "' . $redirectUrl . '";</script>';
    exit();
}

// Datos del usuario actual desde la sesión
$nombre_usuario = $_SESSION['usuario_nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['usuario_rol'] ?? 'usuario';
$email_usuario = $_SESSION['usuario_email'] ?? '';

// Obtener primera letra del nombre para el avatar
$avatar_letter = !empty($nombre_usuario) ? strtoupper(substr($nombre_usuario, 0, 1)) : 'U';
?>
<!-- Navbar Superior -->
<nav class="navbar">
    <div class="navbar-left">
        <!-- Botón Hamburguesa -->
        <button class="hamburger-btn" id="hamburger-btn" aria-label="Abrir menú">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
        
        <!-- Logo -->
        <div class="logo">
            <img src="../../img/img-inicio/header-logo.png" alt="Burmex Logo" class="logo-img">
        </div>
    </div>
    
    <div class="navbar-right">
        <!-- Notificaciones -->
        <div class="notifications">
            <button class="notifications-btn" id="notificationsBtn">
                <img src="../../img/notificacion.png" alt="Notificaciones" class="notifications-icon">
            </button>
            <div class="notifications-dropdown" id="notificationsDropdown">
                <div class="notifications-header">
                    <h4>Notificaciones</h4>
                </div>
                <div class="notifications-list">
                    <div class="no-notifications">
                        No hay notificaciones
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Perfil del usuario -->
        <div class="user-profile" id="userProfileBtn">
            <div class="user-avatar">
                <span class="avatar-text"><?php echo htmlspecialchars($avatar_letter); ?></span>
            </div>
            <div class="user-info">
                <p class="user-name"><?php echo htmlspecialchars($nombre_usuario); ?></p>
                <p class="user-role"><?php echo htmlspecialchars(ucfirst($rol_usuario)); ?></p>
            </div>
            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <p class="dropdown-email"><?php echo htmlspecialchars($email_usuario); ?></p>
                </div>
                <a href="../public/logout.php" class="dropdown-item logout">
                    <span class="dropdown-icon">🚪</span> Cerrar sesión
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Overlay para móvil -->
<div class="sidebar-overlay"></div>

<script src="/js/dashboard.js"></script>

</script>