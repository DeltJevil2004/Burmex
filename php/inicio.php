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

    <!-- Iconos -->
    <link rel="icon" type="image/x-icon" href="../img/img-inicio/logo-icon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/img-inicio/logo-apple-icon.ico">
</head>

<body>
<!-- Navbar -->
<header>
    <?php include '../php/navbar.php'; ?>

</header>

<!-- Hero Banner -->
<section class="hero-banner">
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">Equipo de Cómputo de Última Generación</h1>
            <p class="hero-text">Encuentra las mejores computadoras, laptops, servidores y equipos tecnológicos para tu hogar, oficina o empresa. Calidad garantizada y precios competitivos.</p>
            <div class="hero-buttons">
                <button class="btn btn-primary">Ver Catálogo</button>
                <button class="btn btn-secondary">Solicitar Cotización</button>
            </div>
        </div>
    </div>
</section>

<!-- Sección Categorías -->
<section class="categories-section">
    <div class="categories-container">
        <h2 class="categories-title">Nuestras Categorías</h2>
        <p class="categories-description">
            Explora nuestra amplia gama de equipos de cómputo y encuentra exactamente lo que necesitas para tu negocio o uso personal.
        </p>
        
        <!-- Grid de tarjetas -->
        <div class="categories-grid">
            <!-- Fila 1 -->
            <div class="category-card">
                <div class="card-image">
                    <img src="../img/img-inicio/laptop.png" alt="Laptops">
                </div>
                <!-- Se eliminó el div .card-content que no existía en el HTML original -->
                <h3 class="card-title">Laptops</h3>
                <p class="card-description">Portátiles para trabajo y gaming</p>
                <div class="card-footer">
                    <span class="card-models">150+ modelos</span>
                    <span class="card-arrow">→</span>
                </div>
            </div>
            
            <div class="category-card">
                <div class="card-image">
                    <img src="../img/img-inicio/pc.png" alt="Computadoras de Escritorio">
                </div>
                <h3 class="card-title">Computadoras de Escritorio</h3>
                <p class="card-description">Pcs completas y componentes</p>
                <div class="card-footer">
                    <span class="card-models">200+ configuraciones</span>
                    <span class="card-arrow">→</span>
                </div>
            </div>
            
            <div class="category-card">
                <div class="card-image">
                    <img src="../img/img-inicio/servidores.png" alt="Servidores">
                </div>
                <h3 class="card-title">Servidores</h3>
                <p class="card-description">Soluciones empresariales</p>
                <div class="card-footer">
                    <span class="card-models">50+ soluciones</span>
                    <span class="card-arrow">→</span>
                </div>
            </div>
            
            <!-- Fila 2 -->
            <div class="category-card">
                <div class="card-image">
                    <img src="../img/img-inicio/componentes.png" alt="Accesorios">
                </div>
                <h3 class="card-title">Componentes</h3>
                <p class="card-description">Partes y accesorios</p>
                <div class="card-footer">
                    <span class="card-models">500+ productos</span>
                    <span class="card-arrow">→</span>
                </div>
            </div>
            
            <div class="category-card">
                <div class="card-image">
                    <img src="../img/img-inicio/venta.png" alt="Componentes">
                </div>
                <h3 class="card-title">Puntos de Venta</h3>
                <p class="card-description">Sistema POS completos</p>
                <div class="card-footer">
                    <span class="card-models">30+ sistema</span>
                    <span class="card-arrow">→</span>
                </div>
            </div>
            
            <div class="category-card">
                <div class="card-image">
                    <img src="../img/img-inicio/Accesorios.png" alt="accesorios">
                </div>
                <h3 class="card-title">Accesorios</h3>
                <p class="card-description">Periferico y más</p>
                <div class="card-footer">
                    <span class="card-models">300+ accesorios</span>
                    <span class="card-arrow">→</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección Productos Destacados -->
<section class="featured-section">
    <div class="featured-container">
        <h2 class="featured-title">Productos Destacados</h2>
        <p class="featured-description">
            Descubre nuestros equipos más populares con las mejores especificaciones y precios especiales.
        </p>

        <!-- Grid de productos -->
        <div class="products-grid">
            <!-- Producto 1 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="#" alt="Laptop Gaming">
                </div>
                <div class="product-content">
                    <h3 class="product-name">Laptop Gaming ROG Strix</h3>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                        </div>
                        <span class="rating-number">4.8</span>
                    </div>
                    
                    <div class="product-specs">
                        <span class="spec">RTX 4060</span>
                        <span class="spec">16GB RAM</span>
                        <span class="spec">1TB SSD</span>
                    </div>
                    
                    <div class="product-pricing">
                        <span class="current-price">$24,999</span>
                        <span class="original-price">$29,999</span>
                    </div>
                    
                    <div class="product-actions">
                        <button class="view-btn">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="view-icon">
                        </button>
                        <button class="add-cart-btn">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 2 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="#" alt="PC Escritorio">
                </div>
                <div class="product-content">
                    <h3 class="product-name">PC Gamer Intel i7</h3>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star half">★</span>
                        </div>
                        <span class="rating-number">4.3</span>
                    </div>
                    
                    <div class="product-specs">
                        <span class="spec">i7-13700K</span>
                        <span class="spec">32GB RAM</span>
                        <span class="spec">RTX 4070</span>
                    </div>
                    
                    <div class="product-pricing">
                        <span class="current-price">$32,499</span>
                        <span class="original-price">$36,999</span>
                    </div>
                    
                    <div class="product-actions">
                        <button class="view-btn">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="view-icon">
                        </button>
                        <button class="add-cart-btn">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 3 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="#" alt="Monitor Gaming">
                </div>
                <div class="product-content">
                    <h3 class="product-name">Monitor Curvo 32"</h3>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                        </div>
                        <span class="rating-number">4.9</span>
                    </div>
                    
                    <div class="product-specs">
                        <span class="spec">165Hz</span>
                        <span class="spec">QHD</span>
                        <span class="spec">1ms</span>
                    </div>
                    
                    <div class="product-pricing">
                        <span class="current-price">$7,499</span>
                        <span class="original-price">$8,999</span>
                    </div>
                    
                    <div class="product-actions">
                        <button class="view-btn">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="view-icon">
                        </button>
                        <button class="add-cart-btn">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 4 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="#" alt="Teclado Mecánico">
                </div>
                <div class="product-content">
                    <h3 class="product-name">Teclado Mecánico RGB</h3>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star half">★</span>
                        </div>
                        <span class="rating-number">4.5</span>
                    </div>
                    
                    <div class="product-specs">
                        <span class="spec">Switches Red</span>
                        <span class="spec">RGB</span>
                        <span class="spec">USB-C</span>
                    </div>
                    
                    <div class="product-pricing">
                        <span class="current-price">$1,299</span>
                        <span class="original-price">$1,599</span>
                    </div>
                    
                    <div class="product-actions">
                        <button class="view-btn">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="view-icon">
                        </button>
                        <button class="add-cart-btn">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 5 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="#" alt="Mouse Gaming">
                </div>
                <div class="product-content">
                    <h3 class="product-name">Mouse Gaming Pro</h3>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                        </div>
                        <span class="rating-number">4.7</span>
                    </div>
                    
                    <div class="product-specs">
                        <span class="spec">25,600 DPI</span>
                        <span class="spec">6 Botones</span>
                        <span class="spec">RGB</span>
                    </div>
                    
                    <div class="product-pricing">
                        <span class="current-price">$899</span>
                        <span class="original-price">$1,199</span>
                    </div>
                    
                    <div class="product-actions">
                        <button class="view-btn">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="view-icon">
                        </button>
                        <button class="add-cart-btn">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 6 -->
            <div class="product-card">
                <div class="product-image">
                    <img src="#" alt="Audífonos Gaming">
                </div>
                <div class="product-content">
                    <h3 class="product-name">Audífonos 7.1 Surround</h3>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star half">★</span>
                        </div>
                        <span class="rating-number">4.4</span>
                    </div>
                    
                    <div class="product-specs">
                        <span class="spec">7.1 Virtual</span>
                        <span class="spec">RGB</span>
                        <span class="spec">USB</span>
                    </div>
                    
                    <div class="product-pricing">
                        <span class="current-price">$1,599</span>
                        <span class="original-price">$1,999</span>
                    </div>
                    
                    <div class="product-actions">
                        <button class="view-btn">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="view-icon">
                        </button>
                        <button class="add-cart-btn">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div class="view-all-container">
            <button class="view-all-btn">
                Ver todos los productos
            </button>
        </div>
</section>

<!-- Sección Nuestros Servicios -->
<section class="services-section">
    <div class="services-container">
        <h2 class="services-title">Nuestros Servicios</h2>
        <p class="services-description">
            Ofrecemos servicios integrales para garantizar el mejor rendimiento de tus equipos de cómputo.
        </p>
        
        <!-- Grid de servicios -->
        <div class="services-grid">
            <!-- Servicio 1 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="../img/img-inicio/soporte-tecnico.png" alt="Soporte Técnico 24/7">
                </div>
                <div class="service-content">
                    <h3 class="service-name">Soporte Técnico 24/7</h3>
                    <p class="service-text">Asistencia técnica especializada disponible las 24 horas del día, los 7 días de la semana.</p>
                    
                    <ul class="service-features">
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Soporte remoto
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Diagnóstico gratuito
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Respuesta inmediata
                        </li>
                    </ul>
                    
                    <button class="service-btn">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 2 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="../img/img-inicio/instalacion-configuracion.png" alt="Instalación y Configuración">
                </div>
                <div class="service-content">
                    <h3 class="service-name">Instalación y Configuración</h3>
                    <p class="service-text">Servicio completo de instalación y configuración de equipos en tu oficina o hogar.</p>
                    
                    <ul class="service-features">
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Instalación profesional
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Configuración completa
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Capacitación incluida
                        </li>
                    </ul>
                    
                    <button class="service-btn">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 3 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="../img/img-inicio/mantenimiento-preventivo.png" alt="Mantenimiento Preventivo">
                </div>
                <div class="service-content">
                    <h3 class="service-name">Mantenimiento Preventivo</h3>
                    <p class="service-text">Programas de mantenimiento para mantener tus equipos funcionando de manera óptima.</p>
                    
                    <ul class="service-features">
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Limpieza interna
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Actualización de software
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Revisión de componentes
                        </li>
                    </ul>
                    
                    <button class="service-btn">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 4 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="../img/img-inicio/garantia-extendida.png" alt="Garantía Extendida">
                </div>
                <div class="service-content">
                    <h3 class="service-name">Garantía Extendida</h3>
                    <p class="service-text">Protección adicional para tus equipos con nuestra garantía extendida de hasta 3 años.</p>
                    
                    <ul class="service-features">
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Hasta 3 años
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Reemplazo inmediato
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Sin costo adicional
                        </li>
                    </ul>
                    
                    <button class="service-btn">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 5 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="../img/img-inicio/financiamiento.png" alt="Financiamiento">
                </div>
                <div class="service-content">
                    <h3 class="service-name">Financiamiento</h3>
                    <p class="service-text">Opciones de financiamiento flexibles para empresas y particulares.</p>
                    
                    <ul class="service-features">
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Sin intereses
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Pagos mensuales
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Aprobación rápida
                        </li>
                    </ul>
                    
                    <button class="service-btn">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 6 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="../img/img-inicio/consultoria-it.png" alt="Consultoría IT">
                </div>
                <div class="service-content">
                    <h3 class="service-name">Consultoría IT</h3>
                    <p class="service-text">Asesoría especializada para optimizar la infraestructura tecnológica de tu empresa.</p>
                    
                    <ul class="service-features">
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Análisis completo
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Recomendaciones
                        </li>
                        <li class="feature-item">
                            <span class="check-icon">✓</span>
                            Implementación
                        </li>
                    </ul>
                    
                    <button class="service-btn">Solicitar Servicio</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección Líderes en Tecnología -->
<section class="about-section">
    <div class="about-container">
        <div class="about-content">
            <div class="about-text">
                <h2 class="about-title">Líderes en Tecnología desde 2009</h2>
                <p class="about-description">
                    Somos una empresa especializada en la venta de equipos de cómputo, puntos de venta y soluciones tecnológicas para empresas y particulares. Con más de 15 años de experiencia, nos hemos consolidado como líderes en el mercado mexicano.
                </p>
                <p class="about-description">
                    Nuestro compromiso es ofrecer productos de la más alta calidad, precios competitivos y un servicio al cliente excepcional. Trabajamos con las mejores marcas del mercado para garantizar la satisfacción de nuestros clientes.
                </p>
                
                <!-- Estadísticas -->
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">15+</span>
                        <!-- Cambiado de stat-label a stat-text para coincidir con CSS -->
                        <span class="stat-text">Años de Experiencia</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">10,000+</span>
                        <span class="stat-text">Clientes Satisfechos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-text">Empresas Atendidas</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-text">Soporte Técnico</span>
                    </div>
                </div>
                
                <button class="about-btn">Conoce Más</button>
            </div>
            
            <div class="about-image">
                <img src="../img/img-inicio/empresa-tecnologia.png" alt="Empresa de Tecnología">
            </div>
        </div>
    </div>
</section>

<!-- Sección ¿Por qué elegirnos? -->
<section class="why-choose-section">
    <div class="why-choose-container">
        <h2 class="why-choose-title">¿Por qué elegirnos?</h2>
        
        <!-- Grid de beneficios -->
        <div class="benefits-grid">
            <!-- Beneficio 1 -->
            <div class="benefit-card">
                <div class="benefit-icon">
                    <img src="../img/img-inicio/icono-calidad.png" alt="Calidad Garantizada">
                </div>
                <h3 class="benefit-title">Calidad Garantizada</h3>
                <p class="benefit-text">Trabajamos solo con las mejores marcas del mercado</p>
            </div>
            
            <!-- Beneficio 2 -->
            <div class="benefit-card">
                <div class="benefit-icon">
                    <img src="../img/img-inicio/icono-entrega.png" alt="Entrega Rápida">
                </div>
                <h3 class="benefit-title">Entrega Rápida</h3>
                <p class="benefit-text">Envíos en 24-48 horas a toda la república</p>
            </div>
            
            <!-- Beneficio 3 -->
            <div class="benefit-card">
                <div class="benefit-icon">
                    <img src="../img/img-inicio/icono-garantia.png" alt="Garantía Completa">
                </div>
                <h3 class="benefit-title">Garantía Completa</h3>
                <p class="benefit-text">Todos nuestros productos incluyen garantía oficial</p>
            </div>
            
            <!-- Beneficio 4 -->
            <div class="benefit-card">
                <div class="benefit-icon">
                    <img src="../img/img-inicio/icono-soporte.png" alt="Soporte Experto">
                </div>
                <h3 class="benefit-title">Soporte Experto</h3>
                <p class="benefit-text">Equipo técnico especializado a tu disposición</p>
            </div>
        </div>
    </div>
</section>

<!-- Sección Contáctanos -->
<section class="contact-section" id="contacto">
    <div class="contact-container">
        <h2 class="contact-title">Contáctanos</h2>
        <p class="contact-subtitle">
            ¿Necesitas una cotización o tienes alguna pregunta? Estamos aquí para ayudarte.
        </p>
        
        <div class="contact-content">
            <!-- Información de contacto - Izquierda -->
            <div class="contact-info">
                <h3 class="info-title">Información de contacto</h3>
                
                <div class="contact-list">
                    <!-- Dirección -->
                    <div class="contact-item">
                        <div class="contact-icon">
                            <img src="../img/img-inicio/icono-direccion.png" alt="Dirección">
                        </div>
                        <div class="contact-details">
                            <h4 class="detail-title">Dirección</h4>
                            <p class="detail-text">Av. Tecnología 123, Col. Innovación<br>Ciudad de México, CDMX 01234</p>
                        </div>
                    </div>
                    
                    <!-- Teléfono -->
                    <div class="contact-item">
                        <div class="contact-icon">
                            <img src="../img/img-inicio/icono-telefono.png" alt="Teléfono">
                        </div>
                        <div class="contact-details">
                            <h4 class="detail-title">Teléfono</h4>
                            <p class="detail-text">+52 55 1234 5678<br>+52 55 8765 4321</p>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="contact-item">
                        <div class="contact-icon">
                            <img src="../img/img-inicio/icono-email.png" alt="Email">
                        </div>
                        <div class="contact-details">
                            <h4 class="detail-title">Email</h4>
                            <p class="detail-text">ventas@techstore.com<br>soporte@techstore.com</p>
                        </div>
                    </div>
                    
                    <!-- Horarios -->
                    <div class="contact-item">
                        <div class="contact-icon">
                            <img src="../img/img-inicio/icono-horario.png" alt="Horarios">
                        </div>
                        <div class="contact-details">
                            <h4 class="detail-title">Horarios</h4>
                            <p class="detail-text">Lunes a Viernes: 9:00 AM - 7:00 PM<br>Sábados: 10:00 AM - 4:00 PM</p>
                        </div>
                    </div>
                </div>
                
                <!-- Redes sociales -->
                <div class="social-section">
                    <h4 class="social-title">Síguenos</h4>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/share/1At2PsGGd3/?mibextid=wwXIfr" class="social-icon">
                            <img src="../img/img-inicio/Facebook.png" alt="Facebook">
                        </a>
                        <a href="https://x.com/burmex_mx?s=21&t=rP-c6XcC5WKNH_vZobNrEg" class="social-icon">
                            <img src="../img/img-inicio/X.png" alt="X">
                        </a>
                        <a href="https://www.linkedin.com/company/burmex" class="social-icon">
                            <img src="../img/img-inicio/Linkedin.png" alt="LinkedIn">
                        </a>
                        <a href="https://www.instagram.com/burmex.mx?igsh=YWN1cDVhOXIzdmw2" class="social-icon">
                            <img src="../img/img-inicio/Instagram.png" alt="Instagram">
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Formulario -->
            <div class="contact-form">
                <h3 class="form-title">Solicitar Cotización</h3>
                <form class="quote-form">
                    <!-- Fila 1: Nombre y Email -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" id="nombre" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-input" required>
                        </div>
                    </div>
                    
                    <!-- Fila 2: Teléfono y Empresa -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" id="telefono" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="empresa" class="form-label">Empresa</label>
                            <input type="text" id="empresa" class="form-input">
                        </div>
                    </div>
                    
                    <!-- Tipo de servicio -->
                    <div class="form-group">
                        <label for="servicio" class="form-label">Tipo de servicio</label>
                        <select id="servicio" class="form-select" required>
                            <option value="">Selecciona un servicio</option>
                            <option value="equipos">Compra de equipos</option>
                            <option value="soporte">Soporte técnico</option>
                            <option value="instalacion">Instalación y configuración</option>
                            <option value="mantenimiento">Mantenimiento preventivo</option>
                            <option value="consultoria">Consultoría IT</option>
                            <option value="financiamiento">Financiamiento</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    
                    <!-- Mensaje -->
                    <div class="form-group">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea id="mensaje" class="form-textarea" rows="4" placeholder="Describe tus necesidades..."></textarea>
                    </div>
                    
                    <!-- Botón -->
                    <button type="submit" class="submit-btn">Enviar Solicitud</button>
                </form>
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