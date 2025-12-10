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
    <link rel="stylesheet" href="../styles/css/nuestroequipo.css"/>
    <link rel="stylesheet" href="../styles/css/nuestroequipo-movil.css"/>

    <!-- Iconos -->
    <link rel="icon" type="image/x-icon" href="../img/img-inicio/logo-icon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/img-inicio/logo-apple-icon.ico">

</head>
<body>
    <!-- Navbar -->
<header>

   <?php include '../php/navbar.php'; ?>

</header>

<!-- Hero Banner Nuestro Equipo -->
<section class="hero-banner-equipo">
    <div class="hero-container-equipo">
        <div class="hero-content-equipo">
            <h1 class="hero-title-equipo">Únete a nuestro equipo</h1>
            <p class="hero-text-equipo">Forma parte de una empresa líder en tecnología. Ofrecemos un ambiente de trabajo
            dinámico, oportunidades de crecimiento y los mejores beneficios del mercado.</p>
        </div>
    </div>
</section>

<!-- Sección ¿Por qué trabajar con nosotros? -->
<section class="porque-trabajar">
    <div class="container">
        <h2 class="section-title">¿Por qué trabajar con nosotros?</h2>
        <p class="section-description">Ofrecemos más que un trabajo, te brindamos una carrera profesional con beneficios
        excepcionales.</p>
        
        <div class="beneficios-grid">
            <!-- Beneficio 1 -->
            <div class="beneficio-item">
                <div class="beneficio-icon">
                    <img src="../img/img-equipo/salario-icono.png" alt="Salario Competitivo">
                </div>
                <h3 class="beneficio-title">Salario Competitivo</h3>
                <p class="beneficio-text">Sueldos por encima del promedio del
                mercado con revisiones anuales.</p>
            </div>
            
            <!-- Beneficio 2 -->
            <div class="beneficio-item">
                <div class="beneficio-icon">
                    <img src="../img/img-equipo/seguro-icono.png" alt="Seguro Médico">
                </div>
                <h3 class="beneficio-title">Seguro Médico</h3>
                <p class="beneficio-text">Seguro de gastos médicos mayores
                para ti y tu familia.</p>
            </div>
            
            <!-- Beneficio 3 -->
            <div class="beneficio-item">
                <div class="beneficio-icon">
                    <img src="../img/img-equipo/capacitacion-icono.png" alt="Capacitación">
                </div>
                <h3 class="beneficio-title">Capacitación</h3>
                <p class="beneficio-text">Programas de capacitación continua y
                certificaciones profesionales.</p>
            </div>
            
            <!-- Beneficio 4 -->
            <div class="beneficio-item">
                <div class="beneficio-icon">
                    <img src="../img/img-equipo/flexibilidad-icono.png" alt="Flexibilidad">
                </div>
                <h3 class="beneficio-title">Flexibilidad</h3>
                <p class="beneficio-text">Horarios flexibles y opciones de trabajo
                híbrido.</p>
            </div>
        </div>
    </div>
</section>

<!-- Sección Vacantes Disponibles -->
<section class="vacantes-section">
    <div class="container">
        <h2 class="section-title">Vacantes disponibles</h2>
        <p class="section-description">Encuentra la oportunidad perfecta para tu carrera profesional.</p>
        
        <!-- Filtros de categorías -->
        <div class="categorias-filtro">
            <button class="filtro-btn active" data-categoria="todos">Todos</button>
            <button class="filtro-btn" data-categoria="tecnologias">Tecnologías</button>
            <button class="filtro-btn" data-categoria="ventas">Ventas</button>
            <button class="filtro-btn" data-categoria="soporte">Soporte Técnico</button>
            <button class="filtro-btn" data-categoria="marketing">Marketing</button>
            <button class="filtro-btn" data-categoria="finanzas">Finanzas</button>
        </div>
        
        <!-- Grid de vacantes -->
        <div class="vacantes-grid">
            <!-- Las vacantes se cargarán aquí -->
            <div class="vacantes-placeholder">
                <p class="placeholder-text">Próximamente</p>
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