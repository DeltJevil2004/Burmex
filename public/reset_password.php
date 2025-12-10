<?php
// public/reset_password.php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    header('Location: recuperar_password.php?token=' . urlencode($token));
    exit();
} else {
    header('Location: recuperar_password.php');
    exit();
}
?>