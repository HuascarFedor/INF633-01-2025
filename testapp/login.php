<?php
session_start();
require 'db.php';

// Inicializacion conteo de intentos
if(!isset($_SESSION['loggin_attempts'])) {
    $_SESSION['loggin_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Definimos cantidad de intentos y penalizacion
$max_attempts = 5;
$lockout_time = 300;

// Verificamos cantidad de intentos
if($_SESSION['loggin_attempts'] >= $max_attempts) {
    $remaining = $lockout_time - (time() - $_SESSION['last_attempt_time']);
    if($remaining > 0) {
        die("Acceso bloqueado. Intente nuevamente en ". ceil($remaining/60) ." minutos");
    }else{
        $_SESSION['loggin_attempts'] = 0;
    }
}

// Generar token CSRF si no existe
if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Token CSRF inválido.");
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    }
    else{
        $_SESSION['loggin_attempts'] += 1;
        $_SESSION['last_attempt_time'] = time();
        echo "Usuario o contraseña incorrectos";
    }
}
?>
<form method="POST">
<label>Número de intentos: <?php echo $_SESSION['loggin_attempts']; ?></label>
<br>
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
Usuario: <input type="text" name="username" required><br>
contraseña: <input type="password" name="password" required>
<button type="submit">Iniciar sesión</button>
</form>