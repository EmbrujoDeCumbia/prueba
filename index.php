<?php
session_start();
include './conexion.php'; // Aquí irá tu archivo con la conexión mysqli

// Procesar el login cuando se envíe el formulario
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta a la base de datos
    $sql = "SELECT * FROM usuarios WHERE usuario = ? AND contrasena = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_assoc();
        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['nombre'] = $fila['nombre']; // si lo tienes
        $_SESSION['userType'] = $fila['tipo'];
        $_SESSION['loggedIn'] = true;


        if ($fila['tipo'] == 'profesor') {
            header("Location: ./profesor/inicio.php");
        } else {
            header("Location: ./alumno/inicio.php");
        }
        exit;
    } else {
        $mensaje = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if ($mensaje): ?>
            <p style="color: red;"><?= $mensaje ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
