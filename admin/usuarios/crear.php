<?php
session_start();

// Verificar si usuario está logueado y es admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../public/login.php?error=permisos');
    exit();
}

require_once '../../includes/config.php';

$mensaje = '';
$error = '';

// Procesar creación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $rol = $_POST['rol'] ?? 'empleado';
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    // Validaciones
    if (empty($email) || empty($nombre) || empty($apellido) || empty($password)) {
        $error = 'Todos los campos son obligatorios';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email no válido';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } else {
        try {
            // Verificar si el email ya existe
            $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error = 'El email ya está registrado';
            } else {
                // Insertar nuevo usuario
                $sql = "INSERT INTO usuarios (email, password, nombre, apellido, rol, activo) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$email, $password, $nombre, $apellido, $rol, $activo]);
                
                $mensaje = 'Usuario creado exitosamente';
                // Redirigir después de 2 segundos
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "../usuarios.php";
                    }, 2000);
                </script>';
            }
        } catch (PDOException $e) {
            error_log("Error creando usuario: " . $e->getMessage());
            $error = 'Error al crear el usuario';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario - Burmex Admin</title>
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
                    <h1>Nuevo Usuario</h1>
                    <p class="subtitulo-seccion">Agrega un nuevo usuario al sistema</p>
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

            <!-- Formulario -->
            <div class="contenedor-formulario">
                <form method="POST" action="">
                    <div class="grupo-formulario">
                        <label for="email" class="etiqueta-formulario">Email *</label>
                        <input type="email" id="email" name="email" 
                               class="input-formulario" 
                               placeholder="ejemplo@correo.com" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                               required>
                    </div>

                    <div class="grupo-doble">
                        <div class="grupo-formulario">
                            <label for="nombre" class="etiqueta-formulario">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" 
                                   class="input-formulario" 
                                   placeholder="Nombre" 
                                   value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" 
                                   required>
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="apellido" class="etiqueta-formulario">Apellido *</label>
                            <input type="text" id="apellido" name="apellido" 
                                   class="input-formulario" 
                                   placeholder="Apellido" 
                                   value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>" 
                                   required>
                        </div>
                    </div>

                    <div class="grupo-doble">
                        <div class="grupo-formulario">
                            <label for="password" class="etiqueta-formulario">Contraseña *</label>
                            <input type="password" id="password" name="password" 
                                   class="input-formulario" 
                                   placeholder="Mínimo 6 caracteres" 
                                   minlength="6" required>
                            <p class="texto-ayuda">Mínimo 6 caracteres</p>
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="confirm_password" class="etiqueta-formulario">Confirmar Contraseña *</label>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   class="input-formulario" 
                                   placeholder="Repite la contraseña" 
                                   minlength="6" required>
                        </div>
                    </div>

                    <div class="grupo-formulario">
                        <label for="rol" class="etiqueta-formulario">Rol</label>
                        <select id="rol" name="rol" class="select-formulario">
                            <option value="empleado" <?php echo ($_POST['rol'] ?? 'empleado') === 'empleado' ? 'selected' : ''; ?>>Empleado</option>
                            <option value="gerente" <?php echo ($_POST['rol'] ?? 'empleado') === 'gerente' ? 'selected' : ''; ?>>Gerente</option>
                            <option value="admin" <?php echo ($_POST['rol'] ?? 'empleado') === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>

                    <div class="grupo-formulario">
                        <label class="checkbox-formulario">
                            <input type="checkbox" name="activo" value="1" 
                                   <?php echo isset($_POST['activo']) ? 'checked' : 'checked'; ?>>
                            <span>Usuario activo</span>
                        </label>
                        <p class="texto-ayuda">Los usuarios inactivos no pueden iniciar sesión</p>
                    </div>

                    <!-- Botones -->
                    <div class="contenedor-botones">
                        <button type="button" class="btn-cancelar" onclick="window.location.href='../usuarios.php'">
                            Cancelar
                        </button>
                        <button type="submit" class="btn-crear">
                            <i class="fas fa-plus"></i> Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>