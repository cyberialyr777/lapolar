<?php
include("./bd.php");
$url_base = "http://localhost/proyecto/";

$data = obtenerMarcasModelos($conexion);
$marcas = $data['marcas'];
$modelos = $data['modelos'];


if (isset($_GET['txtID'])) {
  $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";
  $sentencia_cantidad_actual = $conexion->prepare("SELECT CANTIDAD, FECHA_ENTRADA, FECHA_SALIDA FROM `inventarios` WHERE ID_REFACCION = :id_refaccion");
  $sentencia_cantidad_actual->bindParam(":id_refaccion", $txtID);
  $sentencia_cantidad_actual->execute();
  $resultado_cantidad_actual = $sentencia_cantidad_actual->fetch(PDO::FETCH_ASSOC);
  $cantidad_actual = (int)$resultado_cantidad_actual['CANTIDAD'];
  $fecha_entrada_actual = $resultado_cantidad_actual['FECHA_ENTRADA'];
  $fecha_salida_actual = $resultado_cantidad_actual['FECHA_SALIDA'];

  $sentencia = $conexion->prepare("SELECT * FROM inventarios WHERE id_refaccion=:id");
  $sentencia->bindParam(":id", $txtID);
  $sentencia->execute();
  $registro = $sentencia->fetch(PDO::FETCH_LAZY);

  $refaccion = $registro['REFACCION'];
  $marca = $registro['ID_MARCA'];
  $modelo = $registro['ID_MODELO'];
  $cantidad = $registro['CANTIDAD'];
}

if ($_POST) {
  print_r($_POST);
  $refaccion = isset($_POST['refaccion']) ? htmlspecialchars(trim($_POST['refaccion'])) : "";
  $marca = isset($_POST['id_marca']) ? (int)$_POST['id_marca'] : 0;
  $modelo = isset($_POST['id_modelo']) ? (int)$_POST['id_modelo'] : 0; // Aseguramos que sea un entero
  $cantidad = isset($_POST['cantidad']) ? htmlspecialchars(trim($_POST['cantidad'])) : "";



    // If quantity is increased, update "fecha_salida" to current date
  if ($cantidad > $cantidad_actual)   {
    $fecha_entrada = date("Y-m-d H:i:s");
  
  } else {
    $fecha_entrada = $fecha_entrada_actual;

    // If quantity is decreased, update "fecha_salida" to current date
    if ($cantidad < $cantidad_actual) {
      $fecha_salida = date("Y-m-d H:i:s");
    } else {
      $fecha_salida = $fecha_salida_actual;
    }
  }



  $sentencia = $conexion->prepare("UPDATE inventarios SET 
  REFACCION=:refaccion,
  ID_MARCA=:id_marca,
  ID_MODELO=:id_modelo,
  CANTIDAD=:cantidad,
  FECHA_ENTRADA = :fecha_entrada,
  FECHA_SALIDA = :fecha_salida
  WHERE ID_REFACCION=:id");

  // Vincular los parámetros correctamente
  $sentencia->bindParam(":id", $txtID);
  $sentencia->bindParam(":refaccion", $refaccion);
  $sentencia->bindParam(":id_marca", $marca);
  $sentencia->bindParam(":id_modelo", $modelo);
  $sentencia->bindParam(":cantidad", $cantidad);
  $sentencia->bindParam(":fecha_entrada", $fecha_entrada);
  $sentencia->bindParam(":fecha_salida", $fecha_salida_actual);
  $sentencia->execute();

  header("Location: inventario.php");
  exit();
}



?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="css/editar_proveedores.css" />
  <link rel="icon" type="image/x-icon" href="imagenes/logo-azul.ico">

  <title>La Polar</title>
</head>

<body>

  <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a class="navbar-brand" href="<?php echo $url_base . '/inicio.php'; ?>">
      <img class="user" src="imagenes/logo.png" alt="User" height="70 px" />
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="links collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="inicio nav-link" href="<?php echo $url_base . '/inicio.php'; ?>">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="facturas nav-link" href="<?php echo $url_base . '/facturas.php'; ?>">Facturas</a>
        </li>
        <li class="nav-item">
          <a class="agrein nav-link" href="<?php echo $url_base . '/inventario.php'; ?>">Inventario</a>
        </li>
      </ul>


      <span class="bb badge rounded-pill">
        <img class="user" src="imagenes/user.png" alt="User" height="30 px" />
        Melitón Morales
      </span>
      <a href="cerrar_sesion.php" style="background-color:#F7E2E2; margin-right: 2%;" class="btn btn-sm ml-2">Salir</a>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="arriba row">
      <div class="fuser col-sm-3">
        <img class="user" src="imagenes/user.png" alt="User" height="70 px" />
        <p> </p>
        <h6 class="bien">Melitón Morales</h6>
        <p class="ceo">CEO</p>
      </div>

      <div class="bienn col-sm-9 ">

        <h5 class="bien">Editar</h5>

      </div>

    </div>


    <div class="abajo row">
      <div class="menu col-sm-3">
        <div class="d-grid gap-2 d-md-block">
          <a href="<?php echo $url_base . '/inicio.php'; ?>">
            <button class="binicio btn btn-primary" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z" />
              </svg>
              <p>Inicio</p>
            </button>
          </a>

          <a href="<?php echo $url_base . '/clientes.php'; ?>">
            <button class="bclientes btn btn-primary" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
              </svg>
              <p>Clientes</p>
            </button>
          </a>
        </div>

        <div class="d-grid gap-2 d-md-block">
          <a href="<?php echo $url_base . '/proveedores.php'; ?>">
            <button class="binicio btn btn-primary" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
              </svg>
              <p>Proveedores</p>
            </button>
          </a>

          <a href="<?php echo $url_base . '/credito.php'; ?>">
            <button class="bclientes btn btn-primary" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z" />
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z" />
              </svg>
              <p>Crédito</p>
            </button>
          </a>
        </div>

        <div class="d-grid gap-2 d-md-block">
          <a href="<?php echo $url_base . '/inventario.php'; ?>">
            <button class="binicio btn btn-primary" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-tools" viewBox="0 0 16 16">
                <path d="M1 0 0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.27 3.27a.997.997 0 0 0 1.414 0l1.586-1.586a.997.997 0 0 0 0-1.414l-3.27-3.27a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3c0-.269-.035-.53-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814L1 0Zm9.646 10.646a.5.5 0 0 1 .708 0l2.914 2.915a.5.5 0 0 1-.707.707l-2.915-2.914a.5.5 0 0 1 0-.708ZM3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026L3 11Z" />
              </svg>
              <p>Inventario</p>
            </button>
          </a>

          <a href="<?php echo $url_base . '/cobranza.php'; ?>">
            <button class="bclientes btn btn-primary" type="button">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-piggy-bank" viewBox="0 0 16 16">
                <path d="M5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm1.138-1.496A6.613 6.613 0 0 1 7.964 4.5c.666 0 1.303.097 1.893.273a.5.5 0 0 0 .286-.958A7.602 7.602 0 0 0 7.964 3.5c-.734 0-1.441.103-2.102.292a.5.5 0 1 0 .276.962z" />
                <path fill-rule="evenodd" d="M7.964 1.527c-2.977 0-5.571 1.704-6.32 4.125h-.55A1 1 0 0 0 .11 6.824l.254 1.46a1.5 1.5 0 0 0 1.478 1.243h.263c.3.513.688.978 1.145 1.382l-.729 2.477a.5.5 0 0 0 .48.641h2a.5.5 0 0 0 .471-.332l.482-1.351c.635.173 1.31.267 2.011.267.707 0 1.388-.095 2.028-.272l.543 1.372a.5.5 0 0 0 .465.316h2a.5.5 0 0 0 .478-.645l-.761-2.506C13.81 9.895 14.5 8.559 14.5 7.069c0-.145-.007-.29-.02-.431.261-.11.508-.266.705-.444.315.306.815.306.815-.417 0 .223-.5.223-.461-.026a.95.95 0 0 0 .09-.255.7.7 0 0 0-.202-.645.58.58 0 0 0-.707-.098.735.735 0 0 0-.375.562c-.024.243.082.48.32.654a2.112 2.112 0 0 1-.259.153c-.534-2.664-3.284-4.595-6.442-4.595zM2.516 6.26c.455-2.066 2.667-3.733 5.448-3.733 3.146 0 5.536 2.114 5.536 4.542 0 1.254-.624 2.41-1.67 3.248a.5.5 0 0 0-.165.535l.66 2.175h-.985l-.59-1.487a.5.5 0 0 0-.629-.288c-.661.23-1.39.359-2.157.359a6.558 6.558 0 0 1-2.157-.359.5.5 0 0 0-.635.304l-.525 1.471h-.979l.633-2.15a.5.5 0 0 0-.17-.534 4.649 4.649 0 0 1-1.284-1.541.5.5 0 0 0-.446-.275h-.56a.5.5 0 0 1-.492-.414l-.254-1.46h.933a.5.5 0 0 0 .488-.393zm12.621-.857a.565.565 0 0 1-.098.21.704.704 0 0 1-.044-.025c-.146-.09-.157-.175-.152-.223a.236.236 0 0 1 .117-.173c.049-.027.08-.021.113.012a.202.202 0 0 1 .064.199z" />
              </svg>
              <p>Cobranza</p>
            </button>
          </a>
        </div>
      </div>

      <div class="col-sm-9" style="margin-bottom: 230px;">
        <div class="container formulario">
          <form action="" enctype="multipart/form-data" method="post" class="form">
            <div class="form">
              <div class="mb-3">
                <label for="nombre" class="form-label">Refaccion</label>
                <input value="<?php echo $refaccion; ?>" type="text" class="form-control nombre" name="refaccion" id="nombre" placeholder="Banda">
              </div>
              <div class="mb-3">
                <label for="telefono" class="form-label">Cantidad</label>
                <input value="<?php echo $cantidad; ?>" type="text" class="form-control telefono" name="cantidad" id="telefono" placeholder="9">
              </div>
              <select style="margin-bottom: 2%;" class="form-select" name="id_marca" aria-label="Seleccionar marca">
                <option value="" disabled>Seleccionar marca</option>
                <?php foreach ($marcas as $clienteItem) { ?>
                  <option value="<?php echo $clienteItem['ID_MARCA']; ?>" <?php if ($marcas == $clienteItem['ID_MARCA']) echo "selected"; ?>><?php echo $clienteItem['MARCA']; ?></option>
                <?php } ?>
              </select>

              <select style="margin-bottom: 2%;" class="form-select" name="id_modelo" aria-label="Seleccionar modelo">
                <option value="" disabled>Seleccionar modelo</option>
                <?php foreach ($modelos as $clienteItem) { ?>
                  <option value="<?php echo $clienteItem['ID_MODELO']; ?>" <?php if ($modelos == $clienteItem['ID_MODELO']) echo "selected"; ?>><?php echo $clienteItem['MODELO']; ?></option>
                <?php } ?>
              </select>
              <div>
                <button type="submit" class="guardar-btn btn">Guardar</button>
              </div>
            </div>
          </form>
        </div>



      </div>

    </div>

  </div>

  <footer class="footer">
    <div class="container">
      <p>Copyright La Polar 2023</p>
    </div>
  </footer>

  <script src="./js/popover.js"></script>


</body>

</html>