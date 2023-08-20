<?php

include("./bd.php");
$url_base="http://localhost/proyecto/";

session_start();
session_destroy();
header("Location: index.php");

?>