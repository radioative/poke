<?php
session_start();
include_once "con.php";

if ( isset ($_GET['usuario']) && $_GET['usuario'] != '') {
    
    /* remover caracteres especiais */
    $usuario = clean_string($_GET['usuario']);

    /* consulta ao banco de dados */
    $stmt = mysqli_prepare($link_db, "SELECT * FROM usuario WHERE nome = ?");
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $resposta = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_assoc($resposta);
    
    /* usuario não encontrado no banco de dados */
    if ( count ($rows) <= 0 ){
    
        /* criar um usuario no  banco de dados */
        if (isset ($_GET['criar']) && $_GET['criar'] == '1') {

            $query = "INSERT INTO `usuario` (`nome`) VALUES ('$usuario')";
            mysqli_query($link_db, $query);
            $_id = mysqli_insert_id($link_db);
            
            if ( $_id > 0){

                /* cria uma sessão com os dados do usuario */
                $_SESSION['usuario']['id']      = $_id;
                $_SESSION['usuario']['nome']    = $usuario;


                /* insere 12 pokemons para o usuario */
                $query = "select id from pokemon order by rand() limit 12";
                $result = mysqli_query($link_db, $query);
                $rows = mysqli_fetch_all($result); 

                $reg    = '';
                $sep    = '';

                foreach ( $rows as $item ){

                    $reg .= "$sep ('$_id', '".$item[0]."' ) ";
                    $sep = ',';

                }
                
                $query = "INSERT INTO `cards` (`id_usuario`, `id_pokemon`) VALUES $reg";
                mysqli_query($link_db, $query);
                mysqli_insert_id($link_db);
                
                $mensagem = "
                    <h5>Usuario <b>".$_SESSION['usuario']['nome']."</b> criado com sucesso! </h5> <br />
                    <button class='w-100 btn btn-lg btn-success' href='pokemon.php'>Trocar Pokemons</button>
                ";
            
            }

        /* opção para criar o usuario no banco de dados */
        } else { 

            $mensagem = " 
                <h5>Registro não encontrado, Deseja criar o usuario <b>$usuario</b> ?</h5> <br />
                <a class='w-100 btn btn-lg btn-success' href='index.php?usuario=$usuario&criar=1'>Criar Usuário</a>
            ";

        }

    /* usuario encontrado no banco de dados */
    } else {
     
        /* cria uma sessão com os dados do usuario */
        $_SESSION['usuario']['id']      = $rows['id'];
        $_SESSION['usuario']['nome']    = $rows['nome'];

        $mensagem = "
            <h5>Usuario <b>".$_SESSION['usuario']['nome']."</b> encontrado</h5> <br />
            <a class='w-100 btn btn-lg btn-success' href='pokemon.php'>Trocar Pokemons</a>
        ";
        
    }
    
} else {

    unset($_SESSION);

}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>Pokefight</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <!-- Style CSS -->
    <link href="css/style.css" rel="stylesheet">

  </head>
  
 <body class="text-center login">
    
    <main class="form-signin">
      <form action="" method="GET">

        <h1 class="h3 mb-3 fw-normal">Digite o nome do seu usuário</h1>

        <div class="form">
          <input type="text" class="form-control" id="id_usuario" name="usuario" value="<?=$usuario?>" placeholder="nome do usuario">

        </div>

        <button class="w-100 btn btn-lg btn-secondary" type="submit">Buscar</button>

        <div class="mensagem"><?=$mensagem?></div>

      </form>
    </main>

</body>

</html>
<?php //echo "<pre>"; print_r($_SESSION); echo "</pre>"; ?>