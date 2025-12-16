<?php
 
session_start();

// Verificar si usuario está logueado y es admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../../public/login.php?error=permisos');
    exit();
}

require_once '../../includes/config.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        // No permitir eliminar al propio usuario
        if ($id == $_SESSION['usuario_id']) {
            header('Location: ../usuarios.php?error=no_autoeliminar');
            exit();
        }
        
        // Eliminar usuario
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        
        header('Location: ../usuarios.php?success=eliminado');
        exit();
        
    } catch (PDOException $e) {
        error_log("Error eliminando usuario: " . $e->getMessage());
        header('Location: ../usuarios.php?error=eliminar');
        exit();
    }
} else {
    header('Location: ../usuarios.php?error=no_id');
    exit();
}
?>