<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burmex</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="../styles/css/footer.css"/>
    <link rel="stylesheet" href="../styles/css/navbar.css"/>
    <link rel="stylesheet" href="../styles/css/inicio.css"/>
    <link rel="stylesheet" href="../styles/css/inicio-movil.css"/>
    <link rel="stylesheet" href="../styles/css/cotizar.css"/>
    <link rel="stylesheet" href="../styles/css/cotizar-movil.css"/>

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
<section class="banner-principal-cotizar">
    <div class="contenedor-banner-cotizar">
        <div class="contenido-banner-cotizar">
            <h1 class="titulo-banner-cotizar">Solicitar Cotización</h1>
            <p class="texto-banner-cotizar">Obtén una cotización personalizada para tus necesidades tecnológicas. Respuesta en menos de 24 horas.</p>
        </div>
    </div>
</section>

<!-- Formulario de Cotización -->
<section class="seccion-formulario">
    <div class="contenedor-principal">
        <div class="rejilla-formulario">
            
            <!-- Columna izquierda: Información -->
            <div class="columna-info">
                <div class="tarjeta-info">
                    <h3 class="titulo-info">¿Por qué cotizar con nosotros?</h3>
                    
                    <!-- Beneficios -->
                    <div class="lista-beneficios">
                        <div class="item-beneficio">
                            <div class="icono-beneficio">
                                <img src="/img/img-cotizar/icono-rapido.png" alt="Respuesta Rápida">
                            </div>
                            <div class="contenido-beneficio">
                                <h4 class="titulo-beneficio">Respuesta Rápida</h4>
                                <p class="texto-beneficio">Cotización en menos de 24 horas</p>
                            </div>
                        </div>
                        
                        <div class="item-beneficio">
                            <div class="icono-beneficio">
                                <img src="/img/img-cotizar/icono-precio.png" alt="Mejores Precios">
                            </div>
                            <div class="contenido-beneficio">
                                <h4 class="titulo-beneficio">Mejores Precios</h4>
                                <p class="texto-beneficio">Precios competitivos del mercado</p>
                            </div>
                        </div>
                        
                        <div class="item-beneficio">
                            <div class="icono-beneficio">
                                <img src="/img/img-cotizar/icono-asesoria.png" alt="Asesoría Experta">
                            </div>
                            <div class="contenido-beneficio">
                                <h4 class="titulo-beneficio">Asesoría Experta</h4>
                                <p class="texto-beneficio">Recomendaciones personalizadas</p>
                            </div>
                        </div>
                        
                        <div class="item-beneficio">
                            <div class="icono-beneficio">
                                <img src="/img/img-cotizar/icono-garantia.png" alt="Garantía Total">
                            </div>
                            <div class="contenido-beneficio">
                                <h4 class="titulo-beneficio">Garantía Total</h4>
                                <p class="texto-beneficio">Productos con garantía oficial</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contacto -->
                    <div class="caja-contacto">
                        <h4 class="titulo-contacto">Contacto Directo</h4>
                        <div class="info-contacto">
                            <div class="item-contacto">
                                <div class="icono-contacto">
                                    <img src="/img/img-cotizar/icono-telefono.png" alt="Teléfono">
                                </div>
                                <span class="texto-contacto">+52 55 1234 5678</span>
                            </div>
                            <div class="item-contacto">
                                <div class="icono-contacto">
                                    <img src="/img/img-cotizar/icono-email.png" alt="Email">
                                </div>
                                <span class="texto-contacto">ventas@burmex.com</span>
                            </div>
                            <div class="item-contacto">
                                <div class="icono-contacto">
                                    <img src="/img/img-cotizar/icono-horario.png" alt="Horario">
                                </div>
                                <span class="texto-contacto">Lun-Vie: 9am - 6pm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Columna derecha: Formulario -->
            <div class="columna-formulario">
                <div class="contenedor-formulario">
                    <form class="formulario-cotizacion" id="cotizacionForm">
                        
                        <!-- Fila 1 -->
                        <div class="fila-formulario">
                            <div class="grupo-formulario">
                                <label for="nombre">Nombre Completo *</label>
                                <input type="text" id="nombre" name="nombre" required placeholder="Ingresa tu nombre completo">
                            </div>
                            <div class="grupo-formulario">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required placeholder="ejemplo@empresa.com">
                            </div>
                        </div>

                        <!-- Fila 2 -->
                        <div class="fila-formulario">
                            <div class="grupo-formulario">
                                <label for="telefono">Teléfono *</label>
                                <input type="tel" id="telefono" name="telefono" required placeholder="+52 (xxx) xxx-xxxx">
                            </div>
                            <div class="grupo-formulario">
                                <label for="empresa">Empresa</label>
                                <input type="text" id="empresa" name="empresa" placeholder="Nombre de tu empresa">
                            </div>
                        </div>

                        <!-- Fila 3 -->
                        <div class="fila-formulario">
                            <div class="grupo-formulario">
                                <label for="tipoCliente">Tipo de Cliente *</label>
                                <select id="tipoCliente" name="tipoCliente" required>
                                    <option value="">Selecciona una opción</option>
                                    <option value="empresa">Empresa</option>
                                    <option value="emprendedor">Emprendedor</option>
                                    <option value="estudiante">Estudiante</option>
                                    <option value="particular">Particular</option>
                                    <option value="gobierno">Gobierno</option>
                                    <option value="educacion">Educación</option>
                                </select>
                            </div>
                            <div class="grupo-formulario">
                                <label for="tipoProducto">Tipo de Producto/Servicio *</label>
                                <select id="tipoProducto" name="tipoProducto" required>
                                    <option value="">Selecciona una opción</option>
                                    <option value="laptop">Laptops</option>
                                    <option value="desktop">Computadoras de Escritorio</option>
                                    <option value="servidor">Servidores</option>
                                    <option value="redes">Equipos de Red</option>
                                    <option value="pos">Puntos de Venta</option>
                                    <option value="componentes">Componentes</option>
                                    <option value="accesorios">Accesorios</option>
                                    <option value="soporte">Soporte Técnico</option>
                                    <option value="consultoria">Consultoría IT</option>
                                    <option value="redes-instalacion">Instalación de Redes</option>
                                    <option value="seguridad">Seguridad Informática</option>
                                    <option value="software">Desarrollo de Software</option>
                                    <option value="capacitacion">Capacitación</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fila 4 -->
                        <div class="fila-formulario">
                            <div class="grupo-formulario ancho-completo">
                                <label for="descripcion">Descripción del Requerimiento *</label>
                                <textarea id="descripcion" name="descripcion" rows="4" required placeholder="Describe detalladamente lo que necesitas..."></textarea>
                            </div>
                        </div>

                        <!-- Fila 5 -->
                        <div class="fila-formulario">
                            <div class="grupo-formulario">
                                <label for="presupuesto">Presupuesto Aproximado (MXN)</label>
                                <select id="presupuesto" name="presupuesto">
                                    <option value="">No tengo un presupuesto definido</option>
                                    <option value="0-10000">$0 - $10,000</option>
                                    <option value="10000-50000">$10,000 - $50,000</option>
                                    <option value="50000-100000">$50,000 - $100,000</option>
                                    <option value="100000-250000">$100,000 - $250,000</option>
                                    <option value="250000+">Más de $250,000</option>
                                </select>
                            </div>
                            <div class="grupo-formulario">
                                <label for="urgencia">Urgencia *</label>
                                <select id="urgencia" name="urgencia" required>
                                    <option value="">Selecciona una opción</option>
                                    <option value="baja">Baja (1-2 semanas)</option>
                                    <option value="media">Media (3-7 días)</option>
                                    <option value="alta">Alta (1-2 días)</option>
                                    <option value="urgente">Urgente (24 horas)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fila 6 -->
                        <div class="fila-formulario">
                            <div class="grupo-formulario ancho-completo">
                                <label>Método de Contacto Preferido *</label>
                                <div class="opciones-radio">
                                    <label class="opcion-radio">
                                        <input type="radio" name="metodoContacto" value="email" required>
                                        <span class="texto-radio">Email</span>
                                    </label>
                                    <label class="opcion-radio">
                                        <input type="radio" name="metodoContacto" value="telefono">
                                        <span class="texto-radio">Teléfono</span>
                                    </label>
                                    <label class="opcion-radio">
                                        <input type="radio" name="metodoContacto" value="whatsapp">
                                        <span class="texto-radio">WhatsApp</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botón enviar -->
                        <div class="area-envio">
                            <button type="submit" class="boton-enviar">Enviar Solicitud de Cotización</button>
                        </div>
                    </form>
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