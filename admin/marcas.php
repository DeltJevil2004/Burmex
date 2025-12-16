<?php
// admin/marcas.php
session_start();

// Verificar si usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php?error=sesion');
    exit();
}

require_once '../includes/config.php';

$mensaje = '';
$error = '';
$marca_editando = null;

// Obtener todas las marcas
try {
    $sql = "SELECT * FROM marcas ORDER BY creado_en DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error obteniendo marcas: " . $e->getMessage());
    $marcas = [];
}

// Crear nueva marca
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $logo_url = trim($_POST['logo_url'] ?? '');
    $sitio_web = trim($_POST['sitio_web'] ?? '');
    
    if (empty($nombre)) {
        $error = 'El nombre de la marca es obligatorio';
    } else {
        try {
            // Verificar si la marca ya existe
            $stmt = $conn->prepare("SELECT id_marca FROM marcas WHERE nombre_marca = ?");
            $stmt->execute([$nombre]);
            
            if ($stmt->fetch()) {
                $error = 'La marca ya existe';
            } else {
                $sql = "INSERT INTO marcas (nombre_marca, descripcion, logo_url, sitio_web) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$nombre, $descripcion, $logo_url, $sitio_web]);
                
                $mensaje = 'Marca creada exitosamente';
                // Recargar marcas
                $stmt = $conn->prepare("SELECT * FROM marcas ORDER BY creado_en DESC");
                $stmt->execute();
                $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            error_log("Error creando marca: " . $e->getMessage());
            $error = 'Error al crear la marca';
        }
    }
}

// Actualizar marca
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $id = $_POST['id'] ?? 0;
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $logo_url = trim($_POST['logo_url'] ?? '');
    $sitio_web = trim($_POST['sitio_web'] ?? '');
    
    if (empty($nombre)) {
        $error = 'El nombre de la marca es obligatorio';
    } else {
        try {
            // Verificar si el nombre ya existe en otra marca
            $stmt = $conn->prepare("SELECT id_marca FROM marcas WHERE nombre_marca = ? AND id_marca != ?");
            $stmt->execute([$nombre, $id]);
            
            if ($stmt->fetch()) {
                $error = 'El nombre ya está siendo usado por otra marca';
            } else {
                $sql = "UPDATE marcas 
                        SET nombre_marca = ?, descripcion = ?, logo_url = ?, sitio_web = ? 
                        WHERE id_marca = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$nombre, $descripcion, $logo_url, $sitio_web, $id]);
                
                $mensaje = 'Marca actualizada exitosamente';
                // Recargar marcas
                $stmt = $conn->prepare("SELECT * FROM marcas ORDER BY creado_en DESC");
                $stmt->execute();
                $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            error_log("Error actualizando marca: " . $e->getMessage());
            $error = 'Error al actualizar la marca';
        }
    }
}

// Eliminar marca
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    try {
        // Verificar si hay productos asociados
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE marca_id = ?");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        
        if ($resultado['total'] > 0) {
            $error = 'No se puede eliminar la marca porque tiene productos asociados';
        } else {
            $stmt = $conn->prepare("DELETE FROM marcas WHERE id_marca = ?");
            $stmt->execute([$id]);
            
            $mensaje = 'Marca eliminada exitosamente';
            // Recargar marcas
            $stmt = $conn->prepare("SELECT * FROM marcas ORDER BY creado_en DESC");
            $stmt->execute();
            $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        error_log("Error eliminando marca: " . $e->getMessage());
        $error = 'Error al eliminar la marca';
    }
}

// Obtener marca para editar
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    try {
        $stmt = $conn->prepare("SELECT * FROM marcas WHERE id_marca = ?");
        $stmt->execute([$id]);
        $marca_editando = $stmt->fetch();
        
        if (!$marca_editando) {
            $error = 'Marca no encontrada';
        }
    } catch (PDOException $e) {
        error_log("Error obteniendo marca: " . $e->getMessage());
        $error = 'Error al cargar la marca';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcas - Burmex Admin</title>
    <link rel="stylesheet" href="../styles/css/dashboard.css">
    <link rel="stylesheet" href="../styles/css/marcas.css">
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
            <div class="encabezado-marcas">
                <div class="titulo-seccion">
                    <h1>Marcas</h1>
                    <p class="subtitulo-seccion">Administra las marcas de productos</p>
                </div>
                <button class="btn-nueva-marca" id="btnNuevaMarca">
                    <i class="fas fa-plus"></i> Nueva Marca
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

            <!-- Buscador -->
            <div class="buscador-marcas">
                <div class="buscador-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="buscar-marca" placeholder="Buscar marcas...">
                </div>
            </div>

            <!-- Formulario flotante (se muestra al crear/editar) -->
            <div class="formulario-flotante <?php echo ($marca_editando || isset($_POST['crear'])) ? 'mostrar' : ''; ?>" id="formularioMarca">
                <div class="formulario-contenido">
                    <div class="formulario-header">
                        <h2><?php echo $marca_editando ? 'Editar Marca' : 'Nueva Marca'; ?></h2>
                        <button class="cerrar-formulario" id="cerrarFormulario">&times;</button>
                    </div>
                    
                    <form method="POST" action="">
                        <?php if ($marca_editando): ?>
                            <input type="hidden" name="id" value="<?php echo $marca_editando['id_marca']; ?>">
                        <?php endif; ?>
                        
                        <div class="grupo-formulario">
                            <label for="nombre" class="etiqueta-formulario">Nombre de la marca *</label>
                            <input type="text" id="nombre" name="nombre" 
                                   class="input-formulario" 
                                   value="<?php echo htmlspecialchars($marca_editando ? $marca_editando['nombre_marca'] : ($_POST['nombre'] ?? '')); ?>" 
                                   required autofocus>
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="descripcion" class="etiqueta-formulario">Descripción</label>
                            <textarea id="descripcion" name="descripcion" 
                                      class="textarea-formulario" 
                                      rows="3"><?php echo htmlspecialchars($marca_editando ? $marca_editando['descripcion'] : ($_POST['descripcion'] ?? '')); ?></textarea>
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="logo_url" class="etiqueta-formulario">URL del logo</label>
                            <input type="text" id="logo_url" name="logo_url" 
                                   class="input-formulario" 
                                   value="<?php echo htmlspecialchars($marca_editando ? $marca_editando['logo_url'] : ($_POST['logo_url'] ?? '')); ?>" 
                                   placeholder="https://ejemplo.com/logo.png">
                        </div>
                        
                        <div class="grupo-formulario">
                            <label for="sitio_web" class="etiqueta-formulario">Sitio web</label>
                            <input type="url" id="sitio_web" name="sitio_web" 
                                   class="input-formulario" 
                                   value="<?php echo htmlspecialchars($marca_editando ? $marca_editando['sitio_web'] : ($_POST['sitio_web'] ?? '')); ?>" 
                                   placeholder="https://ejemplo.com">
                        </div>
                        
                        <div class="formulario-botones">
                            <button type="button" class="btn-cancelar" id="btnCancelar">
                                Cancelar
                            </button>
                            <?php if ($marca_editando): ?>
                                <button type="submit" name="actualizar" class="btn-actualizar">
                                    <i class="fas fa-save"></i> Actualizar Marca
                                </button>
                            <?php else: ?>
                                <button type="submit" name="crear" class="btn-crear">
                                    <i class="fas fa-plus"></i> Crear Marca
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Grid de marcas -->
            <div class="grid-marcas" id="gridMarcas">
                <?php if (count($marcas) > 0): ?>
                    <?php foreach ($marcas as $marca): ?>
                        <div class="tarjeta-marca" data-nombre="<?php echo strtolower($marca['nombre_marca']); ?>">
                            <div class="marca-header">
                                <div class="marca-imagen">
                                    <?php if (!empty($marca['logo_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($marca['logo_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($marca['nombre_marca']); ?>"
                                             onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><rect width=%22100%22 height=%22100%22 fill=%22%23f0f0f0%22/><text x=%2250%22 y=%2255%22 font-family=%22Arial%22 font-size=%2224%22 text-anchor=%22middle%22 fill=%22%23999%22>' + '<?php echo substr($marca['nombre_marca'], 0, 2); ?>' + '</text></svg>'">
                                    <?php else: ?>
                                        <div class="marca-sin-imagen">
                                            <?php echo strtoupper(substr($marca['nombre_marca'], 0, 2)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="marca-acciones">
                                    <button class="btn-accion btn-editar" 
                                            onclick="window.location.href='marcas.php?editar=<?php echo $marca['id_marca']; ?>'"
                                            title="Editar marca">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-accion btn-eliminar" 
                                            onclick="confirmarEliminar(<?php echo $marca['id_marca']; ?>, '<?php echo htmlspecialchars($marca['nombre_marca']); ?>')"
                                            title="Eliminar marca">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="marca-contenido">
                                <h3 class="marca-nombre"><?php echo htmlspecialchars($marca['nombre_marca']); ?></h3>
                                
                                <?php if (!empty($marca['descripcion'])): ?>
                                    <p class="marca-descripcion">
                                        <?php 
                                        $descripcion = htmlspecialchars($marca['descripcion']);
                                        if (strlen($descripcion) > 100) {
                                            echo substr($descripcion, 0, 100) . '...';
                                        } else {
                                            echo $descripcion;
                                        }
                                        ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($marca['sitio_web'])): ?>
                                    <a href="<?php echo htmlspecialchars($marca['sitio_web']); ?>" 
                                       target="_blank" 
                                       class="marca-sitio-web">
                                        <i class="fas fa-external-link-alt"></i> Sitio web
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="marca-footer">
                                <span class="marca-fecha">
                                    <i class="far fa-calendar"></i>
                                    <?php echo date('d/m/Y', strtotime($marca['creado_en'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="sin-marcas">
                        <i class="fas fa-tags"></i>
                        <h3>No hay marcas registradas</h3>
                        <p>Crea tu primera marca usando el botón "Nueva Marca"</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <!-- JavaScript -->
    <script src="./js/dashboard.js"></script>
    <script src="/js/marcas.js"></script>
</body>
</html>