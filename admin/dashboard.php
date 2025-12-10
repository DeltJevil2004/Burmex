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
    <title>Dashboard - Burmex Admin</title>
    <link rel="stylesheet" href="../styles/css/dashboard.css">
    <link rel="icon" type="image/x-icon" href="../img/img-inicio/logo-icon.ico">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include_once 'includes/navbar.php'; ?>
    <?php include_once 'includes/sidebar.php'; ?>
    
    <!-- Overlay para móvil -->
    <div class="sidebar-overlay"></div>

    <!-- Contenido principal -->
    <main class="main-content">
        <div class="container">
            <!-- Título -->
            <div class="dashboard-header">
                <h1>Dashboard</h1>
                <p class="dashboard-subtitle">Resumen general de tu tienda</p>
            </div> 

            <!-- Métricas -->
            <div class="metrics-grid">
                <!-- Métrica 1: Ventas del mes -->
                <div class="metric-card">
                    <div class="metric-header">
                        <h3 class="metric-title">Venta del mes</h3>
                        <div class="metric-icon">
                            <img src="../img/img-dash/dollar.png" alt="Ventas">
                        </div>
                    </div>
                    <div class="metric-value">$<?php echo formatoMoneda($ventas_mes['ventas_mes'] ?? 0); ?></div>
                    <div class="metric-change">
                        <?php
                        $venta_actual = $ventas_mes['ventas_mes'] ?? 0;
                        $venta_anterior = $ventas_mes_anterior['ventas_mes'] ?? 0;
                        $diferencia = $venta_actual - $venta_anterior;
                        
                        if ($venta_anterior > 0) {
                            $porcentaje = ($diferencia / $venta_anterior) * 100;
                            $clase = $diferencia >= 0 ? 'positive' : 'negative';
                            $icono = $diferencia >= 0 ? '↗' : '↘';
                            echo '<span class="change-label ' . $clase . '">' . $icono . ' ' . abs(round($porcentaje, 1)) . '% vs mes pasado</span>';
                        } else {
                            echo '<span class="change-label">No hay datos del mes pasado</span>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Métrica 2: Órdenes totales -->
                <div class="metric-card">
                    <div class="metric-header">
                        <h3 class="metric-title">Órdenes totales</h3>
                        <div class="metric-icon">
                            <img src="../img/img-dash/package.png" alt="Órdenes">
                        </div>
                    </div>
                    <div class="metric-value"><?php echo formatoNumero($ordenes['total_ordenes'] ?? 0); ?></div>
                    <div class="metric-change">
                        <span class="change-label">Total de pedidos completados</span>
                    </div>
                </div>

                <!-- Métrica 3: Productos activos -->
                <div class="metric-card">
                    <div class="metric-header">
                        <h3 class="metric-title">Productos activos</h3>
                        <div class="metric-icon">
                            <img src="../img/img-dash/product.png" alt="Productos">
                        </div>
                    </div>
                    <div class="metric-value"><?php echo formatoNumero($productos['total_productos'] ?? 0); ?></div>
                    <div class="metric-change">
                        <span class="change-label">Disponibles en catálogo</span>
                    </div>
                </div>

                <!-- Métrica 4: Clientes nuevos -->
                <div class="metric-card">
                    <div class="metric-header">
                        <h3 class="metric-title">Clientes nuevos</h3>
                        <div class="metric-icon">
                            <img src="../img/img-dash/users.png" alt="Clientes">
                        </div>
                    </div>
                    <div class="metric-value"><?php echo formatoNumero($clientes['clientes_nuevos'] ?? 0); ?></div>
                    <div class="metric-change">
                        <span class="change-label">Este mes</span>
                    </div>
                </div>
            </div>

            <!-- Segunda fila: Gráfica y Productos más vendidos -->
            <div class="dashboard-row">
                <!-- Gráfica de ventas mensuales -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h2 class="chart-title">Ventas mensuales</h2>
                        <div class="chart-period">
                            <button class="period-btn active" data-period="6m">6M</button>
                            <button class="period-btn" data-period="1y">1A</button>
                        </div>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="salesChart" 
                                data-meses='<?php echo json_encode($meses_grafica); ?>'
                                data-ventas='<?php echo json_encode($ventas_grafica); ?>'>
                        </canvas>
                    </div>
                    <div class="chart-summary">
                        <div class="summary-item">
                            <span class="summary-label">Total 6 meses:</span>
                            <span class="summary-value">$<?php echo formatoMoneda(array_sum($ventas_grafica)); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Promedio mensual:</span>
                            <span class="summary-value">$<?php 
                                $promedio = count($ventas_grafica) > 0 ? array_sum($ventas_grafica) / count($ventas_grafica) : 0;
                                echo formatoMoneda($promedio); 
                            ?></span>
                        </div>
                    </div>
                </div>

                <!-- Productos más vendidos -->
                <div class="top-products-container">
                    <div class="top-products-header">
                        <h2 class="top-products-title">Productos más vendidos</h2>
                        <div class="top-products-actions">
                            <span class="period-label">Este mes</span>
                            <a href="../admin/productos/index.php" class="view-all-link">Ver todos</a>
                        </div>
                    </div>
                    <div class="top-products-list">
                        <?php if (count($productos_mas_vendidos) > 0): ?>
                            <?php foreach ($productos_mas_vendidos as $index => $producto): ?>
                                <div class="top-product-item">
                                    <div class="product-rank">#<?php echo $index + 1; ?></div>
                                    <div class="product-image">
                                        <?php if (!empty($producto['imagen_url'])): ?>
                                            <img src="../<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                                        <?php else: ?>
                                            <div class="product-image-placeholder">
                                                <span>🖥️</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-info">
                                        <h4 class="product-name"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h4>
                                        <p class="product-brand"><?php echo htmlspecialchars($producto['nombre_marca']); ?></p>
                                    </div>
                                    <div class="product-stats">
                                        <div class="product-price">$<?php echo formatoMoneda($producto['precio']); ?></div>
                                        <div class="product-sales">
                                            <span class="sales-icon">📦</span>
                                            <span class="sales-count"><?php echo formatoNumero($producto['unidades_vendidas']); ?> vendidos</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-data-message">
                                <p>No hay datos de ventas este mes</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Órdenes recientes -->
            <div class="recent-orders-container">
                <div class="recent-orders-header">
                    <h2 class="recent-orders-title">Órdenes recientes</h2>
                    <div class="recent-orders-actions">
                        <a href="../admin/ordenes/index.php" class="view-all-link">Ver todas las órdenes</a>
                    </div>
                </div>
                
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Obtener 5 órdenes recientes
                        try {
                            $sql_ordenes_recientes = "SELECT 
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
                            
                            $stmt = $conn->prepare($sql_ordenes_recientes);
                            $stmt->execute();
                            $ordenes_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($ordenes_recientes) > 0):
                                foreach ($ordenes_recientes as $orden):
                                    // Limitar nombre del producto
                                    $producto = $orden['producto_principal'] ?: 'Sin producto';
                                    if (strlen($producto) > 25) {
                                        $producto = substr($producto, 0, 25) . '...';
                                    }
                                    
                                    // Clase CSS según estado
                                    $clase_estado = 'status-' . $orden['estado'];
                                    
                                    // Limitar nombre del cliente
                                    $cliente = htmlspecialchars($orden['nombre_cliente'] ?: 'Cliente no especificado');
                                    if (strlen($cliente) > 20) {
                                        $cliente = substr($cliente, 0, 20) . '...';
                                    }
                        ?>
                        <tr>
                            <td class="column-order">#<?php echo $orden['id_orden']; ?></td>
                            <td class="column-client"><?php echo $cliente; ?></td>
                            <td class="column-product" title="<?php echo htmlspecialchars($orden['producto_principal'] ?: ''); ?>">
                                <?php echo htmlspecialchars($producto); ?>
                            </td>
                            <td class="column-amount">$<?php echo formatoMoneda($orden['total']); ?></td>
                            <td>
                                <span class="column-status <?php echo $clase_estado; ?>">
                                    <?php echo ucfirst($orden['estado']); ?>
                                </span>
                            </td>
                            <td class="column-date"><?php echo $orden['fecha_formateada']; ?></td>
                        </tr>
                        <?php
                                endforeach;
                            else:
                        ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #6b7280;">
                                No hay órdenes recientes
                            </td>
                        </tr>
                        <?php
                            endif;
                        } catch (PDOException $e) {
                            error_log("Error obteniendo órdenes recientes: " . $e->getMessage());
                        ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #dc2626;">
                                Error al cargar las órdenes
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