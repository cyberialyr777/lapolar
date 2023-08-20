<?php
include("./bd.php");
$url_base="http://localhost/proyecto/";

// Verifica si hay algún mensaje de error y muestra el mensaje correspondiente
if (isset($_GET["error"])) {
  $error_code = $_GET["error"];
  if ($error_code == 1) {
      echo "<p class='error-message'>Por favor, completa todos los campos.</p>";
  } elseif ($error_code == 2) {
      echo "<p class='error-message'>Credenciales inválidas. Inténtalo nuevamente.</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ysabeau+Infant:wght@200&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="imagenes/logo-azul.ico">
    <title>La Polar</title>
</head>
<body>
    
    <div class="container-fluid">
        <div class="contenedor-verde">
            <img class="logo" src="imagenes/logo-azul.png" alt="User" height="155 px" />
            <div>
                <h2 class="texto-inicio-sesion">Inicia sesión con tu email corporativo.</h2>
            </div>
            <div>
                <form action="procesar_inicio_sesion.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" name="correo" placeholder="name@example.com">
                        <label for="floatingInput">Correo</label>
                      </div>
                      <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" name="contrasena" placeholder="Password">
                        <label for="floatingPassword">Contraseña</label>
                      </div>
                    <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="mostrarContrasena">
                      <label class="form-check-label" for="exampleCheck1">Mostrar contraseña</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                  </form>
            </div>
        </div>
    </div>

</body>

<script>
    const mostrarContrasenaCheckbox = document.getElementById("mostrarContrasena");
    const contrasenaInput = document.getElementById("floatingPassword");

    mostrarContrasenaCheckbox.addEventListener("change", function () {
        if (mostrarContrasenaCheckbox.checked) {
            contrasenaInput.type = "text";
        } else {
            contrasenaInput.type = "password";
        }
    });
</script>

</html>
