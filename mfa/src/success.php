<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
echo "<h1>✅ Autenticación exitosa, bienvenido {$_SESSION['user']}</h1><a href='logout.php'>Cerrar sesión</a>";
