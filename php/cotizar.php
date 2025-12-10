<!DOCTYPE html>
<html lang="en">
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

<!-- Hero Banner Cotizar -->
<section class="hero-banner-cotizar">
    <div class="hero-container-cotizar">
        <div class="hero-content-cotizar">
            <h1 class="hero-title-cotizar">Solicitar Cotización</h1>
            <p class="hero-text-cotizar">Obtén una cotización personalizada para tus necesidades tecnológicas. Respuesta en menos de 24 horas.</p>
        </div>
    </div>
</section>

<!-- Formulario de Cotización -->
<section class="formulario-section">
    <div class="container">
        <div class="formulario-grid">
            
            <!-- Columna Izquierda: Información -->
            <div class="info-column">
                <div class="info-card">
                    <h3 class="info-title">¿Por qué cotizar con nosotros?</h3>
                    
                    <!-- Beneficios -->
                    <div class="beneficios-list">
                        <div class="beneficio-item">
                            <div class="beneficio-icon">
                                <img src="/img/img-cotizar/icono-rapido.png" alt="Respuesta Rápida">
                            </div>
                            <div class="beneficio-content">
                                <h4 class="beneficio-titulo">Respuesta Rápida</h4>
                                <p class="beneficio-texto">Cotización en menos de 24 horas</p>
                            </div>
                        </div>
                        
                        <div class="beneficio-item">
                            <div class="beneficio-icon">
                                <img src="/img/img-cotizar/icono-precio.png" alt="Mejores Precios">
                            </div>
                            <div class="beneficio-content">
                                <h4 class="beneficio-titulo">Mejores Precios</h4>
                                <p class="beneficio-texto">Precios competitivos del mercado</p>
                            </div>
                        </div>
                        
                        <div class="beneficio-item">
                            <div class="beneficio-icon">
                                <img src="/img/img-cotizar/icono-asesoria.png" alt="Asesoría Experta">
                            </div>
                            <div class="beneficio-content">
                                <h4 class="beneficio-titulo">Asesoría Experta</h4>
                                <p class="beneficio-texto">Recomendaciones personalizadas</p>
                            </div>
                        </div>
                        
                        <div class="beneficio-item">
                            <div class="beneficio-icon">
                                <img src="/img/img-cotizar/icono-garantia.png" alt="Garantía Total">
                            </div>
                            <div class="beneficio-content">
                                <h4 class="beneficio-titulo">Garantía Total</h4>
                                <p class="beneficio-texto">Productos con garantía oficial</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cuadro de Contacto -->
                    <div class="contacto-box">
                        <h4 class="contacto-title">Contacto Directo</h4>
                        <div class="contacto-info">
                            <div class="contacto-item">
                                <div class="contacto-icon">
                                    <img src="/img/img-cotizar/icono-telefono.png" alt="Teléfono">
                                </div>
                                <span class="contacto-text">+52 55 1234 5678</span>
                            </div>
                            <div class="contacto-item">
                                <div class="contacto-icon">
                                    <img src="/img/img-cotizar/icono-email.png" alt="Email">
                                </div>
                                <span class="contacto-text">ventas@burmex.com</span>
                            </div>
                            <div class="contacto-item">
                                <div class="contacto-icon">
                                    <img src="/img/img-cotizar/icono-horario.png" alt="Horario">
                                </div>
                                <span class="contacto-text">Lun-Vie: 9am - 6pm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Columna Derecha: Formulario -->
            <div class="form-column">
                <div class="formulario-wrapper">
                    <form class="formulario-cotizacion" id="cotizacionForm">
                        
                        <!-- Fila 1: Nombre Completo - Email -->
                        <div class="form-fila">
                            <div class="form-group">
                                <label for="nombre">Nombre Completo *</label>
                                <input type="text" id="nombre" name="nombre" required placeholder="Ingresa tu nombre completo">
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required placeholder="ejemplo@empresa.com">
                            </div>
                        </div>

                        <!-- Fila 2: Teléfono - Empresa -->
                        <div class="form-fila">
                            <div class="form-group">
                                <label for="telefono">Teléfono *</label>
                                <input type="tel" id="telefono" name="telefono" required placeholder="+52 (xxx) xxx-xxxx">
                            </div>
                            <div class="form-group">
                                <label for="empresa">Empresa</label>
                                <input type="text" id="empresa" name="empresa" placeholder="Nombre de tu empresa">
                            </div>
                        </div>

                        <!-- Fila 3: Tipo de Cliente - Tipo de Producto -->
                        <div class="form-fila">
                            <div class="form-group">
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
                            <div class="form-group">
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

                        <!-- Fila 4: Descripción de Requerimientos -->
                        <div class="form-fila">
                            <div class="form-group full-width">
                                <label for="descripcion">Descripción del Requerimiento *</label>
                                <textarea id="descripcion" name="descripcion" rows="4" required placeholder="Describe detalladamente lo que necesitas..."></textarea>
                            </div>
                        </div>

                        <!-- Fila 5: Presupuesto Aproximado - Urgencia -->
                        <div class="form-fila">
                            <div class="form-group">
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
                            <div class="form-group">
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

                        <!-- Fila 6: Método de Contacto Preferido -->
                        <div class="form-fila">
                            <div class="form-group full-width">
                                <label>Método de Contacto Preferido *</label>
                                <div class="metodo-radio">
                                    <label class="radio-option">
                                        <input type="radio" name="metodoContacto" value="email" required>
                                        <span class="radio-text">Email</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="metodoContacto" value="telefono">
                                        <span class="radio-text">Teléfono</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="metodoContacto" value="whatsapp">
                                        <span class="radio-text">WhatsApp</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botón de Enviar -->
                        <div class="form-submit">
                            <button type="submit" class="btn-enviar">Enviar Solicitud de Cotización</button>
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