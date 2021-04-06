<?php
session_start();
include_once "con.php";

//$_SESSION['id'] = 1;


if ( !isset ($_SESSION['id']) ){

    $_SESSION['id'] = 1;

}


//echo gettype (intval ($_SESSION['id']));


$handle = file ('https://pokeapi.co/api/v2/pokemon/' . intval($_SESSION['id']) );

$handle = json_decode($handle[0]);


$id         = $handle->id;
$name       = $handle->name;
$exp        = $handle->base_experience;
$imagem     = $handle->sprites->front_default;


$query = "INSERT INTO `pokemon` (`id`, `nome`, `exp`, `imagem`)
VALUES ('$id', '$name', '$exp', '$imagem' );";

mysqli_query($link_db, $query);



$_SESSION['id'] = intval ($_SESSION['id']) + 1;

header('refresh:5;scrap.php');


echo $_SESSION['id'];



?>



