<?php
// admin/modelos.php
session_start();

// Verificar si usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php?error=sesion');
    exit();
}

require_once '../includes/config.php';

$mensaje = '';
$error = '';
$modelo_editando = null;
$accion = ''; // 'crear' o 'editar'

// Configuración de paginación
$modelos_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

$offset = ($pagina_actual - 1) * $modelos_por_pagina;

// Variables para búsqueda y filtro
$busqueda = trim($_GET['busqueda'] ?? '');
$marca_filtro = $_GET['marca'] ?? 'todas';

// Obtener marcas para filtro
try {
    $stmt_marcas = $conn->query("SELECT id_marca, nombre_marca FROM marcas ORDER BY nombre_marca");
    $marcas = $stmt_marcas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error obteniendo marcas: " . $e->getMessage());
    $marcas = [];
}

// Procesar búsqueda y obtener modelos con paginación
try {
    // Construir consulta base
    $sql_where = "WHERE 1=1";
    $params = [];
    
    if (!empty($busqueda)) {
        $sql_where .= " AND (m.nombre_modelo LIKE ? OR m.descripcion LIKE ?)";
        $params[] = "%$busqueda%";
        $params[] = "%$busqueda%";
    }
    
    if ($marca_filtro !== 'todas' && is_numeric($marca_filtro)) {
        $sql_where .= " AND m.marca_id = ?";
        $params[] = $marca_filtro;
    }
    
    // Obtener total de modelos
    $sql_total = "SELECT COUNT(*) as total FROM modelos m $sql_where";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->execute($params);
    $total_modelos = $stmt_total->fetch()['total'];
    $total_paginas = $total_modelos > 0 ? ceil($total_modelos / $modelos_por_pagina) : 1;
    
    // Obtener modelos de la página actual con información de marca
    $sql = "SELECT 
                m.*,
                ma.nombre_marca
            FROM modelos m
            LEFT JOIN marcas ma ON m.marca_id = ma.id_marca
            $sql_where
            ORDER BY m.creado_en DESC
            LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    
    // Agregar parámetros de límite y offset
    $params_limit = $params;
    $params_limit[] = $modelos_por_pagina;
    $params_limit[] = $offset;
    
    $stmt->execute($params_limit);
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Error obteniendo modelos: " . $e->getMessage());
    $modelos = [];
    $total_modelos = 0;
    $total_paginas = 1;
}

// Crear nuevo modelo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {
        $nombre_modelo = trim($_POST['nombre_modelo'] ?? '');
        $marca_id = isset($_POST['marca_id']) ? (int)$_POST['marca_id'] : 0;
        $descripcion = trim($_POST['descripcion'] ?? '');
        $especificaciones_input = trim($_POST['especificaciones'] ?? '');
        
        // Convertir especificaciones a JSON
        $especificaciones_json = '[]';
        if (!empty($especificaciones_input)) {
            $especificaciones_array = array_filter(
                array_map('trim', explode("\n", $especificaciones_input)),
                function($line) {
                    return !empty($line);
                }
            );
            $especificaciones_json = json_encode($especificaciones_array, JSON_UNESCAPED_UNICODE);
        }
        
        if (empty($nombre_modelo)) {
            $error = 'El nombre del modelo es obligatorio';
        } elseif ($marca_id <= 0) {
            $error = 'Debe seleccionar una marca';
        } else {
            try {
                // Verificar si el modelo ya existe para esta marca
                $stmt = $conn->prepare("SELECT id_modelo FROM modelos WHERE nombre_modelo = ? AND marca_id = ?");
                $stmt->execute([$nombre_modelo, $marca_id]);
                
                if ($stmt->fetch()) {
                    $error = 'Este modelo ya existe para la marca seleccionada';
                } else {
                    $sql = "INSERT INTO modelos (nombre_modelo, marca_id, descripcion, especificaciones_tecnicas) 
                            VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    if ($stmt->execute([$nombre_modelo, $marca_id, $descripcion, $especificaciones_json])) {
                        $mensaje = 'Modelo creado exitosamente';
                        // Redirigir a la primera página para ver el nuevo modelo
                        header("Location: modelos.php?pagina=1&mensaje=" . urlencode($mensaje));
                        exit();
                    } else {
                        $error = 'No se pudo crear el modelo';
                    }
                }
            } catch (PDOException $e) {
                error_log("Error creando modelo: " . $e->getMessage());
                $error = 'Error al crear el modelo';
            }
        }
        
        // Si hay error, mostrar modal de creación
        if ($error) {
            $accion = 'crear';
        }
    }
    
    // Actualizar modelo
    if ($_POST['accion'] === 'actualizar') {
        $id = $_POST['id'] ?? 0;
        $nombre_modelo = trim($_POST['nombre_modelo'] ?? '');
        $marca_id = isset($_POST['marca_id']) ? (int)$_POST['marca_id'] : 0;
        $descripcion = trim($_POST['descripcion'] ?? '');
        $especificaciones_input = trim($_POST['especificaciones'] ?? '');
        
        // Convertir especificaciones a JSON
        $especificaciones_json = '[]';
        if (!empty($especificaciones_input)) {
            $especificaciones_array = array_filter(
                array_map('trim', explode("\n", $especificaciones_input)),
                function($line) {
                    return !empty($line);
                }
            );
            $especificaciones_json = json_encode($especificaciones_array, JSON_UNESCAPED_UNICODE);
        }
        
        if (empty($nombre_modelo)) {
            $error = 'El nombre del modelo es obligatorio';
        } elseif ($marca_id <= 0) {
            $error = 'Debe seleccionar una marca';
        } else {
            try {
                // Verificar si el nombre ya existe en otro modelo de la misma marca
                $stmt = $conn->prepare("SELECT id_modelo FROM modelos WHERE nombre_modelo = ? AND marca_id = ? AND id_modelo != ?");
                $stmt->execute([$nombre_modelo, $marca_id, $id]);
                
                if ($stmt->fetch()) {
                    $error = 'Este nombre de modelo ya existe para la marca seleccionada';
                } else {
                    $sql = "UPDATE modelos 
                            SET nombre_modelo = ?, marca_id = ?, descripcion = ?, especificaciones_tecnicas = ?, actualizado_en = CURRENT_TIMESTAMP 
                            WHERE id_modelo = ?";
                    $stmt = $conn->prepare($sql);
                    
                    if ($stmt->execute([$nombre_modelo, $marca_id, $descripcion, $especificaciones_json, $id])) {
                        $mensaje = 'Modelo actualizado exitosamente';
                        // Redirigir manteniendo la página actual
                        header("Location: modelos.php?pagina=$pagina_actual&mensaje=" . urlencode($mensaje));
                        exit();
                    } else {
                        $error = 'No se pudo actualizar el modelo';
                    }
                }
            } catch (PDOException $e) {
                error_log("Error actualizando modelo: " . $e->getMessage());
                $error = 'Error al actualizar el modelo';
            }
        }
        
        // Si hay error, mostrar modal de edición
        if ($error) {
            $accion = 'editar';
        }
    }
}

// Eliminar modelo
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    try {
        // Verificar si hay productos asociados
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM productos WHERE modelo_id = ?");
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        
        if ($resultado['total'] > 0) {
            $error = 'No se puede eliminar el modelo porque tiene productos asociados';
        } else {
            $stmt = $conn->prepare("DELETE FROM modelos WHERE id_modelo = ?");
            $stmt->execute([$id]);
            
            $mensaje = 'Modelo eliminado exitosamente';
            // Recalcular página después de eliminar
            $total_despues = max(0, $total_modelos - 1);
            $pagina_despues = ($pagina_actual > ceil($total_despues / $modelos_por_pagina)) 
                ? max(1, ceil($total_despues / $modelos_por_pagina)) 
                : $pagina_actual;
            
            header("Location: modelos.php?pagina=$pagina_despues&mensaje=" . urlencode($mensaje));
            exit();
        }
    } catch (PDOException $e) {
        error_log("Error eliminando modelo: " . $e->getMessage());
        $error = 'Error al eliminar el modelo';
    }
}

// Obtener modelo para editar
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    try {
        $stmt = $conn->prepare("SELECT * FROM modelos WHERE id_modelo = ?");
        $stmt->execute([$id]);
        $modelo_editando = $stmt->fetch();
        
        if ($modelo_editando) {
            $accion = 'editar';
        } else {
            $error = 'Modelo no encontrado';
        }
    } catch (PDOException $e) {
        error_log("Error obteniendo modelo: " . $e->getMessage());
        $error = 'Error al cargar el modelo';
    }
}

// Mostrar modal de creación
if (isset($_GET['crear']) && $_GET['crear'] == '1') {
    $accion = 'crear';
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
    <title>Modelos - Burmex Admin</title>
    <link rel="stylesheet" href="../styles/css/dashboard.css">
    <link rel="stylesheet" href="../styles/css/modelos.css">
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
            <div class="encabezado-modelos">
                <div class="titulo-seccion">
                    <h1>Modelos</h1>
                    <p class="subtitulo-seccion">Administra los modelos de productos</p>
                </div>
                <a href="?crear=1&pagina=<?php echo $pagina_actual; ?>" class="btn-nuevo-modelo" id="btnNuevoModelo">
                    <i class="fas fa-plus"></i> Nuevo Modelo
                </a>
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

            <!-- Buscador y filtros -->
            <div class="filtros-busqueda">
                <div class="buscador-usuarios">
                    <i class="fas fa-search"></i>
                    <form method="GET" class="form-busqueda-inline" id="formBusquedaModelos">
                        <input type="hidden" name="pagina" value="1">
                        <input type="text" 
                               name="busqueda" 
                               id="buscar-modelo" 
                               placeholder="Buscar modelos, descripción..."
                               value="<?php echo htmlspecialchars($busqueda); ?>"
                               class="input-busqueda-inline">
                    </form>
                </div>
                
                <div class="filtros-select">
                    <select name="marca" class="select-filtro" id="filtro-marca" onchange="filtrarModelos()">
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

            <!-- Grid de modelos -->
            <div class="grid-modelos" id="gridModelos">
                <?php if (count($modelos) > 0): ?>
                    <?php foreach ($modelos as $modelo): ?>
                        <div class="tarjeta-modelo">
                            <div class="modelo-header">
                                <div class="modelo-info">
                                    <div class="modelo-marca">
                                        <i class="fas fa-trademark"></i> 
                                        <?php echo htmlspecialchars($modelo['nombre_marca'] ?? 'Sin marca'); ?>
                                    </div>
                                    <h3 class="modelo-nombre">
                                        <?php echo htmlspecialchars($modelo['nombre_modelo']); ?>
                                    </h3>
                                </div>
                                <div class="modelo-acciones">
                                    <a href="?editar=<?php echo $modelo['id_modelo']; ?>&pagina=<?php echo $pagina_actual; ?>" 
                                       class="btn-accion btn-editar" title="Editar modelo">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn-accion btn-eliminar" 
                                            onclick="confirmarEliminar(<?php echo $modelo['id_modelo']; ?>, '<?php echo htmlspecialchars($modelo['nombre_modelo']); ?>', <?php echo $pagina_actual; ?>)"
                                            title="Eliminar modelo">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="modelo-contenido">
                                <?php if (!empty($modelo['descripcion'])): ?>
                                    <p class="modelo-descripcion">
                                        <?php 
                                        $descripcion = htmlspecialchars($modelo['descripcion']);
                                        if (strlen($descripcion) > 150) {
                                            echo substr($descripcion, 0, 150) . '...';
                                        } else {
                                            echo $descripcion;
                                        }
                                        ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($modelo['especificaciones_tecnicas'])): ?>
                                    <div class="modelo-especificaciones">
                                        <?php 
                                        $especificaciones = json_decode($modelo['especificaciones_tecnicas'], true);
                                        if (is_array($especificaciones) && count($especificaciones) > 0) {
                                            $mostradas = array_slice($especificaciones, 0, 3);
                                            foreach ($mostradas as $espec):
                                                if (!empty(trim($espec))):
                                        ?>
                                            <div class="especificacion-item">
                                                <i class="fas fa-check-circle"></i>
                                                <span><?php echo htmlspecialchars(trim($espec)); ?></span>
                                            </div>
                                        <?php 
                                                endif;
                                            endforeach;
                                            if (count($especificaciones) > 3): 
                                        ?>
                                            <div class="mas-especificaciones">
                                                +<?php echo count($especificaciones) - 3; ?> más...
                                            </div>
                                        <?php 
                                            endif;
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="modelo-footer">
                                <div class="fechas-modelo">
                                    <span class="modelo-fecha">
                                        <i class="far fa-calendar-plus"></i>
                                        Creado: <?php echo date('d/m/Y', strtotime($modelo['creado_en'])); ?>
                                    </span>
                                    <?php if ($modelo['actualizado_en'] && $modelo['actualizado_en'] != $modelo['creado_en']): ?>
                                        <span class="modelo-fecha">
                                            <i class="far fa-calendar-check"></i>
                                            Actualizado: <?php echo date('d/m/Y', strtotime($modelo['actualizado_en'])); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="sin-modelos">
                        <i class="fas fa-cubes"></i>
                        <h3>No hay modelos registrados</h3>
                        <p>Crea tu primer modelo usando el botón "Nuevo Modelo"</p>
                        <?php if ($busqueda || $marca_filtro !== 'todas'): ?>
                            <button class="btn-limpiar-filtros" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar filtros
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
                <div class="paginacion">
                    <div class="info-paginacion">
                        Mostrando <?php echo (($pagina_actual - 1) * $modelos_por_pagina) + 1; ?> - 
                        <?php 
                            $hasta = min($pagina_actual * $modelos_por_pagina, $total_modelos);
                            echo $hasta;
                        ?> 
                        de <?php echo $total_modelos; ?> modelos
                    </div>
                    
                    <div class="controles-paginacion">
                        <?php if ($pagina_actual > 1): ?>
                            <a href="?pagina=1&busqueda=<?php echo urlencode($busqueda); ?>&marca=<?php echo $marca_filtro; ?>" 
                               class="pagina-btn primera" title="Primera página">
                                « Primera
                            </a>
                            <a href="?pagina=<?php echo $pagina_actual - 1; ?>&busqueda=<?php echo urlencode($busqueda); ?>&marca=<?php echo $marca_filtro; ?>" 
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
                            <a href="?pagina=<?php echo $i; ?>&busqueda=<?php echo urlencode($busqueda); ?>&marca=<?php echo $marca_filtro; ?>"
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
                            <a href="?pagina=<?php echo $pagina_actual + 1; ?>&busqueda=<?php echo urlencode($busqueda); ?>&marca=<?php echo $marca_filtro; ?>" 
                               class="pagina-btn siguiente" title="Página siguiente">
                                Siguiente ›
                            </a>
                            <a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo urlencode($busqueda); ?>&marca=<?php echo $marca_filtro; ?>" 
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

    <!-- Modal para formulario -->
    <div class="modal-formulario" id="modalFormulario" style="<?php echo ($accion === 'crear' || $accion === 'editar') ? 'display: flex;' : 'display: none;'; ?>">
        <div class="modal-contenido">
            <?php if ($accion === 'crear' || $accion === 'editar'): ?>
                <div class="modal-header">
                    <h2>
                        <i class="fas fa-<?php echo $accion === 'crear' ? 'plus' : 'edit'; ?>"></i>
                        <?php echo $accion === 'crear' ? 'Nuevo Modelo' : 'Editar Modelo'; ?>
                    </h2>
                    <button class="btn-cerrar-modal" onclick="cerrarModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" class="form-modelo">
                    <input type="hidden" name="accion" value="<?php echo $accion === 'crear' ? 'crear' : 'actualizar'; ?>">
                    <?php if ($accion === 'editar' && $modelo_editando): ?>
                        <input type="hidden" name="id" value="<?php echo $modelo_editando['id_modelo']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-fila">
                        <div class="form-grupo">
                            <label for="nombre_modelo">
                                <i class="fas fa-tag"></i> Nombre del modelo *
                            </label>
                            <input type="text" 
                                   id="nombre_modelo" 
                                   name="nombre_modelo" 
                                   value="<?php echo htmlspecialchars($modelo_editando ? $modelo_editando['nombre_modelo'] : ($_POST['nombre_modelo'] ?? '')); ?>" 
                                   required
                                   placeholder="Ej: Galaxy S23, MacBook Pro M2">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="marca_id">
                                <i class="fas fa-trademark"></i> Marca *
                            </label>
                            <select id="marca_id" name="marca_id" required>
                                <option value="">Seleccionar marca</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?php echo $marca['id_marca']; ?>"
                                        <?php echo (isset($modelo_editando['marca_id']) && $modelo_editando['marca_id'] == $marca['id_marca']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($marca['nombre_marca']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="descripcion">
                            <i class="fas fa-align-left"></i> Descripción
                        </label>
                        <textarea id="descripcion" 
                                  name="descripcion" 
                                  rows="3"
                                  placeholder="Describe las características principales del modelo..."><?php echo htmlspecialchars($modelo_editando ? $modelo_editando['descripcion'] : ($_POST['descripcion'] ?? '')); ?></textarea>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="especificaciones">
                            <i class="fas fa-list-alt"></i> Especificaciones técnicas
                        </label>
                        <textarea id="especificaciones" 
                                  name="especificaciones" 
                                  rows="4"
                                  placeholder="Ingresa las especificaciones técnicas, una por línea..."><?php 
                            if ($modelo_editando && !empty($modelo_editando['especificaciones_tecnicas'])) {
                                $espec_array = json_decode($modelo_editando['especificaciones_tecnicas'], true);
                                if (is_array($espec_array)) {
                                    echo htmlspecialchars(implode("\n", $espec_array));
                                }
                            } else {
                                echo htmlspecialchars($_POST['especificaciones'] ?? '');
                            }
                        ?></textarea>
                        <small class="texto-ayuda">Ingresa cada especificación en una línea separada</small>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn-cancelar" onclick="cerrarModal()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="<?php echo $accion === 'crear' ? 'btn-crear' : 'btn-actualizar'; ?>">
                            <i class="fas fa-<?php echo $accion === 'crear' ? 'plus' : 'save'; ?>"></i>
                            <?php echo $accion === 'crear' ? 'Crear Modelo' : 'Actualizar Modelo'; ?>
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    </main>
    
    <!-- JavaScript -->
    <script src="./js/dashboard.js"></script>
    <script src="../js/modelos.js"></script>
</body>
</html>