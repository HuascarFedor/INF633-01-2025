<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

// Usuario fijo de prueba
if ($user === 'admin' && $pass === '1234') {
    $_SESSION['user'] = $user;

    // Generar clave secreta única por sesión
    $g = new GoogleAuthenticator();
    $secret = $g->generateSecret();
    $_SESSION['secret'] = $secret;

    // Mostrar QR
    $qrCodeUrl = GoogleQrUrl::generate($user, $secret, 'MiSistemaMFA');

    echo "<h2>Escanea el código QR con Google Authenticator</h2>";
    echo "<img src='$qrCodeUrl' />";
    echo '<form action="verify.php" method="POST">
            <input type="text" name="code" placeholder="Código MFA" required>
            <button type="submit">Verificar</button>
          </form>';
} else {
    echo "Credenciales inválidas.";
}
