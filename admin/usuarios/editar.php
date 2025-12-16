<?php

session_start();

// Verificar si usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../public/login.php?error=sesion');
    exit();
}

require_once '../../includes/config.php';

$id = $_GET['id'] ?? 0;
$mensaje = '';
$error = '';
$usuario = null;

// Obtener datos del usuario
try {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        $error = 'Usuario no encontrado';
    }
} catch (PDOException $e) {
    error_log("Error obteniendo usuario: " . $e->getMessage());
    $error = 'Error al cargar el usuario';
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $rol = $_POST['rol'] ?? 'empleado';
    $activo = isset($_POST['activo']) ? 1 : 0;
    $cambiar_password = isset($_POST['cambiar_password']) ? 1 : 0;
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    // Validaciones
    if (empty($email) || empty($nombre) || empty($apellido)) {
        $error = 'Todos los campos son obligatorios';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email no válido';
    } elseif ($cambiar_password && strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif ($cambiar_password && $password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } else {
        try {
            // Verificar si el email ya existe en otro usuario
            $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
            $stmt->execute([$email, $id]);
            
            if ($stmt->fetch()) {
                $error = 'El email ya está registrado por otro usuario';
            } else {
                if ($cambiar_password) {
                    // Actualizar usuario con nueva contraseña
                    $sql = "UPDATE usuarios 
                            SET email = ?, password = ?, nombre = ?, apellido = ?, rol = ?, activo = ? 
                            WHERE id_usuario = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$email, $password, $nombre, $apellido, $rol, $activo, $id]);
                } else {
                    // Actualizar usuario sin cambiar contraseña
                    $sql = "UPDATE usuarios 
                            SET email = ?, nombre = ?, apellido = ?, rol = ?, activo = ? 
                            WHERE id_usuario = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$email, $nombre, $apellido, $rol, $activo, $id]);
                }
                
                $mensaje = 'Usuario actualizado exitosamente';
                // Actualizar datos locales
                $usuario['email'] = $email;
                $usuario['nombre'] = $nombre;
                $usuario['apellido'] = $apellido;
                $usuario['rol'] = $rol;
                $usuario['activo'] = $activo;
            }
        } catch (PDOException $e) {
            error_log("Error actualizando usuario: " . $e->getMessage());
            $error = 'Error al actualizar el usuario';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Burmex Admin</title>
    <link rel="stylesheet" href="../../styles/css/dashboard.css">
    <link rel="stylesheet" href="../../styles/css/usuarios.css">
    <link rel="icon" type="image/x-icon" href="../../img/img-inicio/logo-icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include_once '../includes/navbar.php'; ?>
    <?php include_once '../includes/sidebar.php'; ?>
    
    <div class="capa-lateral"></div>

    <main class="contenido-principal">
        <div class="contenedor">
            <!-- Encabezado -->
            <div class="encabezado-usuarios">
                <div class="titulo-seccion">
                    <h1>Editar Usuario</h1>
                    <p class="subtitulo-seccion">Modifica los datos del usuario</p>
                </div>
                <button class="btn-nuevo-usuario" onclick="window.location.href='../usuarios.php'">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
            </div>

            <!-- Mensajes -->
            <?php if ($mensaje): ?>
                <div class="alert alert-success">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($usuario): ?>
            <!-- Formulario -->
            <div class="contenedor-formulario">
                <form method="POST" action="">
                    <div class="grupo-formulario">
                        <label for="email" class="etiqueta-formulario">Email *</label>
                        <input type="email" id="email" name="email" 
                               class="input-formulario" 
                               value="<?php echo htmlspecialchars($usuario['email']); ?>" 
                               required>
                    </div>

                    <div class="grupo-doble">
                        <div class="grupo-formulario">
                            <label for="nombre" class="etiqueta-formulario">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" 
                                   class="input-formulario" 
                                   value="<?php echo htmlspecialchars($usuario['nombre']); ?>" 
                                   required>
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="apellido" class="etiqueta-formulario">Apellido *</label>
                            <input type="text" id="apellido" name="apellido" 
                                   class="input-formulario" 
                                   value="<?php echo htmlspecialchars($usuario['apellido']); ?>" 
                                   required>
                        </div>
                    </div>

                    <!-- Cambio de contraseña -->
                    <div class="grupo-formulario">
                        <label class="checkbox-formulario">
                            <input type="checkbox" name="cambiar_password" id="cambiar_password" value="1">
                            <span>Cambiar contraseña</span>
                        </label>
                    </div>

                    <div class="grupo-doble campos-password" style="display: none;">
                        <div class="grupo-formulario">
                            <label for="password" class="etiqueta-formulario">Nueva Contraseña</label>
                            <input type="password" id="password" name="password" 
                                   class="input-formulario" 
                                   placeholder="Dejar en blanco para mantener la actual"
                                   minlength="6">
                            <p class="texto-ayuda">Mínimo 6 caracteres</p>
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="confirm_password" class="etiqueta-formulario">Confirmar Contraseña</label>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   class="input-formulario" 
                                   placeholder="Repite la contraseña"
                                   minlength="6">
                        </div>
                    </div>

                    <div class="grupo-formulario">
                        <label for="rol" class="etiqueta-formulario">Rol</label>
                        <select id="rol" name="rol" class="select-formulario">
                            <option value="empleado" <?php echo $usuario['rol'] === 'empleado' ? 'selected' : ''; ?>>Empleado</option>
                            <option value="gerente" <?php echo $usuario['rol'] === 'gerente' ? 'selected' : ''; ?>>Gerente</option>
                            <option value="admin" <?php echo $usuario['rol'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>

                    <div class="grupo-formulario">
                        <label class="checkbox-formulario">
                            <input type="checkbox" name="activo" value="1" 
                                   <?php echo $usuario['activo'] ? 'checked' : ''; ?>>
                            <span>Usuario activo</span>
                        </label>
                        <p class="texto-ayuda">Los usuarios inactivos no pueden iniciar sesión</p>
                    </div>

                    <!-- Botones -->
                    <div class="contenedor-botones">
                        <button type="button" class="btn-cancelar" onclick="window.location.href='../usuarios.php'">
                            Cancelar
                        </button>
                        <button type="submit" class="btn-actualizar">
                            <i class="fas fa-save"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>

            <script>
            document.getElementById('cambiar_password').addEventListener('change', function(e) {
                const camposPassword = document.querySelector('.campos-password');
                const passwordInput = document.getElementById('password');
                const confirmInput = document.getElementById('confirm_password');
                
                if (e.target.checked) {
                    camposPassword.style.display = 'grid';
                    passwordInput.required = true;
                    confirmInput.required = true;
                } else {
                    camposPassword.style.display = 'none';
                    passwordInput.required = false;
                    confirmInput.required = false;
                    passwordInput.value = '';
                    confirmInput.value = '';
                }
            });
            </script>
            <?php else: ?>
                <div class="alert alert-error">
                    Usuario no encontrado
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>