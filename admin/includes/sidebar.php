<?php
// admin/includes/sidebar.php
// Obtener la página actual para marcar como activa
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!-- Sidebar lateral izquierdo -->
<aside class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <a href="dashboard.php" class="nav-link">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_dir == 'ordenes') ? 'active' : ''; ?>">
                <a href="ordenes/index.php" class="nav-link">
                    <span class="nav-icon">📦</span>
                    <span class="nav-text">Órdenes</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_dir == 'marcas') ? 'active' : ''; ?>">
                <a href="marcas/index.php" class="nav-link">
                    <span class="nav-icon">🏷️</span>
                    <span class="nav-text">Marcas</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_dir == 'productos') ? 'active' : ''; ?>">
                <a href="productos/index.php" class="nav-link">
                    <span class="nav-icon">🖥️</span>
                    <span class="nav-text">Productos</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_dir == 'modelos') ? 'active' : ''; ?>">
                <a href="modelos/index.php" class="nav-link">
                    <span class="nav-icon">📐</span>
                    <span class="nav-text">Modelos</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_dir == 'usuarios') ? 'active' : ''; ?>">
                <a href="usuarios/index.php" class="nav-link">
                    <span class="nav-icon">👥</span>
                    <span class="nav-text">Usuarios</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_dir == 'inventario') ? 'active' : ''; ?>">
                <a href="inventario/index.php" class="nav-link">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">Inventario</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_dir == 'clientes') ? 'active' : ''; ?>">
                <a href="clientes/index.php" class="nav-link">
                    <span class="nav-icon">👤</span>
                    <span class="nav-text">Clientes</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_dir == 'reportes') ? 'active' : ''; ?>">
                <a href="reportes/index.php" class="nav-link">
                    <span class="nav-icon">📈</span>
                    <span class="nav-text">Reportes</span>
                </a>
            </li>
            
            <!-- NUEVO: Enlace para cambiar contraseñas de empleados (solo admin/gerente) -->
            <?php 
            // Verificar si el usuario es admin o gerente
            if (isset($_SESSION['usuario_rol']) && 
                ($_SESSION['usuario_rol'] === 'admin' || $_SESSION['usuario_rol'] === 'gerente')): 
            ?>
            <li class="nav-item <?php echo ($current_page == 'cambiar_password_usuario.php') ? 'active' : ''; ?>">
                <a href="cambiar_password_usuario.php" class="nav-link">
                    <span class="nav-icon">🔐</span>
                    <span class="nav-text">Contraseñas</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <!-- Botón cerrar sesión (mantenemos aquí para accesibilidad) -->
        <div class="logout-section">
            <a href="../public/logout.php" class="logout-btn">
                <span class="logout-icon">🚪</span>
                <span class="logout-text">Cerrar sesión</span>
            </a>
        </div>
    </nav>
</aside>