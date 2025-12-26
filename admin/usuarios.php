<?php
session_start();

// Verificar si usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php?error=sesion');
    exit();
}

require_once '../includes/config.php';

// Configuración de paginación
$usuarios_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

$offset = ($pagina_actual - 1) * $usuarios_por_pagina;
$rol_filtro = $_GET['rol'] ?? 'todos';

// Variables para formulario
$mensaje = '';
$error = '';
$usuario_editando = null;
$accion = ''; // 'crear' o 'editar'

// Obtener usuarios con paginación
try {
    // Obtener total de usuarios
    if ($rol_filtro === 'todos') {
        $sql_total = "SELECT COUNT(*) as total FROM usuarios";
        $stmt_total = $conn->prepare($sql_total);
        $stmt_total->execute();
    } else {
        $sql_total = "SELECT COUNT(*) as total FROM usuarios WHERE rol = ?";
        $stmt_total = $conn->prepare($sql_total);
        $stmt_total->execute([$rol_filtro]);
    }
    
    $total_usuarios = $stmt_total->fetch()['total'];
    $total_paginas = ceil($total_usuarios / $usuarios_por_pagina);
    
    // Obtener usuarios de la página actual
    if ($rol_filtro === 'todos') {
        $sql = "SELECT * FROM usuarios ORDER BY creado_en DESC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $usuarios_por_pagina, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $sql = "SELECT * FROM usuarios WHERE rol = ? ORDER BY creado_en DESC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$rol_filtro, $usuarios_por_pagina, $offset]);
    }
    
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Error obteniendo usuarios: " . $e->getMessage());
    $usuarios = [];
    $total_usuarios = 0;
    $total_paginas = 1;
}


// Crear nuevo usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
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
        $accion = 'crear';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email no válido';
        $accion = 'crear';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
        $accion = 'crear';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
        $accion = 'crear';
    } else {
        try {
            // Verificar si el email ya existe
            $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error = 'El email ya está registrado';
                $accion = 'crear';
            } else {
                $sql = "INSERT INTO usuarios (email, password, nombre, apellido, rol, activo) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$email, $password, $nombre, $apellido, $rol, $activo]);
                
                $mensaje = 'Usuario creado exitosamente';
                // Recargar usuarios
                header('Location: usuarios.php?success=creado');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Error creando usuario: " . $e->getMessage());
            $error = 'Error al crear el usuario';
            $accion = 'crear';
        }
    }
}

// Actualizar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $id = $_POST['id'] ?? 0;
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
        $accion = 'editar';
        $usuario_editando = $_POST;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email no válido';
        $accion = 'editar';
        $usuario_editando = $_POST;
    } elseif ($cambiar_password && strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
        $accion = 'editar';
        $usuario_editando = $_POST;
    } elseif ($cambiar_password && $password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
        $accion = 'editar';
        $usuario_editando = $_POST;
    } else {
        try {
            // Verificar si el email ya existe en otro usuario
            $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
            $stmt->execute([$email, $id]);
            
            if ($stmt->fetch()) {
                $error = 'El email ya está registrado por otro usuario';
                $accion = 'editar';
                $usuario_editando = $_POST;
            } else {
                if ($cambiar_password) {
                    $sql = "UPDATE usuarios 
                            SET email = ?, password = ?, nombre = ?, apellido = ?, rol = ?, activo = ? 
                            WHERE id_usuario = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$email, $password, $nombre, $apellido, $rol, $activo, $id]);
                } else {
                    $sql = "UPDATE usuarios 
                            SET email = ?, nombre = ?, apellido = ?, rol = ?, activo = ? 
                            WHERE id_usuario = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$email, $nombre, $apellido, $rol, $activo, $id]);
                }
                
                $mensaje = 'Usuario actualizado exitosamente';
                // Recargar usuarios
                header('Location: usuarios.php?success=actualizado');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Error actualizando usuario: " . $e->getMessage());
            $error = 'Error al actualizar el usuario';
            $accion = 'editar';
            $usuario_editando = $_POST;
        }
    }
}

// Obtener usuario para editar (desde GET)
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $usuario_editando = $stmt->fetch();
        
        if (!$usuario_editando) {
            $error = 'Usuario no encontrado';
        } else {
            $accion = 'editar';
        }
    } catch (PDOException $e) {
        error_log("Error obteniendo usuario: " . $e->getMessage());
        $error = 'Error al cargar el usuario';
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    // No permitir autoeliminarse
    if ($id == $_SESSION['usuario_id']) {
        $error = 'No puedes eliminar tu propio usuario';
    } else {
        try {
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id]);
            
            $mensaje = 'Usuario eliminado exitosamente';
            // Recargar usuarios
            header('Location: usuarios.php?success=eliminado');
            exit();
        } catch (PDOException $e) {
            error_log("Error eliminando usuario: " . $e->getMessage());
            $error = 'Error al eliminar el usuario';
        }
    }
}

// Mostrar mensajes de éxito desde URL
if (isset($_GET['success'])) {
    $mensajes_exito = [
        'creado' => 'Usuario creado exitosamente',
        'actualizado' => 'Usuario actualizado exitosamente',
        'eliminado' => 'Usuario eliminado exitosamente'
    ];
    $mensaje = $mensajes_exito[$_GET['success']] ?? 'Operación exitosa';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Burmex Admin</title>
    <link rel="stylesheet" href="../styles/css/dashboard.css">
    <link rel="stylesheet" href="../styles/css/usuarios.css">
    <link rel="icon" type="image/x-icon" href="../img/img-inicio/logo-icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include_once 'includes/navbar.php'; ?>
    <?php include_once 'includes/sidebar.php'; ?>
    
    <div class="capa-lateral"></div>

    <main class="contenido-principal">
        <div class="contenedor">
            <!-- Encabezado -->
            <div class="encabezado-usuarios">
                <div class="titulo-seccion">
                    <h1>Gestión de Usuarios</h1>
                    <p class="subtitulo-seccion">Administra los usuarios del sistema</p>
                </div>
                <button class="btn-nuevo-usuario" id="btnNuevoUsuario">
                    <i class="fas fa-plus"></i> Nuevo Usuario
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

            <!-- Formulario flotante -->
            <div class="formulario-flotante <?php echo ($accion || $error) ? 'mostrar' : ''; ?>" id="formularioUsuario">
                <div class="formulario-contenido">
                    <div class="formulario-header">
                        <h2><?php echo ($accion == 'editar') ? 'Editar Usuario' : 'Nuevo Usuario'; ?></h2>
                        <button class="cerrar-formulario" id="cerrarFormulario">&times;</button>
                    </div>
                    
                    <form method="POST" action="">
                        <?php if ($accion == 'editar' && $usuario_editando): ?>
                            <input type="hidden" name="id" value="<?php echo $usuario_editando['id_usuario']; ?>">
                        <?php endif; ?>
                        
                        <div class="grupo-formulario">
                            <label for="email" class="etiqueta-formulario">Email *</label>
                            <input type="email" id="email" name="email" 
                                   class="input-formulario" 
                                   value="<?php 
                                   echo htmlspecialchars($usuario_editando ? $usuario_editando['email'] : ($_POST['email'] ?? ''));
                                   ?>" 
                                   required autofocus>
                        </div>

                        <div class="grupo-doble">
                            <div class="grupo-formulario">
                                <label for="nombre" class="etiqueta-formulario">Nombre *</label>
                                <input type="text" id="nombre" name="nombre" 
                                       class="input-formulario" 
                                       value="<?php 
                                       echo htmlspecialchars($usuario_editando ? $usuario_editando['nombre'] : ($_POST['nombre'] ?? ''));
                                       ?>" 
                                       required>
                            </div>
                            
                            <div class="grupo-formulario">
                                <label for="apellido" class="etiqueta-formulario">Apellido *</label>
                                <input type="text" id="apellido" name="apellido" 
                                       class="input-formulario" 
                                       value="<?php 
                                       echo htmlspecialchars($usuario_editando ? $usuario_editando['apellido'] : ($_POST['apellido'] ?? ''));
                                       ?>" 
                                       required>
                            </div>
                        </div>

                        <?php if ($accion == 'editar'): ?>
                        <!-- Solo para editar: checkbox para cambiar contraseña -->
                        <div class="grupo-formulario">
                            <label class="checkbox-formulario">
                                <input type="checkbox" name="cambiar_password" id="cambiar_password" value="1">
                                <span>Cambiar contraseña</span>
                            </label>
                        </div>
                        <?php endif; ?>

                        <div class="grupo-doble campos-password" style="<?php echo ($accion != 'editar') ? '' : 'display: none;'; ?>">
                            <div class="grupo-formulario">
                                <label for="password" class="etiqueta-formulario">
                                    <?php echo ($accion == 'editar') ? 'Nueva Contraseña' : 'Contraseña *'; ?>
                                </label>
                                <input type="password" id="password" name="password" 
                                       class="input-formulario" 
                                       placeholder="<?php echo ($accion == 'editar') ? 'Dejar en blanco para mantener' : 'Mínimo 6 caracteres'; ?>"
                                       <?php echo ($accion != 'editar') ? 'required minlength="6"' : ''; ?>>
                                <p class="texto-ayuda">Mínimo 6 caracteres</p>
                            </div>
                            
                            <div class="grupo-formulario">
                                <label for="confirm_password" class="etiqueta-formulario">
                                    <?php echo ($accion == 'editar') ? 'Confirmar Nueva Contraseña' : 'Confirmar Contraseña *'; ?>
                                </label>
                                <input type="password" id="confirm_password" name="confirm_password" 
                                       class="input-formulario" 
                                       placeholder="Repite la contraseña"
                                       <?php echo ($accion != 'editar') ? 'required minlength="6"' : ''; ?>>
                            </div>
                        </div>

                        <div class="grupo-formulario">
                            <label for="rol" class="etiqueta-formulario">Rol</label>
                            <select id="rol" name="rol" class="select-formulario">
                                <option value="empleado" <?php 
                                $rol_actual = $usuario_editando ? $usuario_editando['rol'] : ($_POST['rol'] ?? 'empleado');
                                echo ($rol_actual === 'empleado') ? 'selected' : '';
                                ?>>Empleado</option>
                                <option value="gerente" <?php echo ($rol_actual === 'gerente') ? 'selected' : ''; ?>>Gerente</option>
                                <option value="admin" <?php echo ($rol_actual === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                            </select>
                        </div>

                        <div class="grupo-formulario">
                            <label class="checkbox-formulario">
                                <input type="checkbox" name="activo" value="1" 
                                       <?php 
                                       $activo_actual = $usuario_editando ? $usuario_editando['activo'] : ($_POST['activo'] ?? 1);
                                       echo $activo_actual ? 'checked' : '';
                                       ?>>
                                <span>Usuario activo</span>
                            </label>
                            <p class="texto-ayuda">Los usuarios inactivos no pueden iniciar sesión</p>
                        </div>

                        <div class="formulario-botones">
                            <button type="button" class="btn-cancelar" id="btnCancelar">
                                Cancelar
                            </button>
                            <?php if ($accion == 'editar'): ?>
                                <button type="submit" name="actualizar" class="btn-actualizar">
                                    <i class="fas fa-save"></i> Actualizar Usuario
                                </button>
                            <?php else: ?>
                                <button type="submit" name="crear" class="btn-crear">
                                    <i class="fas fa-plus"></i> Crear Usuario
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="filtros-busqueda">
                <div class="buscador-usuarios">
                    <i class="fas fa-search"></i>
                    <input type="text" id="buscar-usuario" placeholder="Buscar por nombre o email...">
                </div>
                
                <div class="filtro-roles">
                    <select id="filtro-rol" onchange="filtrarPorRol()">
                        <option value="todos" <?php echo ($rol_filtro === 'todos') ? 'selected' : ''; ?>>Todos los roles</option>
                        <option value="admin" <?php echo ($rol_filtro === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="gerente" <?php echo ($rol_filtro === 'gerente') ? 'selected' : ''; ?>>Gerente</option>
                        <option value="empleado" <?php echo ($rol_filtro === 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                    </select>
                </div>
            </div>

            <!-- Información de resultados -->
            <div class="info-resultados">
                <p>
                    Mostrando <strong><?php echo count($usuarios); ?></strong> de <strong><?php echo $total_usuarios; ?></strong> usuarios
                    <?php if ($rol_filtro !== 'todos'): ?>
                        (filtrado por <?php echo $rol_filtro; ?>)
                    <?php endif; ?>
                </p>
            </div>

            <!-- Tabla de usuarios -->
            <div class="contenedor-tabla">
                <table class="tabla-usuarios">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Último Acceso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaUsuariosBody">
                        <?php if (count($usuarios) > 0): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td class="columna-usuario">
                                        <div class="info-usuario">
                                            <div class="avatar-usuario">
                                                <?php 
                                                $iniciales = strtoupper(substr($usuario['nombre'], 0, 1) . substr($usuario['apellido'], 0, 1));
                                                ?>
                                                <span><?php echo $iniciales; ?></span>
                                            </div>
                                            <div class="datos-usuario">
                                                <h4><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></h4>
                                                <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="columna-rol">
                                        <span class="badge-rol rol-<?php echo $usuario['rol']; ?>">
                                            <?php 
                                            $roles_nombres = [
                                                'admin' => 'Administrador',
                                                'gerente' => 'Gerente',
                                                'empleado' => 'Empleado'
                                            ];
                                            echo $roles_nombres[$usuario['rol']] ?? ucfirst($usuario['rol']);
                                            ?>
                                        </span>
                                    </td>
                                    <td class="columna-estado">
                                        <span class="badge-estado estado-<?php echo $usuario['activo'] ? 'activo' : 'inactivo'; ?>">
                                            <?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td class="columna-registro">
                                        <?php 
                                        $fecha_registro = $usuario['creado_en'];
                                        echo date('d/m/Y', strtotime($fecha_registro));
                                        ?>
                                        <br>
                                        <small class="texto-hora"><?php echo date('H:i', strtotime($fecha_registro)); ?></small>
                                    </td>
                                    <td class="columna-acceso">
                                        <?php 
                                        $fecha_acceso = $usuario['actualizado_en'] ?? $usuario['creado_en'];
                                        echo date('d/m/Y', strtotime($fecha_acceso));
                                        ?>
                                        <br>
                                        <small class="texto-hora"><?php echo date('H:i', strtotime($fecha_acceso)); ?></small>
                                    </td>
                                    <td class="columna-acciones">
                                        <div class="acciones-usuario">
                                            <button class="btn-accion btn-editar" 
                                                    onclick="editarUsuario(<?php echo $usuario['id_usuario']; ?>)"
                                                    title="Editar usuario">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-accion btn-eliminar" 
                                                    onclick="eliminarUsuario(<?php echo $usuario['id_usuario']; ?>, '<?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>')"
                                                    title="Eliminar usuario">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="sin-resultados">
                                    <i class="fas fa-users"></i>
                                    <p>No se encontraron usuarios</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

<!-- Paginación -->
<?php if ($total_paginas > 1): ?>
    <div class="paginacion">
        <div class="info-paginacion">
            Mostrando <?php echo (($pagina_actual - 1) * $usuarios_por_pagina) + 1; ?> - 
            <?php 
                $hasta = min($pagina_actual * $usuarios_por_pagina, $total_usuarios);
                echo $hasta;
            ?> 
            de <?php echo $total_usuarios; ?> usuarios
        </div>
        
        <div class="controles-paginacion">
            <?php if ($pagina_actual > 1): ?>
                <a href="usuarios.php?pagina=1<?php echo $rol_filtro !== 'todos' ? '&rol=' . $rol_filtro : ''; ?>" 
                   class="pagina-btn primera" title="Primera página">
                    « Primera
                </a>
                <a href="usuarios.php?pagina=<?php echo $pagina_actual - 1; ?><?php echo $rol_filtro !== 'todos' ? '&rol=' . $rol_filtro : ''; ?>" 
                   class="pagina-btn anterior" title="Página anterior">
                    ‹ Anterior
                </a>
            <?php else: ?>
                <span class="pagina-btn primera deshabilitada" title="Primera página">« Primera</span>
                <span class="pagina-btn anterior deshabilitada" title="Página anterior">‹ Anterior</span>
            <?php endif; ?>
            
            <?php
            $inicio = max(1, $pagina_actual - 2);
            $fin = min($total_paginas, $pagina_actual + 2);
            
            for ($i = $inicio; $i <= $fin; $i++):
                if ($i == 1 || $i == $total_paginas || ($i >= $pagina_actual - 1 && $i <= $pagina_actual + 1)):
            ?>
                <a href="usuarios.php?pagina=<?php echo $i; ?><?php echo $rol_filtro !== 'todos' ? '&rol=' . $rol_filtro : ''; ?>"
                   class="pagina-btn <?php echo $i == $pagina_actual ? 'activa' : ''; ?>"
                   title="Página <?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
            <?php 
                elseif ($i == $pagina_actual - 2 || $i == $pagina_actual + 2):
            ?>
                <span class="pagina-btn deshabilitada">...</span>
            <?php
                endif;
            endfor;
            ?>
            
            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="usuarios.php?pagina=<?php echo $pagina_actual + 1; ?><?php echo $rol_filtro !== 'todos' ? '&rol=' . $rol_filtro : ''; ?>" 
                   class="pagina-btn siguiente" title="Página siguiente">
                    Siguiente ›
                </a>
                <a href="usuarios.php?pagina=<?php echo $total_paginas; ?><?php echo $rol_filtro !== 'todos' ? '&rol=' . $rol_filtro : ''; ?>" 
                   class="pagina-btn ultima" title="Última página">
                    Última »
                </a>
            <?php else: ?>
                <span class="pagina-btn siguiente deshabilitada" title="Página siguiente">Siguiente ›</span>
                <span class="pagina-btn ultima deshabilitada" title="Última página">Última »</span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
        </div>
    </main>

    <!-- JavaScript -->
    <script src="./js/dashboard.js"></script>
    <script src="../js/usuarios.js"></script>
</body>
</html>