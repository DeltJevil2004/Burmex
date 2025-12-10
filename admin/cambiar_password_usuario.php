<?php
// admin/cambiar_password_usuario.php - VERSIÓN SIN HASH
require_once '../includes/config.php';
// NO session_start() aquí - ya se inició en config.php

// Verificar que el usuario esté logueado y sea admin o gerente
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php?error=sesion');
    exit();
}

if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_rol'] !== 'gerente') {
    header('Location: dashboard.php?error=permisos');
    exit();
}

$mensaje = '';
$error = '';
$usuarios = [];

// Obtener lista de usuarios (empleados y gerentes)
try {
    // Solo mostrar usuarios que NO sean admin (a menos que sea admin)
    if ($_SESSION['usuario_rol'] === 'admin') {
        // Admin puede ver a todos excepto a sí mismo
        $stmt = $conn->prepare("
            SELECT id_usuario, email, nombre, apellido, rol 
            FROM usuarios 
            WHERE activo = 1 
            AND rol IN ('empleado', 'gerente')  -- Solo empleados y gerentes
            ORDER BY 
                FIELD(rol, 'gerente', 'empleado'),  -- Primero gerentes, luego empleados
                nombre, apellido
        ");
    } else {
        // Gerente solo puede ver empleados (no otros gerentes ni admins)
        $stmt = $conn->prepare("
            SELECT id_usuario, email, nombre, apellido, rol 
            FROM usuarios 
            WHERE activo = 1 AND rol = 'empleado'
            ORDER BY nombre, apellido
        ");
    }
    $stmt->execute();
    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error al cargar usuarios: " . $e->getMessage();
}

// Procesar cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_password'])) {
    $usuario_id = $_POST['usuario_id'] ?? 0;
    $nueva_password = $_POST['nueva_password'] ?? '';
    $confirmar_password = $_POST['confirmar_password'] ?? '';
    
    // Validaciones
    if (empty($usuario_id) || $usuario_id <= 0) {
        $error = 'Selecciona un usuario';
    } elseif (empty($nueva_password) || empty($confirmar_password)) {
        $error = 'Completa ambos campos de contraseña';
    } elseif ($nueva_password !== $confirmar_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($nueva_password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        try {
            // Verificar que el usuario a modificar existe y tiene permisos
            $stmt = $conn->prepare("SELECT rol FROM usuarios WHERE id_usuario = ? AND activo = 1");
            $stmt->execute([$usuario_id]);
            $usuario_a_modificar = $stmt->fetch();
            
            if (!$usuario_a_modificar) {
                $error = 'Usuario no encontrado';
            } else {
                // Restricciones de permisos
                $rol_usuario_modificar = $usuario_a_modificar['rol'];
                $rol_usuario_actual = $_SESSION['usuario_rol'];
                
                // Admin puede cambiar a empleados y gerentes
                if ($rol_usuario_actual === 'admin') {
                    // Admin no puede cambiarse su propia contraseña desde aquí
                    if ($usuario_id == $_SESSION['usuario_id']) {
                        $error = 'No puedes cambiar tu propia contraseña desde aquí. Usa otra opción.';
                    }
                    // Admin puede cambiar a empleados y gerentes
                    elseif (!in_array($rol_usuario_modificar, ['empleado', 'gerente'])) {
                        $error = 'Solo puedes cambiar contraseñas de empleados y gerentes';
                    }
                } 
                // Gerente solo puede cambiar a empleados
                elseif ($rol_usuario_actual === 'gerente') {
                    if ($rol_usuario_modificar !== 'empleado') {
                        $error = 'Solo puedes cambiar contraseñas de empleados';
                    }
                } else {
                    $error = 'No tienes permisos para cambiar contraseñas';
                }
                
                // Si no hay errores de permisos, proceder
                if (empty($error)) {
                    // Actualizar contraseña en texto plano
                    $stmt = $conn->prepare("UPDATE usuarios SET contrasena_plano = ? WHERE id_usuario = ?");
                    $stmt->execute([$nueva_password, $usuario_id]);
                    
                    $mensaje = 'Contraseña actualizada correctamente';
                }
            }
        } catch (PDOException $e) {
            error_log("Error al cambiar contraseña: " . $e->getMessage());
            $error = 'Error interno del sistema';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña de Empleado - Burmex Admin</title>
    <link rel="stylesheet" href="../styles/css/dashboard.css">
    <link rel="stylesheet" href="../styles/css/cambiar_password_usuario.css">
    <style>
        /* Tus estilos CSS aquí (el responsive que ya tienes) */
        .container { padding: 20px; width: 100%; box-sizing: border-box; }
        .card { background: white; border-radius: 8px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        /* ... resto de tus estilos ... */
    </style>
</head>
<body>
    <?php include_once 'includes/navbar.php'; ?>
    
    <div class="dashboard-container">
        <?php include_once 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="container">
                <h1>Cambiar Contraseña de Empleado</h1>
                
                <?php if ($mensaje): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <h2>Seleccionar Empleado</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="usuario_id">Empleado:</label>
                            <select id="usuario_id" name="usuario_id" required>
                                <option value="">-- Selecciona un empleado --</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <?php 
                                        // No permitir cambiar la propia contraseña aquí
                                        if ($usuario['id_usuario'] == $_SESSION['usuario_id']) continue;
                                    ?>
                                    <option value="<?php echo $usuario['id_usuario']; ?>">
                                        <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?> 
                                        (<?php echo htmlspecialchars($usuario['email']); ?>)
                                        - <span class="badge badge-<?php echo $usuario['rol']; ?>">
                                            <?php echo $usuario['rol']; ?>
                                        </span>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nueva_password">Nueva Contraseña:</label>
                            <input type="password" id="nueva_password" name="nueva_password" 
                                   placeholder="Mínimo 6 caracteres" minlength="6" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmar_password">Confirmar Contraseña:</label>
                            <input type="password" id="confirmar_password" name="confirmar_password" required>
                        </div>
                        
                        <div class="button-group">
                            <button type="submit" name="cambiar_password" class="btn">Cambiar Contraseña</button>
                            <a href="dashboard.php" class="btn-cancel">Cancelar</a>
                        </div>
                    </form>
                </div>
                
                <?php if (!empty($usuarios)): ?>
                <div class="card">
                    <h2>Lista de Empleados</h2>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $usuario['rol']; ?>">
                                            <?php echo $usuario['rol']; ?>
                                        </span>
                                    </td>
                                    <td>Activo</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

        <!-- JavaScript -->
    <script src="../js/dashboard.js"></script>
</body>
</html>