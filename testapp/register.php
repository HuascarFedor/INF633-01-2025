<?php
require 'db.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Hash de la contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    try{
        $stmt->execute([$username, $hash]);
        echo "Usuario registrado";        
    } catch(PDOException $e){
        echo "Error: ".$e->getMessage();
    }
}
?>
<form method="POST">
Usuario: <input type="text" name="username" required><br>
contraseña: <input type="password" name="password" required>
<button type="submit">Registrarse</button>
</form>