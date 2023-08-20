<?php 
$servidor="localhost";
$baseDeDatos="lapolar";
$usuario="root";
$contrasena="";

try {

    $conexion=new PDO("mysql:host=$servidor;dbname=$baseDeDatos",$usuario,$contrasena);

}catch(Exception $error){
    echo $error->getMessage();
}



function obtenerMunicipiosEstados($conexion) {
    $query = "SELECT * FROM municipios";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $municipios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM estados";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM colonias";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $colonias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array('municipios' => $municipios, 'estados' => $estados, 'colonias' => $colonias);
}

function obtenerMarcasModelos($conexion) {
    $query = "SELECT * FROM marcas";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM modelos";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array('marcas' => $marcas, 'modelos' => $modelos);
}

function obtenerProveedores($conexion) {
    $query = "SELECT * FROM proveedores";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array('proveedores' => $proveedores);
}

function obtenerEmpresa($conexion) {
    $query = "SELECT * FROM clientes";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array('clientes' => $clientes);
}

?>

