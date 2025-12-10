<?php
// test_db.php
echo "<pre>";
echo "=== VERIFICACIÓN DE BASE DE DATOS ===\n\n";

// Configuración de base de datos
$host = 'localhost';
$dbname = 'burmex';
$user = 'root';
$pass = '';

try {
    // 1. Intentar conexión general (sin seleccionar base de datos)
    echo "1. Conectando a MySQL...\n";
    $conn_general = new PDO("mysql:host=$host", $user, $pass);
    $conn_general->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ Conexión a MySQL exitosa\n";
    
    // 2. Verificar si existe la base de datos
    echo "\n2. Verificando base de datos 'burmex'...\n";
    $stmt = $conn_general->query("SHOW DATABASES LIKE 'burmex'");
    $db_exists = $stmt->fetch();
    
    if ($db_exists) {
        echo "   ✅ Base de datos 'burmex' existe\n";
        
        // 3. Conectar a la base de datos específica
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 4. Verificar tablas
        echo "\n3. Verificando tablas en 'burmex'...\n";
        $stmt = $conn->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "   Tablas encontradas (" . count($tables) . "):\n";
            foreach ($tables as $table) {
                echo "   - $table\n";
            }
            
            // 5. Verificar tabla usuarios específicamente
            echo "\n4. Verificando tabla 'usuarios'...\n";
            $stmt = $conn->query("DESCRIBE usuarios");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($columns) {
                echo "   ✅ Tabla 'usuarios' existe con " . count($columns) . " columnas:\n";
                foreach ($columns as $col) {
                    echo "      - {$col['Field']} ({$col['Type']})\n";
                }
                
                // 6. Verificar datos en usuarios
                echo "\n5. Verificando datos en 'usuarios'...\n";
                $stmt = $conn->query("SELECT * FROM usuarios");
                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($usuarios) > 0) {
                    echo "   ✅ Hay " . count($usuarios) . " usuarios registrados:\n";
                    foreach ($usuarios as $usuario) {
                        echo "      - ID: {$usuario['id_usuario']}\n";
                        echo "        Email: {$usuario['email']}\n";
                        echo "        Nombre: {$usuario['nombre']} {$usuario['apellido']}\n";
                        echo "        Rol: {$usuario['rol']}\n";
                        echo "        Activo: " . ($usuario['activo'] ? 'Sí' : 'No') . "\n";
                        echo "        Hash: " . substr($usuario['contrasena_hash'] ?? 'NO TIENE', 0, 30) . "...\n";
                        echo "\n";
                    }
                } else {
                    echo "   ❌ Tabla 'usuarios' está VACÍA\n";
                }
            } else {
                echo "   ❌ Tabla 'usuarios' NO existe o está vacía\n";
            }
        } else {
            echo "   ❌ Base de datos 'burmex' está VACÍA (sin tablas)\n";
        }
    } else {
        echo "   ❌ Base de datos 'burmex' NO existe\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE VERIFICACIÓN ===\n";
echo "</pre>";
?>