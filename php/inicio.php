<!DOCTYPE html>
<html lang="es">
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

<!-- Banner principal -->
<section class="banner-principal">
    <div class="contenedor-banner">
        <div class="contenido-banner">
            <h1 class="titulo-banner">Equipo de Cómputo de Última Generación</h1>
            <p class="texto-banner">Encuentra las mejores computadoras, laptops, servidores y equipos tecnológicos para tu hogar, oficina o empresa. Calidad garantizada y precios competitivos.</p>
            <div class="botones-banner">
                <button class="boton boton-principal">Ver Catálogo</button>
                <button class="boton boton-secundario">Solicitar Cotización</button>
            </div>
        </div>
    </div>
</section>

<!-- Sección Categorías -->
<section class="seccion-categorias">
    <div class="contenedor-categorias">
        <h2 class="titulo-categorias">Nuestras Categorías</h2>
        <p class="descripcion-categorias">
            Explora nuestra amplia gama de equipos de cómputo y encuentra exactamente lo que necesitas para tu negocio o uso personal.
        </p>
        
        <!-- Cuadrícula tarjetas -->
        <div class="cuadricula-categorias">
            <!-- Fila 1 -->
            <div class="tarjeta-categoria">
                <div class="imagen-categoria">
                    <img src="../img/img-inicio/laptop.png" alt="Laptops">
                </div>
                <h3 class="titulo-tarjeta">Laptops</h3>
                <p class="descripcion-tarjeta">Portátiles para trabajo y gaming</p>
                <div class="pie-tarjeta">
                    <span class="modelos-tarjeta">150+ modelos</span>
                    <span class="flecha-tarjeta">→</span>
                </div>
            </div>
            
            <div class="tarjeta-categoria">
                <div class="imagen-categoria">
                    <img src="../img/img-inicio/pc.png" alt="Computadoras de Escritorio">
                </div>
                <h3 class="titulo-tarjeta">Computadoras de Escritorio</h3>
                <p class="descripcion-tarjeta">Pcs completas y componentes</p>
                <div class="pie-tarjeta">
                    <span class="modelos-tarjeta">200+ configuraciones</span>
                    <span class="flecha-tarjeta">→</span>
                </div>
            </div>
            
            <div class="tarjeta-categoria">
                <div class="imagen-categoria">
                    <img src="../img/img-inicio/servidores.png" alt="Servidores">
                </div>
                <h3 class="titulo-tarjeta">Servidores</h3>
                <p class="descripcion-tarjeta">Soluciones empresariales</p>
                <div class="pie-tarjeta">
                    <span class="modelos-tarjeta">50+ soluciones</span>
                    <span class="flecha-tarjeta">→</span>
                </div>
            </div>
            
            <!-- Fila 2 -->
            <div class="tarjeta-categoria">
                <div class="imagen-categoria">
                    <img src="../img/img-inicio/componentes.png" alt="Accesorios">
                </div>
                <h3 class="titulo-tarjeta">Componentes</h3>
                <p class="descripcion-tarjeta">Partes y accesorios</p>
                <div class="pie-tarjeta">
                    <span class="modelos-tarjeta">500+ productos</span>
                    <span class="flecha-tarjeta">→</span>
                </div>
            </div>
            
            <div class="tarjeta-categoria">
                <div class="imagen-categoria">
                    <img src="../img/img-inicio/venta.png" alt="Componentes">
                </div>
                <h3 class="titulo-tarjeta">Puntos de Venta</h3>
                <p class="descripcion-tarjeta">Sistema POS completos</p>
                <div class="pie-tarjeta">
                    <span class="modelos-tarjeta">30+ sistema</span>
                    <span class="flecha-tarjeta">→</span>
                </div>
            </div>
            
            <div class="tarjeta-categoria">
                <div class="imagen-categoria">
                    <img src="../img/img-inicio/Accesorios.png" alt="accesorios">
                </div>
                <h3 class="titulo-tarjeta">Accesorios</h3>
                <p class="descripcion-tarjeta">Periferico y más</p>
                <div class="pie-tarjeta">
                    <span class="modelos-tarjeta">300+ accesorios</span>
                    <span class="flecha-tarjeta">→</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección Productos Destacados -->
<section class="seccion-destacados">
    <div class="contenedor-destacados">
        <h2 class="titulo-destacados">Productos Destacados</h2>
        <p class="descripcion-destacados">
            Descubre nuestros equipos más populares con las mejores especificaciones y precios especiales.
        </p>

        <!-- Cuadrícula productos -->
        <div class="cuadricula-productos">
            <!-- Producto 1 -->
            <div class="tarjeta-producto">
                <div class="imagen-producto">
                    <img src="#" alt="Laptop Gaming">
                </div>
                <div class="contenido-producto">
                    <h3 class="nombre-producto">Laptop Gaming ROG Strix</h3>
                    
                    <div class="calificacion-producto">
                        <div class="estrellas">
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                        </div>
                        <span class="numero-calificacion">4.8</span>
                    </div>
                    
                    <div class="especificaciones-producto">
                        <span class="especificacion">RTX 4060</span>
                        <span class="especificacion">16GB RAM</span>
                        <span class="especificacion">1TB SSD</span>
                    </div>
                    
                    <div class="precios-producto">
                        <span class="precio-actual">$24,999</span>
                        <span class="precio-original">$29,999</span>
                    </div>
                    
                    <div class="acciones-producto">
                        <button class="boton-ver">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="icono-ver">
                        </button>
                        <button class="boton-carrito">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 2 -->
            <div class="tarjeta-producto">
                <div class="imagen-producto">
                    <img src="#" alt="PC Escritorio">
                </div>
                <div class="contenido-producto">
                    <h3 class="nombre-producto">PC Gamer Intel i7</h3>
                    
                    <div class="calificacion-producto">
                        <div class="estrellas">
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella mitad">★</span>
                        </div>
                        <span class="numero-calificacion">4.3</span>
                    </div>
                    
                    <div class="especificaciones-producto">
                        <span class="especificacion">i7-13700K</span>
                        <span class="especificacion">32GB RAM</span>
                        <span class="especificacion">RTX 4070</span>
                    </div>
                    
                    <div class="precios-producto">
                        <span class="precio-actual">$32,499</span>
                        <span class="precio-original">$36,999</span>
                    </div>
                    
                    <div class="acciones-producto">
                        <button class="boton-ver">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="icono-ver">
                        </button>
                        <button class="boton-carrito">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 3 -->
            <div class="tarjeta-producto">
                <div class="imagen-producto">
                    <img src="#" alt="Monitor Gaming">
                </div>
                <div class="contenido-producto">
                    <h3 class="nombre-producto">Monitor Curvo 32"</h3>
                    
                    <div class="calificacion-producto">
                        <div class="estrellas">
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                        </div>
                        <span class="numero-calificacion">4.9</span>
                    </div>
                    
                    <div class="especificaciones-producto">
                        <span class="especificacion">165Hz</span>
                        <span class="especificacion">QHD</span>
                        <span class="especificacion">1ms</span>
                    </div>
                    
                    <div class="precios-producto">
                        <span class="precio-actual">$7,499</span>
                        <span class="precio-original">$8,999</span>
                    </div>
                    
                    <div class="acciones-producto">
                        <button class="boton-ver">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="icono-ver">
                        </button>
                        <button class="boton-carrito">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 4 -->
            <div class="tarjeta-producto">
                <div class="imagen-producto">
                    <img src="#" alt="Teclado Mecánico">
                </div>
                <div class="contenido-producto">
                    <h3 class="nombre-producto">Teclado Mecánico RGB</h3>
                    
                    <div class="calificacion-producto">
                        <div class="estrellas">
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella mitad">★</span>
                        </div>
                        <span class="numero-calificacion">4.5</span>
                    </div>
                    
                    <div class="especificaciones-producto">
                        <span class="especificacion">Switches Red</span>
                        <span class="especificacion">RGB</span>
                        <span class="especificacion">USB-C</span>
                    </div>
                    
                    <div class="precios-producto">
                        <span class="precio-actual">$1,299</span>
                        <span class="precio-original">$1,599</span>
                    </div>
                    
                    <div class="acciones-producto">
                        <button class="boton-ver">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="icono-ver">
                        </button>
                        <button class="boton-carrito">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 5 -->
            <div class="tarjeta-producto">
                <div class="imagen-producto">
                    <img src="#" alt="Mouse Gaming">
                </div>
                <div class="contenido-producto">
                    <h3 class="nombre-producto">Mouse Gaming Pro</h3>
                    
                    <div class="calificacion-producto">
                        <div class="estrellas">
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                        </div>
                        <span class="numero-calificacion">4.7</span>
                    </div>
                    
                    <div class="especificaciones-producto">
                        <span class="especificacion">25,600 DPI</span>
                        <span class="especificacion">6 Botones</span>
                        <span class="especificacion">RGB</span>
                    </div>
                    
                    <div class="precios-producto">
                        <span class="precio-actual">$899</span>
                        <span class="precio-original">$1,199</span>
                    </div>
                    
                    <div class="acciones-producto">
                        <button class="boton-ver">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="icono-ver">
                        </button>
                        <button class="boton-carrito">Agregar al carrito</button>
                    </div>
                </div>
            </div>
            
            <!-- Producto 6 -->
            <div class="tarjeta-producto">
                <div class="imagen-producto">
                    <img src="#" alt="Audífonos Gaming">
                </div>
                <div class="contenido-producto">
                    <h3 class="nombre-producto">Audífonos 7.1 Surround</h3>
                    
                    <div class="calificacion-producto">
                        <div class="estrellas">
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella">★</span>
                            <span class="estrella mitad">★</span>
                        </div>
                        <span class="numero-calificacion">4.4</span>
                    </div>
                    
                    <div class="especificaciones-producto">
                        <span class="especificacion">7.1 Virtual</span>
                        <span class="especificacion">RGB</span>
                        <span class="especificacion">USB</span>
                    </div>
                    
                    <div class="precios-producto">
                        <span class="precio-actual">$1,599</span>
                        <span class="precio-original">$1,999</span>
                    </div>
                    
                    <div class="acciones-producto">
                        <button class="boton-ver">
                            <img src="../img/img-inicio/ojo.svg" alt="Ver producto" class="icono-ver">
                        </button>
                        <button class="boton-carrito">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div class="contenedor-ver-todos">
            <button class="boton-ver-todos">
                Ver todos los productos
            </button>
        </div>
</section>

<!-- Sección Servicios -->
<section class="seccion-servicios">
    <div class="contenedor-servicios">
        <h2 class="titulo-servicios">Nuestros Servicios</h2>
        <p class="descripcion-servicios">
            Ofrecemos servicios integrales para garantizar el mejor rendimiento de tus equipos de cómputo.
        </p>
        
        <!-- Cuadrícula servicios -->
        <div class="cuadricula-servicios">
            <!-- Servicio 1 -->
            <div class="tarjeta-servicio">
                <div class="imagen-servicio">
                    <img src="../img/img-inicio/soporte-tecnico.png" alt="Soporte Técnico 24/7">
                </div>
                <div class="contenido-servicio">
                    <h3 class="nombre-servicio">Soporte Técnico 24/7</h3>
                    <p class="texto-servicio">Asistencia técnica especializada disponible las 24 horas del día, los 7 días de la semana.</p>
                    
                    <ul class="caracteristicas-servicio">
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Soporte remoto
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Diagnóstico gratuito
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Respuesta inmediata
                        </li>
                    </ul>
                    
                    <button class="boton-servicio">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 2 -->
            <div class="tarjeta-servicio">
                <div class="imagen-servicio">
                    <img src="../img/img-inicio/instalacion-configuracion.png" alt="Instalación y Configuración">
                </div>
                <div class="contenido-servicio">
                    <h3 class="nombre-servicio">Instalación y Configuración</h3>
                    <p class="texto-servicio">Servicio completo de instalación y configuración de equipos en tu oficina o hogar.</p>
                    
                    <ul class="caracteristicas-servicio">
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Instalación profesional
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Configuración completa
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Capacitación incluida
                        </li>
                    </ul>
                    
                    <button class="boton-servicio">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 3 -->
            <div class="tarjeta-servicio">
                <div class="imagen-servicio">
                    <img src="../img/img-inicio/mantenimiento-preventivo.png" alt="Mantenimiento Preventivo">
                </div>
                <div class="contenido-servicio">
                    <h3 class="nombre-servicio">Mantenimiento Preventivo</h3>
                    <p class="texto-servicio">Programas de mantenimiento para mantener tus equipos funcionando de manera óptima.</p>
                    
                    <ul class="caracteristicas-servicio">
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Limpieza interna
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Actualización de software
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Revisión de componentes
                        </li>
                    </ul>
                    
                    <button class="boton-servicio">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 4 -->
            <div class="tarjeta-servicio">
                <div class="imagen-servicio">
                    <img src="../img/img-inicio/garantia-extendida.png" alt="Garantía Extendida">
                </div>
                <div class="contenido-servicio">
                    <h3 class="nombre-servicio">Garantía Extendida</h3>
                    <p class="texto-servicio">Protección adicional para tus equipos con nuestra garantía extendida de hasta 3 años.</p>
                    
                    <ul class="caracteristicas-servicio">
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Hasta 3 años
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Reemplazo inmediato
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Sin costo adicional
                        </li>
                    </ul>
                    
                    <button class="boton-servicio">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 5 -->
            <div class="tarjeta-servicio">
                <div class="imagen-servicio">
                    <img src="../img/img-inicio/financiamiento.png" alt="Financiamiento">
                </div>
                <div class="contenido-servicio">
                    <h3 class="nombre-servicio">Financiamiento</h3>
                    <p class="texto-servicio">Opciones de financiamiento flexibles para empresas y particulares.</p>
                    
                    <ul class="caracteristicas-servicio">
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Sin intereses
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Pagos mensuales
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Aprobación rápida
                        </li>
                    </ul>
                    
                    <button class="boton-servicio">Solicitar Servicio</button>
                </div>
            </div>
            
            <!-- Servicio 6 -->
            <div class="tarjeta-servicio">
                <div class="imagen-servicio">
                    <img src="../img/img-inicio/consultoria-it.png" alt="Consultoría IT">
                </div>
                <div class="contenido-servicio">
                    <h3 class="nombre-servicio">Consultoría IT</h3>
                    <p class="texto-servicio">Asesoría especializada para optimizar la infraestructura tecnológica de tu empresa.</p>
                    
                    <ul class="caracteristicas-servicio">
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Análisis completo
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Recomendaciones
                        </li>
                        <li class="item-caracteristica">
                            <span class="icono-check">✓</span>
                            Implementación
                        </li>
                    </ul>
                    
                    <button class="boton-servicio">Solicitar Servicio</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección Líderes en Tecnología -->
<section class="seccion-nosotros">
    <div class="contenedor-nosotros">
        <div class="contenido-nosotros">
            <div class="texto-nosotros">
                <h2 class="titulo-nosotros">Líderes en Tecnología desde 2009</h2>
                <p class="descripcion-nosotros">
                    Somos una empresa especializada en la venta de equipos de cómputo, puntos de venta y soluciones tecnológicas para empresas y particulares. Con más de 15 años de experiencia, nos hemos consolidado como líderes en el mercado mexicano.
                </p>
                <p class="descripcion-nosotros">
                    Nuestro compromiso es ofrecer productos de la más alta calidad, precios competitivos y un servicio al cliente excepcional. Trabajamos con las mejores marcas del mercado para garantizar la satisfacción de nuestros clientes.
                </p>
                
                <!-- Estadísticas -->
                <div class="cuadricula-estadisticas">
                    <div class="item-estadistica">
                        <span class="numero-estadistica">15+</span>
                        <span class="texto-estadistica">Años de Experiencia</span>
                    </div>
                    <div class="item-estadistica">
                        <span class="numero-estadistica">10,000+</span>
                        <span class="texto-estadistica">Clientes Satisfechos</span>
                    </div>
                    <div class="item-estadistica">
                        <span class="numero-estadistica">500+</span>
                        <span class="texto-estadistica">Empresas Atendidas</span>
                    </div>
                    <div class="item-estadistica">
                        <span class="numero-estadistica">24/7</span>
                        <span class="texto-estadistica">Soporte Técnico</span>
                    </div>
                </div>
                
                <button class="boton-nosotros">Conoce Más</button>
            </div>
            
            <div class="imagen-nosotros">
                <img src="../img/img-inicio/empresa-tecnologia.png" alt="Empresa de Tecnología">
            </div>
        </div>
    </div>
</section>

<!-- Sección Beneficios -->
<section class="seccion-beneficios">
    <div class="contenedor-beneficios">
        <h2 class="titulo-beneficios">¿Por qué elegirnos?</h2>
        
        <!-- Cuadrícula beneficios -->
        <div class="cuadricula-beneficios">
            <!-- Beneficio 1 -->
            <div class="tarjeta-beneficio">
                <div class="icono-beneficio">
                    <img src="../img/img-inicio/icono-calidad.png" alt="Calidad Garantizada">
                </div>
                <h3 class="titulo-beneficio">Calidad Garantizada</h3>
                <p class="texto-beneficio">Trabajamos solo con las mejores marcas del mercado</p>
            </div>
            
            <!-- Beneficio 2 -->
            <div class="tarjeta-beneficio">
                <div class="icono-beneficio">
                    <img src="../img/img-inicio/icono-entrega.png" alt="Entrega Rápida">
                </div>
                <h3 class="titulo-beneficio">Entrega Rápida</h3>
                <p class="texto-beneficio">Envíos en 24-48 horas a toda la república</p>
            </div>
            
            <!-- Beneficio 3 -->
            <div class="tarjeta-beneficio">
                <div class="icono-beneficio">
                    <img src="../img/img-inicio/icono-garantia.png" alt="Garantía Completa">
                </div>
                <h3 class="titulo-beneficio">Garantía Completa</h3>
                <p class="texto-beneficio">Todos nuestros productos incluyen garantía oficial</p>
            </div>
            
            <!-- Beneficio 4 -->
            <div class="tarjeta-beneficio">
                <div class="icono-beneficio">
                    <img src="../img/img-inicio/icono-soporte.png" alt="Soporte Experto">
                </div>
                <h3 class="titulo-beneficio">Soporte Experto</h3>
                <p class="texto-beneficio">Equipo técnico especializado a tu disposición</p>
            </div>
        </div>
    </div>
</section>

<!-- Sección Contacto -->
<section class="seccion-contacto" id="contacto">
    <div class="contenedor-contacto">
        <h2 class="titulo-contacto">Contáctanos</h2>
        <p class="subtitulo-contacto">
            ¿Necesitas una cotización o tienes alguna pregunta? Estamos aquí para ayudarte.
        </p>
        
        <div class="contenido-contacto">
            <!-- Información contacto - Izquierda -->
            <div class="info-contacto">
                <h3 class="titulo-info">Información de contacto</h3>
                
                <div class="lista-contacto">
                    <!-- Dirección -->
                    <div class="item-contacto">
                        <div class="icono-contacto">
                            <img src="../img/img-inicio/icono-direccion.png" alt="Dirección">
                        </div>
                        <div class="detalles-contacto">
                            <h4 class="titulo-detalle">Dirección</h4>
                            <p class="texto-detalle">Av. Tecnología 123, Col. Innovación<br>Ciudad de México, CDMX 01234</p>
                        </div>
                    </div>
                    
                    <!-- Teléfono -->
                    <div class="item-contacto">
                        <div class="icono-contacto">
                            <img src="../img/img-inicio/icono-telefono.png" alt="Teléfono">
                        </div>
                        <div class="detalles-contacto">
                            <h4 class="titulo-detalle">Teléfono</h4>
                            <p class="texto-detalle">+52 55 1234 5678<br>+52 55 8765 4321</p>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="item-contacto">
                        <div class="icono-contacto">
                            <img src="../img/img-inicio/icono-email.png" alt="Email">
                        </div>
                        <div class="detalles-contacto">
                            <h4 class="titulo-detalle">Email</h4>
                            <p class="texto-detalle">ventas@techstore.com<br>soporte@techstore.com</p>
                        </div>
                    </div>
                    
                    <!-- Horarios -->
                    <div class="item-contacto">
                        <div class="icono-contacto">
                            <img src="../img/img-inicio/icono-horario.png" alt="Horarios">
                        </div>
                        <div class="detalles-contacto">
                            <h4 class="titulo-detalle">Horarios</h4>
                            <p class="texto-detalle">Lunes a Viernes: 9:00 AM - 7:00 PM<br>Sábados: 10:00 AM - 4:00 PM</p>
                        </div>
                    </div>
                </div>
                
                <!-- Redes sociales -->
                <div class="seccion-redes">
                    <h4 class="titulo-redes">Síguenos</h4>
                    <div class="iconos-redes">
                        <a href="https://www.facebook.com/share/1At2PsGGd3/?mibextid=wwXIfr" class="icono-red">
                            <img src="../img/img-inicio/Facebook.png" alt="Facebook">
                        </a>
                        <a href="https://x.com/burmex_mx?s=21&t=rP-c6XcC5WKNH_vZobNrEg" class="icono-red">
                            <img src="../img/img-inicio/X.png" alt="X">
                        </a>
                        <a href="https://www.linkedin.com/company/burmex" class="icono-red">
                            <img src="../img/img-inicio/Linkedin.png" alt="LinkedIn">
                        </a>
                        <a href="https://www.instagram.com/burmex.mx?igsh=YWN1cDVhOXIzdmw2" class="icono-red">
                            <img src="../img/img-inicio/Instagram.png" alt="Instagram">
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Formulario -->
            <div class="formulario-contacto">
                <h3 class="titulo-formulario">Solicitar Cotización</h3>
                <form class="formulario-cotizacion">
                    <!-- Fila 1: Nombre y Email -->
                    <div class="fila-formulario">
                        <div class="grupo-formulario">
                            <label for="nombre" class="etiqueta-formulario">Nombre completo</label>
                            <input type="text" id="nombre" class="entrada-formulario" required>
                        </div>
                        <div class="grupo-formulario">
                            <label for="email" class="etiqueta-formulario">Email</label>
                            <input type="email" id="email" class="entrada-formulario" required>
                        </div>
                    </div>
                    
                    <!-- Fila 2: Teléfono y Empresa -->
                    <div class="fila-formulario">
                        <div class="grupo-formulario">
                            <label for="telefono" class="etiqueta-formulario">Teléfono</label>
                            <input type="tel" id="telefono" class="entrada-formulario" required>
                        </div>
                        <div class="grupo-formulario">
                            <label for="empresa" class="etiqueta-formulario">Empresa</label>
                            <input type="text" id="empresa" class="entrada-formulario">
                        </div>
                    </div>
                    
                    <!-- Tipo de servicio -->
                    <div class="grupo-formulario">
                        <label for="servicio" class="etiqueta-formulario">Tipo de servicio</label>
                        <select id="servicio" class="seleccion-formulario" required>
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
                    <div class="grupo-formulario">
                        <label for="mensaje" class="etiqueta-formulario">Mensaje</label>
                        <textarea id="mensaje" class="area-formulario" rows="4" placeholder="Describe tus necesidades..."></textarea>
                    </div>
                    
                    <!-- Botón -->
                    <button type="submit" class="boton-enviar">Enviar Solicitud</button>
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