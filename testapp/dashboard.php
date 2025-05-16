<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

echo "Bienvenido, ".
htmlspecialchars($_SESSION['username'])."!<a href='logout.php'>Cerrar sesión</a>";