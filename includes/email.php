<?php
// includes/email.php
function enviarEmailRecuperacion($email, $nombre, $token) {
    // Configuración (ajusta según tu servidor)
    $asunto = "Recuperación de Contraseña - Burmex";
    $enlace = "https://tudominio.com/public/reset_password.php?token=" . urlencode($token);
    
    $mensaje = "
    <html>
    <head>
        <title>Recuperación de Contraseña</title>
    </head>
    <body>
        <h2>Hola $nombre,</h2>
        <p>Has solicitado recuperar tu contraseña en el sistema Burmex.</p>
        <p>Para crear una nueva contraseña, haz clic en el siguiente enlace:</p>
        <p><a href='$enlace' style='background:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>
            Restablecer Contraseña
        </a></p>
        <p>Este enlace expirará en 1 hora.</p>
        <p>Si no solicitaste este cambio, ignora este email.</p>
        <hr>
        <p><small>Burmex - Sistema de Administración</small></p>
    </body>
    </html>
    ";
    
    // Headers para email HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@burmex.com" . "\r\n";
    
    // En producción, usa PHPMailer o similar
    // return mail($email, $asunto, $mensaje, $headers);
    
    // Por ahora, solo log
    error_log("Email de recuperación para $email: $enlace");
    return true;
}

function enviarEmailPasswordCambiada($usuario_id, $admin_id) {
    // Similar a la anterior, para notificar cuando admin cambia contraseña
    // Implementar según necesidades
}
?>