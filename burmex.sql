-- Crear base de datos si no existe
CREATE DATABASE burmex;
USE burmex;

-- 1. Marcas
CREATE TABLE marcas (
    id_marca INT PRIMARY KEY AUTO_INCREMENT,
    nombre_marca VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    logo_url VARCHAR(500),
    sitio_web VARCHAR(200),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_marca_nombre (nombre_marca)
);

-- 2. Modelos
CREATE TABLE modelos (
    id_modelo INT PRIMARY KEY AUTO_INCREMENT,
    nombre_modelo VARCHAR(150) NOT NULL,
    marca_id INT NOT NULL,
    descripcion TEXT,
    especificaciones_tecnicas JSON,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (marca_id) REFERENCES marcas(id_marca) ON DELETE CASCADE,
    INDEX idx_modelo_nombre (nombre_modelo),
    INDEX idx_modelo_marca (marca_id)
);

-- 3. Categorías
CREATE TABLE categorias (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre_categoria VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    imagen_url VARCHAR(500),
    icono_url VARCHAR(500),
    destacada BOOLEAN DEFAULT FALSE,
    orden INT DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categoria_nombre (nombre_categoria),
    INDEX idx_categoria_destacada (destacada),
    INDEX idx_categoria_orden (orden)
);

-- 4. Usuarios (sin contrasena_hash)
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) NOT NULL UNIQUE,
    contrasena_plano VARCHAR(50) NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    rol ENUM('empleado', 'gerente', 'admin') DEFAULT 'empleado',
    activo BOOLEAN DEFAULT TRUE,
    reset_token VARCHAR(100) NULL,
    reset_token_expira DATETIME NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario_email (email),
    INDEX idx_usuario_rol (rol),
    INDEX idx_usuario_activo (activo),
    INDEX idx_reset_token (reset_token)
);

-- 5. Clientes
CREATE TABLE clientes (
    cliente_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre_completo VARCHAR(200) NOT NULL,
    email VARCHAR(150),
    telefono VARCHAR(20),
    direccion TEXT,
    ciudad VARCHAR(100),
    estado VARCHAR(100),
    pais VARCHAR(100) DEFAULT 'México',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_cliente_nombre (nombre_completo),
    INDEX idx_cliente_email (email),
    INDEX idx_cliente_telefono (telefono),
    INDEX idx_cliente_fecha_registro (fecha_registro DESC),
    INDEX idx_cliente_ciudad (ciudad),
    UNIQUE INDEX idx_cliente_contacto (email, telefono)
);

-- 6. Productos
CREATE TABLE productos (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre_producto VARCHAR(200) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    descripcion TEXT,
    categoria_id INT NOT NULL,
    marca_id INT NOT NULL,
    modelo_id INT,
    stock INT DEFAULT 0,
    destacado BOOLEAN DEFAULT FALSE,
    imagen_url VARCHAR(500),
    tiene_descuento BOOLEAN DEFAULT FALSE,
    porcentaje_descuento INT DEFAULT 0,
    precio_descuento DECIMAL(10,2),
    activo BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id_categoria) ON DELETE RESTRICT,
    FOREIGN KEY (marca_id) REFERENCES marcas(id_marca) ON DELETE RESTRICT,
    FOREIGN KEY (modelo_id) REFERENCES modelos(id_modelo) ON DELETE SET NULL,
    INDEX idx_producto_nombre (nombre_producto),
    INDEX idx_producto_categoria (categoria_id),
    INDEX idx_producto_marca (marca_id),
    INDEX idx_producto_destacado (destacado),
    INDEX idx_producto_activo (activo),
    INDEX idx_producto_stock (stock),
    INDEX idx_producto_precio (precio)
);

-- 7. Movimientos de inventario
CREATE TABLE movimientos_inventario (
    id_movimiento INT PRIMARY KEY AUTO_INCREMENT,
    producto_id INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida', 'ajuste') NOT NULL,
    cantidad INT NOT NULL,
    motivo VARCHAR(200),
    usuario_id INT,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    INDEX idx_movimiento_producto (producto_id),
    INDEX idx_movimiento_fecha (fecha_movimiento DESC),
    INDEX idx_movimiento_tipo (tipo_movimiento)
);

-- 8. Órdenes
CREATE TABLE ordenes (
    id_orden INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT,
    nombre_cliente VARCHAR(200),
    email_cliente VARCHAR(150),
    telefono_cliente VARCHAR(20),
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'procesando', 'completado', 'cancelado') DEFAULT 'pendiente',
    fecha_orden TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    notas TEXT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(cliente_id) ON DELETE SET NULL,
    INDEX idx_orden_estado (estado),
    INDEX idx_orden_fecha (fecha_orden DESC),
    INDEX idx_orden_cliente (cliente_id),
    INDEX idx_orden_total (total)
);

-- 9. Detalles de órdenes
CREATE TABLE orden_detalles (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    orden_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orden_id) REFERENCES ordenes(id_orden) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id_producto),
    INDEX idx_detalle_orden (orden_id),
    INDEX idx_detalle_producto (producto_id)
);

-- 10. Logs de cambios de contraseña por admin/gerente
CREATE TABLE password_changes_admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_cambiado_id INT NOT NULL,
    cambiado_por_id INT NOT NULL,
    fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    FOREIGN KEY (usuario_cambiado_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (cambiado_por_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_usuario_cambiado (usuario_cambiado_id),
    INDEX idx_cambiado_por (cambiado_por_id)
);

-- Insertar usuario admin
INSERT INTO usuarios (email, contrasena_plano, nombre, apellido, rol, activo) 
VALUES ('admin@burmex.com', 'password', 'Administrador', 'Principal', 'admin', 1);