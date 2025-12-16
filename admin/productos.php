<?php
session_start();

// Verificar si usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php?error=sesion');
    exit();
}

// Verificar rol de administrador
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../admin/dashboard.php?error=permisos');
    exit();
}

// Incluir conexión a BD
require_once '../includes/config.php';

// Configuración de paginación
$productos_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

$offset = ($pagina_actual - 1) * $productos_por_pagina;

// Filtros
$categoria_filtro = $_GET['categoria'] ?? 'todas';
$marca_filtro = $_GET['marca'] ?? 'todas';
$busqueda = trim($_GET['busqueda'] ?? '');

// Variables para formulario
$mensaje = '';
$error = '';
$producto_editando = null;
$accion = ''; // 'crear' o 'editar'
$producto_id_eliminar = null;

// Obtener categorías para filtro
try {
    $stmt_categorias = $conn->query("SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria");
    $categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error obteniendo categorías: " . $e->getMessage());
    $categorias = [];
}

// Obtener marcas para filtro
try {
    $stmt_marcas = $conn->query("SELECT id_marca, nombre_marca FROM marcas ORDER BY nombre_marca");
    $marcas = $stmt_marcas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error obteniendo marcas: " . $e->getMessage());
    $marcas = [];
}

// Procesar búsqueda y filtros
try {
    // Construir consulta base
    $sql_where = "WHERE p.activo = 1";
    $params = [];
    
    if (!empty($busqueda)) {
        $sql_where .= " AND (p.nombre_producto LIKE ? OR p.descripcion LIKE ?)";
        $params[] = "%$busqueda%";
        $params[] = "%$busqueda%";
    }
    
    if ($categoria_filtro !== 'todas' && is_numeric($categoria_filtro)) {
        $sql_where .= " AND p.categoria_id = ?";
        $params[] = $categoria_filtro;
    }
    
    if ($marca_filtro !== 'todas' && is_numeric($marca_filtro)) {
        $sql_where .= " AND p.marca_id = ?";
        $params[] = $marca_filtro;
    }
    
    // Obtener total de productos
    $sql_total = "SELECT COUNT(*) as total FROM productos p $sql_where";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->execute($params);
    $total_productos = $stmt_total->fetch()['total'];
    $total_paginas = $total_productos > 0 ? ceil($total_productos / $productos_por_pagina) : 1;
    
    // Obtener productos de la página actual con información de categoría y marca
    $sql = "SELECT 
                p.*,
                c.nombre_categoria,
                m.nombre_marca
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id_categoria
            LEFT JOIN marcas m ON p.marca_id = m.id_marca
            $sql_where
            ORDER BY p.creado_en DESC
            LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    
    // Agregar parámetros de límite y offset
    $params_limit = $params;
    $params_limit[] = $productos_por_pagina;
    $params_limit[] = $offset;
    
    $stmt->execute($params_limit);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Error obteniendo productos: " . $e->getMessage());
    $productos = [];
    $total_productos = 0;
    $total_paginas = 1;
}

// Procesar acciones (crear/editar/eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'crear':
            case 'editar':
                try {
                    // Validar y sanitizar datos
                    $nombre = trim($_POST['nombre'] ?? '');
                    $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
                    $descripcion = trim($_POST['descripcion'] ?? '');
                    $categoria_id = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 0;
                    $marca_id = isset($_POST['marca_id']) ? (int)$_POST['marca_id'] : 0;
                    $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 0;
                    $tiene_descuento = isset($_POST['tiene_descuento']) ? 1 : 0;
                    $porcentaje_descuento = isset($_POST['porcentaje_descuento']) ? (int)$_POST['porcentaje_descuento'] : 0;
                    $activo = isset($_POST['activo']) ? 1 : 0;
                    $destacado = isset($_POST['destacado']) ? 1 : 0;
                    
                    // Manejo de imagen
                    $imagen_url = trim($_POST['imagen_url'] ?? '');
                    $imagen_subida = false;
                    
                    // Verificar si se subió un archivo
                    if (isset($_FILES['imagen_archivo']) && $_FILES['imagen_archivo']['error'] === UPLOAD_ERR_OK) {
                        $file = $_FILES['imagen_archivo'];
                        
                        // Validar que sea una imagen
                        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        $max_size = 2 * 1024 * 1024; // 2MB
                        
                        if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
                            // Crear directorio si no existe
                            $upload_dir = '../img-productos/';
                            if (!file_exists($upload_dir)) {
                                mkdir($upload_dir, 0777, true);
                            }
                            
                            // Generar nombre único para el archivo
                            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $nombre_archivo = 'producto_' . time() . '_' . uniqid() . '.' . $extension;
                            $upload_path = $upload_dir . $nombre_archivo;
                            
                            // Mover el archivo
                            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                                $imagen_url = 'img-productos/' . $nombre_archivo;
                                $imagen_subida = true;
                            }
                        }
                    }
                    
                    // Si no se subió archivo y no hay URL, mantener la existente (en caso de edición)
                    if (empty($imagen_url) && $_POST['accion'] === 'editar' && isset($_POST['producto_id'])) {
                        $producto_id_temp = (int)$_POST['producto_id'];
                        $stmt_temp = $conn->prepare("SELECT imagen_url FROM productos WHERE id_producto = ?");
                        $stmt_temp->execute([$producto_id_temp]);
                        $producto_temp = $stmt_temp->fetch();
                        if ($producto_temp && !empty($producto_temp['imagen_url'])) {
                            $imagen_url = $producto_temp['imagen_url'];
                        }
                    }
                    
                    // Validaciones básicas
                    if (empty($nombre)) {
                        $error = "El nombre del producto es requerido";
                        break;
                    }
                    
                    if ($precio <= 0) {
                        $error = "El precio debe ser mayor a 0";
                        break;
                    }
                    
                    if ($categoria_id <= 0) {
                        $error = "Debe seleccionar una categoría";
                        break;
                    }
                    
                    if ($marca_id <= 0) {
                        $error = "Debe seleccionar una marca";
                        break;
                    }
                    
                    // Calcular precio con descuento si aplica
                    $precio_descuento = null;
                    if ($tiene_descuento && $porcentaje_descuento > 0) {
                        $precio_descuento = $precio - ($precio * $porcentaje_descuento / 100);
                    }
                    
                    if ($_POST['accion'] === 'crear') {
                        // Crear nuevo producto
                        $sql_insert = "INSERT INTO productos (
                            nombre_producto, precio, descripcion, categoria_id, 
                            marca_id, stock, imagen_url, tiene_descuento, 
                            porcentaje_descuento, precio_descuento, activo, destacado
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        
                        $stmt = $conn->prepare($sql_insert);
                        $stmt->execute([
                            $nombre, $precio, $descripcion, $categoria_id,
                            $marca_id, $stock, $imagen_url, $tiene_descuento,
                            $porcentaje_descuento, $precio_descuento, $activo, $destacado
                        ]);
                        
                        $mensaje = "Producto creado exitosamente.";
                    } else {
                        // Editar producto existente
                        $producto_id = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
                        
                        if ($producto_id <= 0) {
                            $error = "ID de producto inválido";
                            break;
                        }
                        
                        $sql_update = "UPDATE productos SET
                            nombre_producto = ?,
                            precio = ?,
                            descripcion = ?,
                            categoria_id = ?,
                            marca_id = ?,
                            stock = ?,
                            imagen_url = ?,
                            tiene_descuento = ?,
                            porcentaje_descuento = ?,
                            precio_descuento = ?,
                            activo = ?,
                            destacado = ?,
                            actualizado_en = CURRENT_TIMESTAMP
                            WHERE id_producto = ?";
                        
                        $stmt = $conn->prepare($sql_update);
                        $stmt->execute([
                            $nombre, $precio, $descripcion, $categoria_id,
                            $marca_id, $stock, $imagen_url, $tiene_descuento,
                            $porcentaje_descuento, $precio_descuento, $activo, $destacado,
                            $producto_id
                        ]);
                        
                        $mensaje = "Producto actualizado exitosamente.";
                    }
                    
                    // Recargar página
                    header("Location: productos.php?pagina=$pagina_actual&mensaje=" . urlencode($mensaje));
                    exit();
                    
                } catch (PDOException $e) {
                    error_log("Error guardando producto: " . $e->getMessage());
                    $error = "Error al guardar el producto: " . $e->getMessage();
                }
                break;
                
            case 'eliminar':
                if (isset($_POST['producto_id'])) {
                    $producto_id_eliminar = (int)$_POST['producto_id'];
                }
                break;
                
            case 'confirmar_eliminar':
                try {
                    $producto_id = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
                    
                    if ($producto_id <= 0) {
                        $error = "ID de producto inválido";
                        break;
                    }
                    
                    // Verificar si hay órdenes asociadas
                    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM orden_detalles WHERE producto_id = ?");
                    $stmt_check->execute([$producto_id]);
                    $tiene_ordenes = $stmt_check->fetchColumn() > 0;
                    
                    if ($tiene_ordenes) {
                        // Solo marcar como inactivo si tiene órdenes
                        $sql_update = "UPDATE productos SET activo = 0 WHERE id_producto = ?";
                        $stmt = $conn->prepare($sql_update);
                        $stmt->execute([$producto_id]);
                        $mensaje = "Producto desactivado (tiene órdenes asociadas).";
                    } else {
                        // Eliminar completamente
                        $sql_delete = "DELETE FROM productos WHERE id_producto = ?";
                        $stmt = $conn->prepare($sql_delete);
                        $stmt->execute([$producto_id]);
                        $mensaje = "Producto eliminado exitosamente.";
                    }
                    
                    header("Location: productos.php?pagina=$pagina_actual&mensaje=" . urlencode($mensaje));
                    exit();
                    
                } catch (PDOException $e) {
                    error_log("Error eliminando producto: " . $e->getMessage());
                    $error = "Error al eliminar el producto: " . $e->getMessage();
                }
                break;
        }
    }
}

// Obtener producto para editar
if (isset($_GET['editar']) && is_numeric($_GET['editar'])) {
    try {
        $producto_id = (int)$_GET['editar'];
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
        $stmt->execute([$producto_id]);
        $producto_editando = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($producto_editando) {
            $accion = 'editar';
        }
    } catch (PDOException $e) {
        error_log("Error obteniendo producto para editar: " . $e->getMessage());
    }
}

// Mostrar mensajes
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}

// Determinar si mostrar modal de creación
if (isset($_GET['crear']) && $_GET['crear'] == '1') {
    $accion = 'crear';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Burmex Admin</title>
    <link rel="stylesheet" href="../styles/css/dashboard.css">
    <link rel="stylesheet" href="../styles/css/productos-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../img/img-inicio/logo-icon.ico">
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
            <div class="encabezado-productos">
                <div class="titulo-seccion">
                    <h1>Productos</h1>
                    <p>Gestión de productos del catálogo</p>
                </div>
                <a href="?crear=1&pagina=<?php echo $pagina_actual; ?>" class="btn-nuevo-producto" id="btnNuevoProducto" style="background-color: #009BDB;">
                    <span>+</span> Nuevo Producto
                </a>
            </div>
            
            <!-- Mensajes -->
            <?php if ($mensaje): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Filtros y búsqueda -->
            <div class="filtros-busqueda">
                <div class="buscador-usuarios">
                    <i class="fas fa-search"></i>
                    <form method="GET" class="form-busqueda-inline" id="formBusqueda">
                        <input type="hidden" name="pagina" value="1">
                        <input type="text" 
                               name="busqueda" 
                               id="inputBusqueda"
                               placeholder="Buscar por nombre o descripción..." 
                               value="<?php echo htmlspecialchars($busqueda); ?>"
                               class="input-busqueda-inline">
                    </form>
                </div>
                
                <div class="filtros-select">
                    <select name="categoria" class="select-filtro" id="filtro-categoria" onchange="filtrarProductos()">
                        <option value="todas" <?php echo $categoria_filtro === 'todas' ? 'selected' : ''; ?>>
                            Todas las categorías
                        </option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>" 
                                <?php echo $categoria_filtro == $categoria['id_categoria'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <select name="marca" class="select-filtro" id="filtro-marca" onchange="filtrarProductos()">
                        <option value="todas" <?php echo $marca_filtro === 'todas' ? 'selected' : ''; ?>>
                            Todas las marcas
                        </option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>" 
                                <?php echo $marca_filtro == $marca['id_marca'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($marca['nombre_marca']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Grid de productos -->
            <div class="grid-productos">
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <div class="tarjeta-producto">
                            <div class="imagen-producto">
                                <?php if (!empty($producto['imagen_url'])): ?>
                                    <img src="../<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                         onerror="this.src='../img/placeholder-producto.png'">
                                <?php else: ?>
                                    <div class="imagen-placeholder">
                                        <span><i class="fas fa-desktop"></i></span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Badge destacado -->
                                <?php if ($producto['destacado']): ?>
                                    <span class="badge-destacado"><i class="fas fa-star"></i> Destacado</span>
                                <?php endif; ?>
                                
                                <!-- Badge descuento -->
                                <?php if ($producto['tiene_descuento'] && $producto['porcentaje_descuento'] > 0): ?>
                                    <span class="badge-descuento">-<?php echo $producto['porcentaje_descuento']; ?>%</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="info-producto">
                                <h3 class="nombre-producto">
                                    <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                                </h3>
                                
                                <div class="categoria-marca">
                                    <span class="categoria"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($producto['nombre_categoria'] ?? 'Sin categoría'); ?></span>
                                    <span class="separador">•</span>
                                    <span class="marca"><i class="fas fa-trademark"></i> <?php echo htmlspecialchars($producto['nombre_marca'] ?? 'Sin marca'); ?></span>
                                </div>
                                
                                <p class="descripcion-producto">
                                    <?php 
                                    $descripcion = $producto['descripcion'] ?? '';
                                    if (strlen($descripcion) > 100) {
                                        echo htmlspecialchars(substr($descripcion, 0, 100)) . '...';
                                    } else {
                                        echo htmlspecialchars($descripcion);
                                    }
                                    ?>
                                </p>
                                
                                <div class="precio-stock">
                                    <div class="precios">
                                        <?php if ($producto['tiene_descuento'] && $producto['precio_descuento']): ?>
                                            <span class="precio-original">$<?php echo number_format($producto['precio'], 2); ?></span>
                                            <span class="precio-descuento">$<?php echo number_format($producto['precio_descuento'], 2); ?></span>
                                        <?php else: ?>
                                            <span class="precio-normal">$<?php echo number_format($producto['precio'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="stock-estado">
                                        <span class="stock"><i class="fas fa-boxes"></i> <?php echo $producto['stock']; ?> unidades</span>
                                        <span class="estado <?php echo $producto['activo'] ? 'activo' : 'inactivo'; ?>">
                                            <?php if ($producto['activo']): ?>
                                                <i class="fas fa-check-circle"></i> Disponible
                                            <?php else: ?>
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="acciones-producto">
                                    <a href="?editar=<?php echo $producto['id_producto']; ?>&pagina=<?php echo $pagina_actual; ?>" 
                                       class="btn-editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button class="btn-eliminar" 
                                            data-id="<?php echo $producto['id_producto']; ?>"
                                            data-nombre="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="sin-productos">
                        <i class="fas fa-box-open fa-3x" style="color: #6c757d; margin-bottom: 20px;"></i>
                        <p>No se encontraron productos.</p>
                        <?php if ($busqueda || $categoria_filtro !== 'todas' || $marca_filtro !== 'todas'): ?>
                            <a href="productos.php" class="btn-limpiar-filtros">
                                <i class="fas fa-times"></i> Limpiar filtros
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
<!-- Paginación -->
<?php if ($total_paginas > 1): ?>
    <div class="paginacion">
        <div class="info-paginacion">
            Mostrando <?php echo (($pagina_actual - 1) * $productos_por_pagina) + 1; ?> - 
            <?php 
                $hasta = min($pagina_actual * $productos_por_pagina, $total_productos);
                echo $hasta;
            ?> 
            de <?php echo $total_productos; ?> productos
        </div>
        
        <div class="controles-paginacion">
            <?php if ($pagina_actual > 1): ?>
                <a href="?pagina=1&busqueda=<?php echo urlencode($busqueda); ?>&categoria=<?php echo $categoria_filtro; ?>&marca=<?php echo $marca_filtro; ?>" 
                   class="pagina-btn primera" title="Primera página">
                    « Primera
                </a>
                <a href="?pagina=<?php echo $pagina_actual - 1; ?>&busqueda=<?php echo urlencode($busqueda); ?>&categoria=<?php echo $categoria_filtro; ?>&marca=<?php echo $marca_filtro; ?>" 
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
                <a href="?pagina=<?php echo $i; ?>&busqueda=<?php echo urlencode($busqueda); ?>&categoria=<?php echo $categoria_filtro; ?>&marca=<?php echo $marca_filtro; ?>"
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
                <a href="?pagina=<?php echo $pagina_actual + 1; ?>&busqueda=<?php echo urlencode($busqueda); ?>&categoria=<?php echo $categoria_filtro; ?>&marca=<?php echo $marca_filtro; ?>" 
                   class="pagina-btn siguiente" title="Página siguiente">
                    Siguiente ›
                </a>
                <a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo urlencode($busqueda); ?>&categoria=<?php echo $categoria_filtro; ?>&marca=<?php echo $marca_filtro; ?>" 
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
    </main>

    <!-- Modal para formulario -->
    <div class="modal-formulario" id="modalFormulario" style="<?php echo ($accion === 'crear' || $accion === 'editar' || $producto_id_eliminar) ? 'display: flex;' : 'display: none;'; ?>">
        <div class="modal-contenido">
            <?php if ($accion === 'crear' || $accion === 'editar'): ?>
                <div class="modal-header">
                    <h2>
                        <i class="fas fa-<?php echo $accion === 'crear' ? 'plus' : 'edit'; ?>"></i>
                        <?php echo $accion === 'crear' ? 'Nuevo Producto' : 'Editar Producto'; ?>
                    </h2>
                    <button class="btn-cerrar-modal" id="btnCerrarModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" class="form-producto" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="<?php echo $accion; ?>">
                    <?php if ($accion === 'editar' && $producto_editando): ?>
                        <input type="hidden" name="producto_id" value="<?php echo $producto_editando['id_producto']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-fila">
                        <div class="form-grupo">
                            <label for="nombre">
                                <i class="fas fa-tag"></i> Nombre del producto *
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?php echo htmlspecialchars($producto_editando['nombre_producto'] ?? ''); ?>" 
                                   required
                                   placeholder="Ej: Laptop Gaming ASUS ROG">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="precio">
                                <i class="fas fa-dollar-sign"></i> Precio ($) *
                            </label>
                            <input type="number" 
                                   id="precio" 
                                   name="precio" 
                                   step="0.01" 
                                   min="0.01"
                                   value="<?php echo isset($producto_editando['precio']) ? number_format($producto_editando['precio'], 2) : ''; ?>" 
                                   required
                                   placeholder="0.00">
                        </div>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="descripcion">
                            <i class="fas fa-align-left"></i> Descripción
                        </label>
                        <textarea id="descripcion" 
                                  name="descripcion" 
                                  rows="3"
                                  placeholder="Describe las características del producto..."><?php echo htmlspecialchars($producto_editando['descripcion'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-fila">
                        <div class="form-grupo">
                            <label for="categoria_id">
                                <i class="fas fa-folder"></i> Categoría *
                            </label>
                            <select id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccionar categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria['id_categoria']; ?>"
                                        <?php echo (isset($producto_editando['categoria_id']) && $producto_editando['categoria_id'] == $categoria['id_categoria']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-grupo">
                            <label for="marca_id">
                                <i class="fas fa-trademark"></i> Marca *
                            </label>
                            <select id="marca_id" name="marca_id" required>
                                <option value="">Seleccionar marca</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"
                                        <?php echo (isset($producto_editando['marca_id']) && $producto_editando['marca_id'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($marca['nombre_marca']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-grupo">
                            <label for="stock">
                                <i class="fas fa-boxes"></i> Stock *
                            </label>
                            <input type="number" 
                                   id="stock" 
                                   name="stock" 
                                   min="0"
                                   value="<?php echo $producto_editando['stock'] ?? '0'; ?>" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="imagen_archivo">
                            <i class="fas fa-image"></i> Imagen del producto
                        </label>
                        
                        <input type="file" 
                               id="imagen_archivo" 
                               name="imagen_archivo" 
                               accept="image/*"
                               class="input-archivo"
                               onchange="mostrarVistaPrevia(event)">
                        
                        <input type="text" 
                               id="imagen_url" 
                               name="imagen_url" 
                               placeholder="O ingresa URL de la imagen..."
                               value="<?php echo htmlspecialchars($producto_editando['imagen_url'] ?? ''); ?>"
                               class="input-url"
                               style="margin-top: 8px;"
                               oninput="mostrarVistaPreviaURL()">
                        
                        <div class="vista-previa-contenedor" id="vistaPreviaContenedor" style="<?php echo !empty($producto_editando['imagen_url']) ? '' : 'display: none;'; ?>">
                            <div class="vista-previa-titulo">Vista previa:</div>
                            <div class="vista-previa-imagen">
                                <?php if (!empty($producto_editando['imagen_url'])): ?>
                                    <img src="../<?php echo htmlspecialchars($producto_editando['imagen_url']); ?>" 
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
                        
                        <small class="texto-ayuda">Sube una imagen o ingresa una URL. Formatos: JPG, PNG, GIF (Max: 2MB)</small>
                    </div>
                    
                    <div class="form-fila">
                        <div class="form-grupo">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="tiene_descuento" 
                                       id="tiene_descuento"
                                       <?php echo (isset($producto_editando['tiene_descuento']) && $producto_editando['tiene_descuento']) ? 'checked' : ''; ?>>
                                <span><i class="fas fa-percentage"></i> ¿Tiene descuento?</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-fila" id="campo-descuento" style="<?php echo (isset($producto_editando['tiene_descuento']) && $producto_editando['tiene_descuento']) ? '' : 'display: none;'; ?>">
                        <div class="form-grupo">
                            <label for="porcentaje_descuento">
                                <i class="fas fa-percent"></i> Porcentaje de descuento (%)
                            </label>
                            <input type="number" 
                                   id="porcentaje_descuento" 
                                   name="porcentaje_descuento" 
                                   min="0" 
                                   max="100"
                                   value="<?php echo $producto_editando['porcentaje_descuento'] ?? '0'; ?>">
                        </div>
                        
                        <div class="form-grupo">
                            <label>
                                <i class="fas fa-tags"></i> Precio con descuento
                            </label>
                            <div id="precio-con-descuento" class="precio-calculado">
                                $0.00
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-fila">
                        <div class="form-grupo">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="activo" 
                                       id="activo"
                                       <?php echo isset($producto_editando['activo']) ? ($producto_editando['activo'] ? 'checked' : '') : 'checked'; ?>>
                                <span><i class="fas fa-check-circle"></i> Producto activo</span>
                            </label>
                        </div>
                        
                        <div class="form-grupo">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="destacado"
                                       id="destacado"
                                       <?php echo (isset($producto_editando['destacado']) && $producto_editando['destacado']) ? 'checked' : ''; ?>>
                                <span><i class="fas fa-star"></i> Producto destacado</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn-cancelar" id="btnCancelar">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn-guardar">
                            <i class="fas fa-<?php echo $accion === 'crear' ? 'save' : 'sync-alt'; ?>"></i>
                            <?php echo $accion === 'crear' ? 'Crear Producto' : 'Actualizar Producto'; ?>
                        </button>
                    </div>
                </form>
            
            <?php elseif ($producto_id_eliminar): ?>
                <!-- Modal de confirmación de eliminación -->
                <div class="modal-header">
                    <h2><i class="fas fa-exclamation-triangle"></i> Confirmar eliminación</h2>
                    <button class="btn-cerrar-modal" id="btnCerrarModalEliminar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="modal-cuerpo">
                    <p>¿Estás seguro de que quieres eliminar este producto?</p>
                    <p class="advertencia">
                        <i class="fas fa-exclamation-circle"></i>
                        Esta acción no se puede deshacer.
                    </p>
                    
                    <form method="POST" class="form-eliminar">
                        <input type="hidden" name="accion" value="confirmar_eliminar">
                        <input type="hidden" name="producto_id" value="<?php echo $producto_id_eliminar; ?>">
                        
                        <div class="modal-footer">
                            <button type="button" class="btn-cancelar" id="btnCancelarEliminar">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                            <button type="submit" class="btn-eliminar-confirmar">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="./js/dashboard.js"></script>
    <script src="../js/productos-admin.js"></script>

</body>
</html>