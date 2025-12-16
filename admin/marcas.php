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

// Configuración de paginación
$marcas_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

$offset = ($pagina_actual - 1) * $marcas_por_pagina;

// Variables para búsqueda
$busqueda = trim($_GET['busqueda'] ?? '');

// Procesar búsqueda y obtener marcas con paginación
try {
    // Construir consulta base
    $sql_where = "WHERE 1=1";
    $params = [];
    
    if (!empty($busqueda)) {
        $sql_where .= " AND (nombre_marca LIKE ? OR descripcion LIKE ?)";
        $params[] = "%$busqueda%";
        $params[] = "%$busqueda%";
    }
    
    // Obtener total de marcas
    $sql_total = "SELECT COUNT(*) as total FROM marcas $sql_where";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->execute($params);
    $total_marcas = $stmt_total->fetch()['total'];
    $total_paginas = $total_marcas > 0 ? ceil($total_marcas / $marcas_por_pagina) : 1;
    
    // Obtener marcas de la página actual
    $sql = "SELECT * FROM marcas 
            $sql_where
            ORDER BY creado_en DESC
            LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    
    // Agregar parámetros de límite y offset
    $params_limit = $params;
    $params_limit[] = $marcas_por_pagina;
    $params_limit[] = $offset;
    
    $stmt->execute($params_limit);
    $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Error obteniendo marcas: " . $e->getMessage());
    $marcas = [];
    $total_marcas = 0;
    $total_paginas = 1;
}

// Crear nueva marca
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $logo_url = trim($_POST['logo_url'] ?? '');
    $sitio_web = trim($_POST['sitio_web'] ?? '');
    
    // Manejo de imagen subida
    $imagen_subida = false;
    if (isset($_FILES['logo_archivo']) && $_FILES['logo_archivo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['logo_archivo'];
        
        // Validar que sea una imagen
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
            // Crear directorio si no existe
            $upload_dir = '../img/img-marcas/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generar nombre único para el archivo
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $nombre_archivo = 'marca_' . time() . '_' . uniqid() . '.' . $extension;
            $upload_path = $upload_dir . $nombre_archivo;
            
            // Mover el archivo
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $logo_url = 'img/img-marcas/' . $nombre_archivo;
                $imagen_subida = true;
            }
        }
    }
    
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
                if ($imagen_subida) {
                    $mensaje .= ' con imagen subida';
                }
                // Redirigir a la primera página para ver la nueva marca
                header("Location: marcas.php?pagina=1&mensaje=" . urlencode($mensaje));
                exit();
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
    
    // Manejo de imagen subida
    $imagen_subida = false;
    if (isset($_FILES['logo_archivo']) && $_FILES['logo_archivo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['logo_archivo'];
        
        // Validar que sea una imagen
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
            // Crear directorio si no existe
            $upload_dir = '../img/img-marcas/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generar nombre único para el archivo
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $nombre_archivo = 'marca_' . time() . '_' . uniqid() . '.' . $extension;
            $upload_path = $upload_dir . $nombre_archivo;
            
            // Mover el archivo
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $logo_url = 'img/img-marcas/' . $nombre_archivo;
                $imagen_subida = true;
            }
        }
    } else {
        // Si no se subió archivo y no hay URL, mantener la existente
        if (empty($logo_url) && $id > 0) {
            $stmt = $conn->prepare("SELECT logo_url FROM marcas WHERE id_marca = ?");
            $stmt->execute([$id]);
            $marca_actual = $stmt->fetch();
            if ($marca_actual && !empty($marca_actual['logo_url'])) {
                $logo_url = $marca_actual['logo_url'];
            }
        }
    }
    
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
                if ($imagen_subida) {
                    $mensaje .= ' con nueva imagen';
                }
                // Redirigir manteniendo la página actual
                header("Location: marcas.php?pagina=$pagina_actual&mensaje=" . urlencode($mensaje));
                exit();
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
            // Obtener logo antes de eliminar para borrar el archivo si existe
            $stmt = $conn->prepare("SELECT logo_url FROM marcas WHERE id_marca = ?");
            $stmt->execute([$id]);
            $marca = $stmt->fetch();
            
            $stmt = $conn->prepare("DELETE FROM marcas WHERE id_marca = ?");
            $stmt->execute([$id]);
            
            // Eliminar archivo de imagen si existe y es local
            if ($marca && !empty($marca['logo_url']) && strpos($marca['logo_url'], 'img/img-marcas/') === 0) {
                $file_path = '../' . $marca['logo_url'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            $mensaje = 'Marca eliminada exitosamente';
            // Recalcular página después de eliminar
            $total_despues = max(0, $total_marcas - 1);
            $pagina_despues = ($pagina_actual > ceil($total_despues / $marcas_por_pagina)) 
                ? max(1, ceil($total_despues / $marcas_por_pagina)) 
                : $pagina_actual;
            
            header("Location: marcas.php?pagina=$pagina_despues&mensaje=" . urlencode($mensaje));
            exit();
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

// Mostrar mensajes de URL
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
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
                    <form method="GET" class="form-busqueda-inline" id="formBusquedaMarcas">
                        <input type="hidden" name="pagina" value="1">
                        <input type="text" 
                               name="busqueda" 
                               id="buscar-marca" 
                               placeholder="Buscar marcas..."
                               value="<?php echo htmlspecialchars($busqueda); ?>">
                    </form>
                </div>
            </div>

            <!-- Formulario flotante (se muestra al crear/editar) -->
            <div class="formulario-flotante <?php echo ($marca_editando || isset($_GET['crear'])) ? 'mostrar' : ''; ?>" id="formularioMarca">
                <div class="formulario-contenido">
                    <div class="formulario-header">
                        <h2><?php echo $marca_editando ? 'Editar Marca' : 'Nueva Marca'; ?></h2>
                        <button class="cerrar-formulario" id="cerrarFormulario">&times;</button>
                    </div>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
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
                            <label for="logo_archivo" class="etiqueta-formulario">Logo de la marca</label>
                            
                            <input type="file" 
                                   id="logo_archivo" 
                                   name="logo_archivo" 
                                   accept="image/*"
                                   class="input-archivo"
                                   onchange="mostrarVistaPrevia(event)">
                            
                            <input type="text" 
                                   id="logo_url" 
                                   name="logo_url" 
                                   placeholder="O ingresa URL del logo..."
                                   value="<?php echo htmlspecialchars($marca_editando ? $marca_editando['logo_url'] : ($_POST['logo_url'] ?? '')); ?>"
                                   class="input-url"
                                   oninput="mostrarVistaPreviaURL()">
                            
                            <div class="vista-previa-contenedor" id="vistaPreviaContenedor" style="<?php echo ($marca_editando && !empty($marca_editando['logo_url'])) ? '' : 'display: none;'; ?>">
                                <div class="vista-previa-titulo">Vista previa:</div>
                                <div class="vista-previa-imagen">
                                    <?php if ($marca_editando && !empty($marca_editando['logo_url'])): ?>
                                        <img src="../<?php echo htmlspecialchars($marca_editando['logo_url']); ?>" 
                                             alt="Vista previa"
                                             id="imagenVistaPrevia"
                                             onerror="ocultarVistaPrevia()">
                                    <?php else: ?>
                                        <img src="" alt="Vista previa" id="imagenVistaPrevia" style="display: none;">
                                    <?php endif; ?>
                                    <div class="sin-imagen" id="sinImagenTexto">No hay imagen</div>
                                </div>
                                <button type="button" class="btn-quitar-imagen" onclick="quitarImagen()">×</button>
                            </div>
                            
                            <small class="texto-ayuda">Sube una imagen (JPG, PNG, GIF, SVG, WEBP - Max: 5MB) o ingresa una URL</small>
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
                                        <img src="../<?php echo htmlspecialchars($marca['logo_url']); ?>" 
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
                                            onclick="window.location.href='marcas.php?editar=<?php echo $marca['id_marca']; ?>&pagina=<?php echo $pagina_actual; ?>'"
                                            title="Editar marca">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-accion btn-eliminar" 
                                            onclick="confirmarEliminar(<?php echo $marca['id_marca']; ?>, '<?php echo htmlspecialchars($marca['nombre_marca']); ?>', <?php echo $pagina_actual; ?>)"
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
                        <?php if ($busqueda): ?>
                            <a href="marcas.php" class="btn-limpiar-filtros">
                                <i class="fas fa-times"></i> Limpiar búsqueda
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
                <div class="paginacion">
                    <div class="info-paginacion">
                        Mostrando <?php echo (($pagina_actual - 1) * $marcas_por_pagina) + 1; ?> - 
                        <?php 
                            $hasta = min($pagina_actual * $marcas_por_pagina, $total_marcas);
                            echo $hasta;
                        ?> 
                        de <?php echo $total_marcas; ?> marcas
                    </div>
                    
                    <div class="controles-paginacion">
                        <?php if ($pagina_actual > 1): ?>
                            <a href="?pagina=1&busqueda=<?php echo urlencode($busqueda); ?>" 
                               class="pagina-btn primera" title="Primera página">
                                « Primera
                            </a>
                            <a href="?pagina=<?php echo $pagina_actual - 1; ?>&busqueda=<?php echo urlencode($busqueda); ?>" 
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
                            <a href="?pagina=<?php echo $i; ?>&busqueda=<?php echo urlencode($busqueda); ?>"
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
                            <a href="?pagina=<?php echo $pagina_actual + 1; ?>&busqueda=<?php echo urlencode($busqueda); ?>" 
                               class="pagina-btn siguiente" title="Página siguiente">
                                Siguiente ›
                            </a>
                            <a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo urlencode($busqueda); ?>" 
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
    <script src="../js/marcas.js"></script>



</body>
</html>