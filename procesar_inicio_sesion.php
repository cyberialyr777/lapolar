<?php
session_start(); // Inicia la sesión para mantener al usuario conectado

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los datos ingresados en el formulario
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    // Realiza alguna validación básica, como asegurarse de que los campos no estén vacíos
    if (empty($correo) || empty($contrasena)) {
        // Si faltan datos, redirige con un mensaje de error
        header("Location: http://localhost/proyecto/index.php?error=1");
        exit;
    }

    // Conexión a la base de datos usando PDO
    $servidor = "localhost";
    $baseDeDatos = "lapolar";
    $usuario = "root";
    $contrasenaBD = "";

    try {
        $conexion = new PDO("mysql:host=$servidor;dbname=$baseDeDatos", $usuario, $contrasenaBD);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $error) {
        echo $error->getMessage();
        exit;
    }

    // Prepara la consulta para verificar las credenciales en la tabla de usuarios
    $query = "SELECT * FROM usuarios WHERE correo = :correo AND contrasena = :contrasena";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(":correo", $correo);
    $stmt->bindParam(":contrasena", $contrasena);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        // Si las credenciales son válidas, inicia la sesión y redirige al usuario a inicio.php
        $_SESSION["correo"] = $correo;
        header("Location: http://empresa.lapolar/inicio.php");
        exit;
    } else {
        // Si las credenciales no son válidas, redirige con un mensaje de error
        header("Location: http://empresa.lapolar/index.php?error=2");
        exit;
    }
}
?>
