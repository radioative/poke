<?php
session_start();
include_once "con.php";

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
 
 <?php
 
 
 



 ?>
 
 
 
 

  <body>

      

        <div class="container-fluid">


            <h5><?=$_SESSION['usuario']['nome']?> <a class=' btn btn-lg btn-link' href='index.php'>Sair</a></h5>  
            
            
      
       <?php

        //session_destroy();

        if ( !isset ($_SESSION['usuario']) && $_SESSION['usuario'] == '') {


            ?>
           
            <div class="row">

                <div class="col-12" style="padding:20%;" align="center">
                
                   <h2> Usuario não encontrado</h2><br /><br /><br /><br />

                   <a class=' btn btn-lg btn-success' href='index.php' target='_blank'> Clique aqui para selecionar um usuario</a>
                   

                </div>
                
                </div>
           
            <?php     
           
                    exit();

                } else {



                $query = "SELECT pokemon.id as id, pokemon.nome as nome, pokemon.exp as exp, pokemon.imagem as imagem FROM usuario LEFT JOIN cards ON usuario.id = cards.id_usuario LEFT JOIN pokemon ON cards.id_pokemon = pokemon.id WHERE usuario.id = ". $_SESSION['usuario']['id'];
                $result = mysqli_query($link_db, $query);
                $rows = mysqli_fetch_all($result); 
                
               
                $player1_cards = '';
                $i=1;
                foreach ( $rows as $item ){

                   //echo "<pre>"; print_r($item); echo "</pre>";
                   
                    $player1_cards .= "pokemon_busca('player1', $i, ".$item[0]."); \r\n";  
                    $i++;

                }



            ?>



        
            <h3>Usuario: <?=$_SESSION['id_treinador']?></h3>

            
         
            
            
            
     
                 
            <div class="row">



                <div class="col-5">
                    <div class= "placar" id="player1_placar"></div>
                    <div id="player1" class="deck"></div>
                </div>


                <div class="col-2" align="center">
                    <div id="combate"></div>
                    <button type="button" class="btn btn-primary" id="botao_troca" style="visibility: hidden;" onClick="salvar_troca();">trocar pokemon</button>
                </div>            

                <div class="col-5">
                    <div class= "placar" id="player2_placar"></div>
                    <div id="player2" class="deck"></div>
                </div>


            </div>
            
            
             <br /><br /><br />
            
               
            <div class="row">
                <div class="col-12">
                    <div  id="historico">
                    
                    <h3>Histórico de trocas</h3>
                    <?php
                    
                        $query = " select * from troca where id_usuario = ". $_SESSION['usuario']['id'];
                        $result = mysqli_query($link_db, $query);
                        $rows = mysqli_fetch_all($result); 

                        $table_line = '';
                        
                        foreach ( $rows as $item ){

                  
                  
                           $recebido = '';
                           $sep = '';
                            foreach (json_decode($item[2]) as $pokemon){
                                $recebido .= "$sep  $pokemon->name ";
                                $sep = ', ';
                            }
                  
                           $enviado = '';
                           $sep = '';
                            foreach (json_decode($item[3]) as $pokemon){
                                $enviado .= "$sep  $pokemon->name ";
                                $sep = ', ';
                            }

                           
                            $table_line .= "<tr>                            
                            <td>$recebido</td> <td>$enviado</td> <td>$item[4]</td><td>$item[5]</td>
                            </tr>";
         
                        }

                         echo "
                               
                                <table  class='table '>
                                    <td><b>Pokemons enviados</b></td> <td><b>Pokemons recebidos</b></td> <td><b>Calculo</b></td><td><b>data</b></td>
                                    $table_line
                                </table>
                            "; 
                        ?>
                        
                    </div>
                </div>
            </div>
            
            
             <br /><br /><br />

            <?php

                }

            ?>

       
            
            <?php //echo "<div class='row'><div class='col-12'><pre>"; print_r($_SESSION); echo "</pre></div></div>"; ?>

            
          
        </div>








    <script src="js/atomic.js"></script>
    
    <script src="js/script.js"></script>


<script type="application/javascript">
    
    
    /* inicia uma variavel com o valor da combinação de cada jogador */
    //var jogadores = ['player1', 'player2'];

    var id_usuario = "<?=$_SESSION['usuario']['id']?>";


    /* array com os cards de cada jogador */ 
    var player1_cards = [];
    var player2_cards = [];

    /* inicia uma variavel a soma de EXP de cada jogador */
    var player1_soma_exp = 0;
    var player2_soma_exp = 0;

    /* estabelece a quantidade máxima de cards por jogador para troca */
    var quantidade_maxima_troca = 6;
    
    /* valor com o calculo de troca entre os jogadores */
    var calculo_troca = 0;


  
  
        /* código executado após o carregamento completo do DOM */

        document.addEventListener('DOMContentLoaded', function(event) {
        
            <?=$player1_cards?>
        
        
            /* carrega pokemons aleatórios para o adversário */
            pokemon_busca("player2", 1);            
            pokemon_busca("player2", 2);            
            pokemon_busca("player2", 3);
            pokemon_busca("player2", 4);
            pokemon_busca("player2", 5);
            pokemon_busca("player2", 6);
            pokemon_busca("player2", 7);
            pokemon_busca("player2", 8);
            pokemon_busca("player2", 9);
            pokemon_busca("player2", 10);
            pokemon_busca("player2", 11);
            pokemon_busca("player2", 12);
            
                        
        });





    
</script>



  </body>
  
  
    
</html>


  
















