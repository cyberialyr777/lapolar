<?php
$url_base = "http://localhost/proyecto/";
include("./bd.php");

$data = obtenerMunicipiosEstados($conexion);
$municipios = $data['municipios'];
$estados = $data['estados'];
$colonias = $data['colonias'];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validación y limpieza de los datos recibidos del formulario
  $nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : "";
  $municipio = isset($_POST['id_municipio']) ? (int)$_POST['id_municipio'] : 0;
  $estado = isset($_POST['id_estado']) ? (int)$_POST['id_estado'] : 0; // Aseguramos que sea un entero
  $colonia = isset($_POST['id_colonia']) ? (int)$_POST['id_colonia'] : 0;
  $telefono = isset($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : "";
  $calle = isset($_POST['calle']) ? htmlspecialchars(trim($_POST['calle'])) : "";
  $numero = isset($_POST['numero']) ? htmlspecialchars(trim($_POST['numero'])) : "";
  $cp = isset($_POST['c_p_']) ? htmlspecialchars(trim($_POST['c_p_'])) : "";
  $correo = isset($_POST['correo']) ? htmlspecialchars(trim($_POST['correo'])) : "";
  $representante_nombre = isset($_POST['representante_nombre']) ? htmlspecialchars(trim($_POST['representante_nombre'])) : "";
  $representante_apellido_paterno = isset($_POST['representante_apellido_paterno']) ? htmlspecialchars(trim($_POST['representante_apellido_paterno'])) : "";


  $sentencia = $conexion->prepare("INSERT INTO `proveedores` (`ID_PROVEEDOR`, `ID_MUNICIPIO`, `ID_ESTADO`, `ID_COLONIA`, `NOMBRE`, `TELEFONO`, `CORREO`, `REPRESENTANTE_NOMBRE`, `REPRESENTANTE_APELLIDO_PATERNO`, `CALLE`, `NUMERO`, `C_P_`, `FECHA_CREACION`, `FECHA_ACTUALIZACION`) VALUES (NULL, :id_municipio, :id_estado, :id_colonia, :nombre, :telefono, :correo, :representante_nombre, :representante_apellido_paterno , :calle, :numero, :c_p_, NOW(), NOW())");



  // Vincular los parámetros correctamente
  $sentencia->bindParam(":nombre", $nombre);
  $sentencia->bindParam(":id_municipio", $municipio);
  $sentencia->bindParam(":id_estado", $estado);
  $sentencia->bindParam(":id_colonia", $colonia);
  $sentencia->bindParam(":telefono", $telefono);
  $sentencia->bindParam(":calle", $calle);
  $sentencia->bindParam(":numero", $numero);
  $sentencia->bindParam(":c_p_", $cp);
  $sentencia->bindParam(":correo", $correo);
  $sentencia->bindParam(":representante_nombre", $representante_nombre);
  $sentencia->bindParam(":representante_apellido_paterno", $representante_apellido_paterno);

  $sentencia->execute();

  header("Location: proveedores.php");
  exit(); //
}


$sentencia = $conexion->prepare("SELECT * FROM `proveedores`");


$sentencia = $conexion->prepare("SELECT p.*, 
                                        CONCAT(representante_nombre, ' ', representante_apellido_paterno) AS representante, 
                                        CONCAT(calle, ' ', numero, ' ', c_p_, ' ', c.NOMBRE, ' ', m.NOMBRE, ' ', e.NOMBRE) AS direccion 
                                FROM `proveedores` p
                                JOIN `colonias` c ON p.`ID_COLONIA` = c.`ID_COLONIA`
                                JOIN `municipios` m ON p.`ID_MUNICIPIO` = m.`ID_MUNICIPIO`
                                JOIN `estados` e ON p.`ID_ESTADO` = e.`ID_ESTADO`");
$sentencia->execute();

$lista_clientes = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['eliminar'])) {
  $txtID = $_GET['eliminar'];
  $sentencia = $conexion->prepare("DELETE FROM proveedores WHERE ID_PROVEEDOR = :id");
  $sentencia->bindParam(":id", $txtID);
  $sentencia->execute();
  header('Location: ' . $_SERVER['PHP_SELF']);
  exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="css/proveedores.css" />
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

        <h5 class="bien">Bienvenido, Melitón Morales</h5>

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
        <div class="icon-add">
          <button id="show-add" class="btn" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
            </svg>
          </button>
          <div class="popup">
            <div class="close-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
              </svg>
            </div>
            <form action="" enctype="multipart/form-data" method="post">
              <div class="form">
                <div class="mb-3">
                  <label for="nombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control nombre" name="nombre" id="nombre" placeholder="Samurai Refacciones">
                </div>
                <div class="mb-3">
                  <label for="telefono" class="form-label">Teléfono</label>
                  <input type="text" class="form-control telefono" name="telefono" id="telefono" placeholder="9934563478">
                </div>
                <div class="mb-3">
                  <label for="calle" class="form-label">Calle</label>
                  <input type="text" class="form-control calle" name="calle" id="calle" placeholder="Calle Pitahaya">
                </div>
                <div class="mb-3">
                  <label for="numero" class="form-label">Numero</label>
                  <input type="text" class="form-control numero" name="numero" id="numero" placeholder="#41">
                </div>
                <div class="mb-3">
                  <label for="cp" class="form-label">C.P.</label>
                  <input type="text" class="form-control cp" name="cp" id="cp" placeholder="86453">
                </div>
                <select style="margin-bottom: 2%;" class="form-select" name="id_municipio" aria-label="Seleccionar municipio">
                  <option value="" selected>Seleccionar municipio</option>
                  <?php foreach ($municipios as $municipio) { ?>
                    <option value="<?php echo $municipio['ID_MUNICIPIO']; ?>"><?php echo $municipio['NOMBRE']; ?></option>
                  <?php } ?>
                </select>

                <select style="margin-bottom: 2%;" class="form-select" name="id_estado" aria-label="Seleccionar colonia">
                  <option value="" selected>Seleccionar estado</option>
                  <?php foreach ($estados as $estado) { ?>
                    <option value="<?php echo $estado['ID_ESTADO']; ?>"><?php echo $estado['NOMBRE']; ?></option>
                  <?php } ?>
                </select>

                <select style="margin-bottom: 2%;" class="form-select" name="id_colonia" aria-label="Seleccionar colonia">
                  <option value="" selected>Seleccionar colonia</option>
                  <?php foreach ($colonias as $colonia) { ?>
                    <option value="<?php echo $colonia['ID_COLONIA']; ?>"><?php echo $colonia['NOMBRE']; ?></option>
                  <?php } ?>
                </select>

                <div class="mb-3">
                  <label for="correo" class="form-label">Correo</label>
                  <input type="text" class="form-control correo" name="correo" id="correo" placeholder="samurai@gmail.com">
                </div>
                <div class="mb-3">
                  <label for="representante_nombre" class="form-label">Representante nombre</label>
                  <input type="text" class="form-control representante_nombre" name="representante_nombre" id="representante_nombre" placeholder="Juan">
                </div>
                <div class="mb-3">
                  <label for="representante_apellido_paterno" class="form-label">Representante apellido paterno</label>
                  <input type="text" class="form-control representante_apellido_paterno" name="representante_apellido_paterno" id="representante_apellido_paterno" placeholder="González">
                </div>
                <div>
                  <button type="submit" class="guardar-btn btn">Guardar</button>
                </div>
              </div>
            </form>

          </div>
        </div>
        <div class="fondo container-fluid row">
          <div class="clientes col-sm-8">
            <h4>Proveedores</h4>
          </div>
          <div class="buscador col-sm-4">
          <form method="get" class="d-flex">
              <input id="search-proveedor" class="form-control me-2" type="text" placeholder="Encontrar proveedores" onkeyup="buscar_proveedores()" />
            
              <button class="bbuscar btn" type="submit"> 
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="lupa bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
              </svg>
              </button>
            </form>

          </div>
          <table class="table table-borderless table-striped" id="tabla_proveedores">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Correo</th>
                <th>Representante</th>
                <th>Fecha creación</th>
                <th>Fecha actualización</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lista_clientes as $registros) { ?>
                <tr>
                  <td><?php echo $registros['ID_PROVEEDOR']; ?></td>
                  <td><?php echo $registros['NOMBRE']; ?></td>
                  <td><?php echo $registros['TELEFONO']; ?></td>
                  <td><?php echo $registros['direccion']; ?></td>
                  <td><?php echo $registros['CORREO']; ?></td>
                  <td><?php echo $registros['representante']; ?></td>
                  <td><?php echo $registros['FECHA_CREACION']; ?></td>
                  <td><?php echo $registros['FECHA_ACTUALIZACION']; ?></td>
                  <td>
                    <a style="background-color: #1a132f !important; color: #f7e2e2 !important;" name="" id="" class="editar btn btn-light" href="editar_proveedores.php?txtID=<?php echo $registros['ID_PROVEEDOR']; ?>" role="button">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5016 1.93934C15.6969 2.1346 15.6969 2.45118 15.5016 2.64645L14.4587 3.68933L12.4587 1.68933L13.5016 0.646447C13.6969 0.451184 14.0134 0.451185 14.2087 0.646447L15.5016 1.93934Z" fill="#F7E2E2" fill-opacity="0.9" />
                        <path d="M13.7516 4.39644L11.7516 2.39644L4.93861 9.20943C4.88372 9.26432 4.84237 9.33123 4.81782 9.40487L4.01326 11.8186C3.94812 12.014 4.13405 12.1999 4.32949 12.1348L6.74317 11.3302C6.81681 11.3057 6.88372 11.2643 6.93861 11.2094L13.7516 4.39644Z" fill="#F7E2E2" fill-opacity="0.9" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1 13.5C1 14.3284 1.67157 15 2.5 15H13.5C14.3284 15 15 14.3284 15 13.5V7.5C15 7.22386 14.7761 7 14.5 7C14.2239 7 14 7.22386 14 7.5V13.5C14 13.7761 13.7761 14 13.5 14H2.5C2.22386 14 2 13.7761 2 13.5V2.5C2 2.22386 2.22386 2 2.5 2H9C9.27614 2 9.5 1.77614 9.5 1.5C9.5 1.22386 9.27614 1 9 1H2.5C1.67157 1 1 1.67157 1 2.5V13.5Z" fill="#F7E2E2" fill-opacity="0.9" />
                      </svg>
                  </a>
                    <a style="background-color: #1a132f !important; color: #f7e2e2 !important;" name="" id="" class="eliminar btn btn-light" href="<?php echo $_SERVER['PHP_SELF'] . '?eliminar=' . $registros['ID_PROVEEDOR']; ?>" role="button">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.5 1C1.94772 1 1.5 1.44772 1.5 2V3C1.5 3.55228 1.94772 4 2.5 4H3V13C3 14.1046 3.89543 15 5 15H11C12.1046 15 13 14.1046 13 13V4H13.5C14.0523 4 14.5 3.55228 14.5 3V2C14.5 1.44772 14.0523 1 13.5 1H10C10 0.447715 9.55229 0 9 0H7C6.44772 0 6 0.447715 6 1H2.5ZM5.5 5C5.77614 5 6 5.22386 6 5.5V12.5C6 12.7761 5.77614 13 5.5 13C5.22386 13 5 12.7761 5 12.5L5 5.5C5 5.22386 5.22386 5 5.5 5ZM8 5C8.27614 5 8.5 5.22386 8.5 5.5V12.5C8.5 12.7761 8.27614 13 8 13C7.72386 13 7.5 12.7761 7.5 12.5V5.5C7.5 5.22386 7.72386 5 8 5ZM11 5.5V12.5C11 12.7761 10.7761 13 10.5 13C10.2239 13 10 12.7761 10 12.5V5.5C10 5.22386 10.2239 5 10.5 5C10.7761 5 11 5.22386 11 5.5Z" fill="#F7E2E2" fill-opacity="0.9" />
                      </svg>
                  </a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
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
  <script src="./js/busqueda_proveedores.js"></script>


</body>

</html>