<?php
error_reporting(-1);
ini_set('error_reporting', E_ALL);

//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername     = "localhost";
$username       = "root";
$password       = "root";
$dbname         = "pokemon_db";


$link_db = mysqli_connect($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) { 
    printf("<br /> Falha na conexão: %s\n", mysqli_connect_error()); exit();
}


/* limpa os caracteres especiais da string */
function clean_string($string){
    $string = trim($string);
    $string = str_replace(' ', '-', $string);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}


?>
