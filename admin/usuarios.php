<?php
session_start();

// Verificar si usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../public/login.php?error=sesion');
    exit();
}

// Incluir conexión a BD
require_once '../includes/config.php';

// Obtener usuarios
try {
    // Filtro por rol
    $rol_filtro = $_GET['rol'] ?? 'todos';
    
    if ($rol_filtro === 'todos') {
        $sql = "SELECT * FROM usuarios ORDER BY creado_en DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "SELECT * FROM usuarios WHERE rol = ? ORDER BY creado_en DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$rol_filtro]);
    }
    
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Error obteniendo usuarios: " . $e->getMessage());
    $usuarios = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Burmex Admin</title>
    <link rel="stylesheet" href="../../styles/css/dashboard.css">
    <link rel="stylesheet" href="../../styles/css/usuarios.css">
    <link rel="icon" type="image/x-icon" href="../../img/img-inicio/logo-icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include_once 'includes/navbar.php'; ?>
    <?php include_once 'includes/sidebar.php'; ?>
    
    <!-- Overlay para móvil -->
    <div class="capa-lateral"></div>

    <!-- Contenido principal -->
    <main class="contenido-principal">
        <div class="contenedor">
            <!-- Encabezado -->
            <div class="encabezado-usuarios">
                <div class="titulo-seccion">
                    <h1>Gestión de Usuarios</h1>
                    <p class="subtitulo-seccion">Administra los usuarios del sistema</p>
                </div>
                <button class="btn-nuevo-usuario" onclick="window.location.href='/admin/usuarios/crear.php'">
                    <i class="fas fa-plus"></i> Nuevo Usuario
                </button>
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

            <!-- Tabla de usuarios -->
<div class="contenedor-tabla">
    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Fecha Registro</th> <!-- NUEVO -->
                <th>Último Acceso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
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
                        <td class="columna-registro"> <!-- NUEVO -->
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
                                <button class="btn-accion btn-editar" title="Editar usuario" onclick="editarUsuario(<?php echo $usuario['id_usuario']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-accion btn-eliminar" title="Eliminar usuario" onclick="eliminarUsuario(<?php echo $usuario['id_usuario']; ?>, '<?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="sin-resultados"> <!-- Cambiado de 5 a 6 -->
                        <i class="fas fa-users"></i>
                        <p>No se encontraron usuarios</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
    </main>


    <!-- JavaScript -->
    <script src="../js/dashboard.js"></script>
    <script src="../js/usuarios.js"></script>
</body>
</html>