<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$code = $_POST['code'] ?? '';
$secret = $_SESSION['secret'] ?? '';

if (!$secret) {
    echo "Error: clave secreta no encontrada.";
    exit;
}

$g = new GoogleAuthenticator();
$check = $g->checkCode($secret, $code); // true si es válido

if ($check) {
    header('Location: success.php');
    exit;
} else {
    echo "❌ Código inválido. <a href='index.php'>Reintentar</a>";
}
