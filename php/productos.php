<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burmex</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="../styles/css/navbar.css"/>
    <link rel="stylesheet" href="../styles/css/inicio.css"/>
    <link rel="stylesheet" href="../styles/css/inicio-movil.css"/>
    <link rel="stylesheet" href="../styles/css/footer.css"/>
    <link rel="stylesheet" href="../styles/css/productos.css"/>
    <link rel="stylesheet" href="../styles/css/productos-movil.css"/>

    <!-- Iconos -->
    <link rel="icon" type="image/x-icon" href="../img/img-inicio/logo-icon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/img-inicio/logo-apple-icon.ico">
</head>
<body>
<!-- Navbar -->
<header>
 
   <?php include '../php/navbar.php'; ?>
</header>

<!-- Hero Banner Productos -->
<section class="hero-banner-productos">
    <div class="hero-container-productos">
        <div class="hero-content-productos">
            <h1 class="hero-title-productos">Catálogo de Productos</h1>
            <p class="hero-text-productos">Encuentra el equipo perfecto para tus necesidades. Más de 500 productos disponibles.</p>
        </div>
    </div>
</section>

<!-- Sección Productos y Filtros -->
<section class="productos-section">
    <div class="container">
        <!-- Encabezado de Productos -->
        <div class="productos-header">
            <div class="productos-info">
                <h2 class="productos-titulo">Todos los productos</h2>
                <p class="productos-contador" id="productosContador">500 productos encontrados</p>
            </div>
            <div class="productos-ordenar">
                <label for="ordenarPor">Ordenar por:</label>
                <select id="ordenarPor" class="select-ordenar">
                    <option value="relevancia">Más relevantes</option>
                    <option value="precio-asc">Precio: menor a mayor</option>
                    <option value="precio-desc">Precio: mayor a menor</option>
                    <option value="nombre-asc">Nombre: A-Z</option>
                    <option value="nombre-desc">Nombre: Z-A</option>
                    <option value="nuevos">Más nuevos</option>
                </select>
            </div>
        </div>

        <div class="productos-grid-container">
            <!-- Columna Izquierda: Filtros -->
            <aside class="filtros-column">
                <div class="filtros-card">
                    <h3 class="filtros-titulo">Filtros</h3>
                    
                    <!-- Buscador -->
                    <div class="filtro-buscar">
                        <input type="text" placeholder="Buscar productos..." class="buscar-input">
                    </div>

                    <!-- Categorías -->
                    <div class="filtro-categorias">
                        <h4 class="filtro-subtitulo">Categorías</h4>
                        <ul class="categorias-lista">
                            <li class="categoria-item">
                                <label class="categoria-label">
                                    <input type="radio" name="categoria" value="todos" checked>
                                    <span class="categoria-texto">Todos los productos</span>
                                </label>
                            </li>
                            <li class="categoria-item">
                                <label class="categoria-label">
                                    <input type="radio" name="categoria" value="laptops">
                                    <span class="categoria-texto">Laptops</span>
                                </label>
                            </li>
                            <li class="categoria-item">
                                <label class="categoria-label">
                                    <input type="radio" name="categoria" value="escritorio">
                                    <span class="categoria-texto">PC de Escritorio</span>
                                </label>
                            </li>
                            <li class="categoria-item">
                                <label class="categoria-label">
                                    <input type="radio" name="categoria" value="gaming">
                                    <span class="categoria-texto">Gaming</span>
                                </label>
                            </li>
                            <li class="categoria-item">
                                <label class="categoria-label">
                                    <input type="radio" name="categoria" value="servidores">
                                    <span class="categoria-texto">Servidores</span>
                                </label>
                            </li>
                            <li class="categoria-item">
                                <label class="categoria-label">
                                    <input type="radio" name="categoria" value="pos">
                                    <span class="categoria-texto">Punto de Venta</span>
                                </label>
                            </li>
                            <li class="categoria-item">
                                <label class="categoria-label">
                                    <input type="radio" name="categoria" value="componentes">
                                    <span class="categoria-texto">Componentes</span>
                                </label>
                            </li>
                            <li class="categoria-item">
                                <label class="categoria-label">
                                    <input type="radio" name="categoria" value="accesorios">
                                    <span class="categoria-texto">Accesorios</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <!-- Precio -->
                    <div class="filtro-precio">
                        <h4 class="filtro-subtitulo">Rango de Precio</h4>
                        <div class="precio-inputs">
                            <input type="number" placeholder="Mínimo" class="precio-min" min="0">
                            <span class="precio-separador">-</span>
                            <input type="number" placeholder="Máximo" class="precio-max" min="0">
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Columna Derecha: Grid de Productos -->
            <div class="productos-column">
                <div class="productos-grid" id="productosGrid">
                    <!-- Los productos se cargarán aquí dinámicamente -->
                    <div class="mensaje-cargando">Cargando productos...</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include '../php/footer.php'; ?>

<!-- Scripts -->
<script src="../js/lupa.js"></script>
</body>
</html>