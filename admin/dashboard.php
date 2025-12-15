<?php
// admin/dashboard.php
session_start();

// Verificar si usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php?error=sesion');
    exit();
}

// Incluir conexión a BD
require_once '../includes/config.php';

// Definir funciones de formato
function formatoMoneda($valor) {
    return number_format($valor, 2);
}

function formatoNumero($valor) {
    return number_format($valor, 0);
}

// Obtener estadísticas
try {
    // Ventas del mes actual
    $mes_actual = date('Y-m');
    $sql_ventas_mes = "SELECT SUM(total) as ventas_mes FROM ordenes 
                       WHERE estado = 'completado' 
                       AND DATE_FORMAT(fecha_orden, '%Y-%m') = ?";
    $stmt = $conn->prepare($sql_ventas_mes);
    $stmt->execute([$mes_actual]);
    $ventas_mes = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ventas mes anterior
    $mes_anterior = date('Y-m', strtotime('-1 month'));
    $stmt->execute([$mes_anterior]);
    $ventas_mes_anterior = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Órdenes totales
    $sql_ordenes = "SELECT COUNT(*) as total_ordenes FROM ordenes";
    $stmt = $conn->prepare($sql_ordenes);
    $stmt->execute();
    $ordenes = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Productos activos
    $sql_productos = "SELECT COUNT(*) as total_productos FROM productos WHERE activo = 1";
    $stmt = $conn->prepare($sql_productos);
    $stmt->execute();
    $productos = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Clientes nuevos este mes
    $sql_clientes = "SELECT COUNT(*) as clientes_nuevos FROM clientes 
                     WHERE DATE_FORMAT(fecha_registro, '%Y-%m') = ?";
    $stmt = $conn->prepare($sql_clientes);
    $stmt->execute([$mes_actual]);
    $clientes = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Productos más vendidos (último mes)
    $sql_mas_vendidos = "SELECT 
                            p.id_producto,
                            p.nombre_producto,
                            p.precio,
                            p.imagen_url,
                            m.nombre_marca,
                            SUM(od.cantidad) as unidades_vendidas
                         FROM orden_detalles od
                         JOIN productos p ON od.producto_id = p.id_producto
                         JOIN marcas m ON p.marca_id = m.id_marca
                         JOIN ordenes o ON od.orden_id = o.id_orden
                         WHERE o.estado = 'completado' 
                         AND o.fecha_orden >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
                         GROUP BY p.id_producto
                         ORDER BY unidades_vendidas DESC
                         LIMIT 4";
    $stmt = $conn->prepare($sql_mas_vendidos);
    $stmt->execute();
    $productos_mas_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Datos para gráfica de ventas mensuales (últimos 6 meses)
    $sql_ventas_mensuales = "SELECT 
                                DATE_FORMAT(fecha_orden, '%Y-%m') as mes,
                                DATE_FORMAT(fecha_orden, '%b') as mes_nombre,
                                SUM(total) as total_ventas
                             FROM ordenes
                             WHERE estado = 'completado'
                             AND fecha_orden >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                             GROUP BY DATE_FORMAT(fecha_orden, '%Y-%m')
                             ORDER BY mes";
    $stmt = $conn->prepare($sql_ventas_mensuales);
    $stmt->execute();
    $ventas_mensuales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Preparar datos para la gráfica
    $meses_grafica = [];
    $ventas_grafica = [];
    
    foreach ($ventas_mensuales as $venta) {
        $meses_grafica[] = $venta['mes_nombre'];
        $ventas_grafica[] = (float)$venta['total_ventas'];
    }
    
} catch (PDOException $e) {
    error_log("Error obteniendo estadísticas: " . $e->getMessage());
    $ventas_mes = ['ventas_mes' => 0];
    $ventas_mes_anterior = ['ventas_mes' => 0];
    $ordenes = ['total_ordenes' => 0];
    $productos = ['total_productos' => 0];
    $clientes = ['clientes_nuevos' => 0];
    $productos_mas_vendidos = [];
    $meses_grafica = [];
    $ventas_grafica = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Burmex Admin</title>
    <link rel="stylesheet" href="../styles/css/dashboard.css">
    <link rel="icon" type="image/x-icon" href="../img/img-inicio/logo-icon.ico">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include_once 'includes/navbar.php'; ?>
    <?php include_once 'includes/sidebar.php'; ?>
    
    <!-- Overlay para móvil -->
    <div class="capa-lateral"></div>

    <!-- Contenido principal -->
    <main class="contenido-principal">
        <div class="contenedor">
            <!-- Título -->
            <div class="encabezado-panel">
                <h1>Panel de Control</h1>
                <p class="subtitulo-panel">Resumen general de la tienda</p>
            </div> 

            <!-- Métricas -->
            <div class="cuadricula-metricas">
                <!-- Métrica 1: Ventas del mes -->
                <div class="tarjeta-metrica">
                    <div class="encabezado-metrica">
                        <h3 class="titulo-metrica">Venta del mes</h3>
                        <div class="icono-metrica">
                            <img src="../img/img-dash/dollar.png" alt="Ventas">
                        </div>
                    </div>
                    <div class="valor-metrica">$<?php echo formatoMoneda($ventas_mes['ventas_mes'] ?? 0); ?></div>
                    <div class="variacion-metrica">
                        <?php
                        $venta_actual = $ventas_mes['ventas_mes'] ?? 0;
                        $venta_anterior = $ventas_mes_anterior['ventas_mes'] ?? 0;
                        $diferencia = $venta_actual - $venta_anterior;
                        
                        if ($venta_anterior > 0) {
                            $porcentaje = ($diferencia / $venta_anterior) * 100;
                            $clase = $diferencia >= 0 ? 'positivo' : 'negativo';
                            $icono = $diferencia >= 0 ? '↗' : '↘';
                            echo '<span class="etiqueta-variacion ' . $clase . '">' . $icono . ' ' . abs(round($porcentaje, 1)) . '% vs mes pasado</span>';
                        } else {
                            echo '<span class="etiqueta-variacion">No hay datos del mes pasado</span>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Métrica 2: Órdenes totales -->
                <div class="tarjeta-metrica">
                    <div class="encabezado-metrica">
                        <h3 class="titulo-metrica">Pedidos totales</h3>
                        <div class="icono-metrica">
                            <img src="../img/img-dash/package.png" alt="Órdenes">
                        </div>
                    </div>
                    <div class="valor-metrica"><?php echo formatoNumero($ordenes['total_ordenes'] ?? 0); ?></div>
                    <div class="variacion-metrica">
                        <span class="etiqueta-variacion">Total de pedidos completados</span>
                    </div>
                </div>

                <!-- Métrica 3: Productos activos -->
                <div class="tarjeta-metrica">
                    <div class="encabezado-metrica">
                        <h3 class="titulo-metrica">Productos activos</h3>
                        <div class="icono-metrica">
                            <img src="../img/img-dash/product.png" alt="Productos">
                        </div>
                    </div>
                    <div class="valor-metrica"><?php echo formatoNumero($productos['total_productos'] ?? 0); ?></div>
                    <div class="variacion-metrica">
                        <span class="etiqueta-variacion">Disponibles en catálogo</span>
                    </div>
                </div>

                <!-- Métrica 4: Clientes nuevos -->
                <div class="tarjeta-metrica">
                    <div class="encabezado-metrica">
                        <h3 class="titulo-metrica">Clientes nuevos</h3>
                        <div class="icono-metrica">
                            <img src="../img/img-dash/users.png" alt="Clientes">
                        </div>
                    </div>
                    <div class="valor-metrica"><?php echo formatoNumero($clientes['clientes_nuevos'] ?? 0); ?></div>
                    <div class="variacion-metrica">
                        <span class="etiqueta-variacion">Este mes</span>
                    </div>
                </div>
            </div>

            <!-- Segunda fila: Gráfica y Productos más vendidos -->
            <div class="fila-panel">
                <!-- Gráfica de ventas mensuales -->
                <div class="contenedor-grafica">
                    <div class="encabezado-grafica">
                        <h2 class="titulo-grafica">Ventas mensuales</h2>
                        <div class="periodo-grafica">
                            <button class="boton-periodo activo" data-periodo="6m">6M</button>
                            <button class="boton-periodo" data-periodo="1y">1A</button>
                        </div>
                    </div>
                    <div class="caja-grafica">
                        <canvas id="graficaVentas" 
                                data-meses='<?php echo json_encode($meses_grafica); ?>'
                                data-ventas='<?php echo json_encode($ventas_grafica); ?>'>
                        </canvas>
                    </div>
                    <div class="resumen-grafica">
                        <div class="item-resumen">
                            <span class="etiqueta-resumen">Total 6 meses:</span>
                            <span class="valor-resumen">$<?php echo formatoMoneda(array_sum($ventas_grafica)); ?></span>
                        </div>
                        <div class="item-resumen">
                            <span class="etiqueta-resumen">Promedio mensual:</span>
                            <span class="valor-resumen">$<?php 
                                $promedio = count($ventas_grafica) > 0 ? array_sum($ventas_grafica) / count($ventas_grafica) : 0;
                                echo formatoMoneda($promedio); 
                            ?></span>
                        </div>
                    </div>
                </div>

                <!-- Productos más vendidos -->
                <div class="contenedor-top-productos">
                    <div class="encabezado-top-productos">
                        <h2 class="titulo-top-productos">Productos más vendidos</h2>
                        <div class="acciones-top-productos">
                            <span class="etiqueta-periodo">Este mes</span>
                            <a href="../admin/productos/index.php" class="enlace-ver-todo">Ver todos</a>
                        </div>
                    </div>
                    <div class="lista-top-productos">
                        <?php if (count($productos_mas_vendidos) > 0): ?>
                            <?php foreach ($productos_mas_vendidos as $index => $producto): ?>
                                <div class="item-top-producto">
                                    <div class="posicion-producto">#<?php echo $index + 1; ?></div>
                                    <div class="imagen-producto">
                                        <?php if (!empty($producto['imagen_url'])): ?>
                                            <img src="../<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                        <?php else: ?>
                                            <div class="placeholder-imagen">
                                                <span>🖥️</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="info-producto">
                                        <h4 class="nombre-producto"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h4>
                                        <p class="marca-producto"><?php echo htmlspecialchars($producto['nombre_marca']); ?></p>
                                    </div>
                                    <div class="estadisticas-producto">
                                        <div class="precio-producto">$<?php echo formatoMoneda($producto['precio']); ?></div>
                                        <div class="ventas-producto">
                                            <span class="icono-ventas">📦</span>
                                            <span class="cantidad-ventas"><?php echo formatoNumero($producto['unidades_vendidas']); ?> vendidos</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="mensaje-sin-datos">
                                <p>No hay datos de ventas este mes</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Pedidos recientes -->
            <div class="contenedor-pedidos-recientes">
                <div class="encabezado-pedidos-recientes">
                    <h2 class="titulo-pedidos-recientes">Pedidos recientes</h2>
                    <div class="acciones-pedidos-recientes">
                        <a href="../admin/ordenes/index.php" class="enlace-ver-todo">Ver todos los pedidos</a>
                    </div>
                </div>
                
                <table class="tabla-pedidos">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Obtener 5 pedidos recientes
                        try {
                            $sql_pedidos_recientes = "SELECT 
                                o.id_orden,
                                o.nombre_cliente,
                                o.total,
                                o.estado,
                                DATE_FORMAT(o.fecha_orden, '%d/%m/%Y %H:%i') as fecha_formateada,
                                (
                                    SELECT p.nombre_producto 
                                    FROM orden_detalles od2 
                                    JOIN productos p ON od2.producto_id = p.id_producto 
                                    WHERE od2.orden_id = o.id_orden 
                                    LIMIT 1
                                ) as producto_principal
                            FROM ordenes o
                            ORDER BY o.fecha_orden DESC
                            LIMIT 5";
                            
                            $stmt = $conn->prepare($sql_pedidos_recientes);
                            $stmt->execute();
                            $pedidos_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($pedidos_recientes) > 0):
                                foreach ($pedidos_recientes as $pedido):
                                    // Limitar nombre del producto
                                    $producto = $pedido['producto_principal'] ?: 'Sin producto';
                                    if (strlen($producto) > 25) {
                                        $producto = substr($producto, 0, 25) . '...';
                                    }
                                    
                                    // Clase CSS según estado
                                    $clase_estado = 'estado-' . $pedido['estado'];
                                    
                                    // Limitar nombre del cliente
                                    $cliente = htmlspecialchars($pedido['nombre_cliente'] ?: 'Cliente no especificado');
                                    if (strlen($cliente) > 20) {
                                        $cliente = substr($cliente, 0, 20) . '...';
                                    }
                        ?>
                        <tr>
                            <td class="columna-pedido">#<?php echo $pedido['id_orden']; ?></td>
                            <td class="columna-cliente"><?php echo $cliente; ?></td>
                            <td class="columna-producto" title="<?php echo htmlspecialchars($pedido['producto_principal'] ?: ''); ?>">
                                <?php echo htmlspecialchars($producto); ?>
                            </td>
                            <td class="columna-monto">$<?php echo formatoMoneda($pedido['total']); ?></td>
                            <td>
                                <span class="columna-estado <?php echo $clase_estado; ?>">
                                    <?php 
                                    $estados = [
                                        'completado' => 'Completado',
                                        'pendiente' => 'Pendiente',
                                        'cancelado' => 'Cancelado',
                                        'en-proceso' => 'En Proceso'
                                    ];
                                    echo $estados[$pedido['estado']] ?? ucfirst($pedido['estado']);
                                    ?>
                                </span>
                            </td>
                            <td class="columna-fecha"><?php echo $pedido['fecha_formateada']; ?></td>
                        </tr>
                        <?php
                                endforeach;
                            else:
                        ?>
                        <tr>
                            <td colspan="6" class="mensaje-vacio">
                                No hay pedidos recientes
                            </td>
                        </tr>
                        <?php
                            endif;
                        } catch (PDOException $e) {
                            error_log("Error obteniendo pedidos recientes: " . $e->getMessage());
                        ?>
                        <tr>
                            <td colspan="6" class="mensaje-error">
                                Error al cargar los pedidos
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script src="../js/dashboard.js"></script>
</body>
</html>