<?php
include_once "con.php";

$id_usuario         = $_POST['back_id_usuario'];
$pokemon_enviado    = $_POST['back_pokemon_enviado'];
$pokemon_recebido   = $_POST['back_pokemon_recebido'];
$calculo_troca      = $_POST['back_calculo_troca'];


if ( isset ($id_usuario)  && isset ($pokemon_enviado)  && isset ($pokemon_recebido)  && isset ($calculo_troca) ) {

    $query = "INSERT INTO `troca` (`id_usuario`, `pokemon_enviado`, `pokemon_recebido`, `calculo_troca`, `data_troca`)
    VALUES ('$id_usuario', '$pokemon_enviado', '$pokemon_recebido', '$calculo_troca', '".date("Y-m-d H:i:s")."');";
    mysqli_query($link_db, $query);

    $_id = mysqli_insert_id($link_db);
            

    /* remove os pokemons trocados */
    $reg    = '';
    $sep    = '';

    foreach (json_decode($pokemon_enviado) as $pokemon){
        $reg .= "$sep (`id_pokemon` = '$pokemon->id') ";
        $sep = ' OR ';
    }

    $query = "DELETE FROM `cards` WHERE ( $reg );";

    mysqli_query($link_db, $query);




    /* insere os pokemons trocados */
    $reg    = '';
    $sep    = '';

    foreach (json_decode($pokemon_recebido) as $pokemon){
        $reg .= "$sep ('$id_usuario', '$pokemon->id') ";
        $sep = ' , ';
    }

    $query = "INSERT INTO `cards` (`id_usuario`, `id_pokemon`) VALUES $reg";
    mysqli_query($link_db, $query);


} 



?>