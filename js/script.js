


/* busca um pokemon na API  */
function pokemon_busca( player, card, numero_pokemon = 0 ) {

    /* caso o id do pokemon não sdeja definido, busca um aleatório */
    if ( numero_pokemon == 0 ) {
        numero_pokemon = Math.floor((Math.random() * 200) + 1);
    } 

    var xhr = atomic("https://pokeapi.co/api/v2/pokemon/"+numero_pokemon);

    xhr.then(function (pokemon) {

        //<img src='${pokemon.data.sprites.front_default}'> 
        //<input type='checkbox' id='${player}_${card}_check' onclick='calcula_combinacao(this.id, "${player}");'>

        var resposta = `
            <div style='margin:20px;'
                id='${player}_${card}'
                data-id='${pokemon.data.id}'
                data-name='${pokemon.data.name}' 
                data-exp='${pokemon.data.base_experience}' >

                Name: ${pokemon.data.name}   
                Exp:  ${pokemon.data.base_experience} 
                <img src='${pokemon.data.sprites.front_default}'> 
                <button type='button' class='btn btn-sm btn-primary' onclick='calcula_combinacao(this.parentNode.id, "${player}");' >Trocar</button>

            </div>`;

        document.getElementById(player).innerHTML += resposta;

    })
    .catch(function (error) {
        console.log(error.status); console.log(error.statusText);  
    });

}







/* verifica se a combinação é justa - margem de 20% */
function verificar_troca (){

    if (player1_soma_exp > 0 && player2_soma_exp > 0){ 

        /* divide a soma de EXP de um jogador pela soma de EXP do outro jogador */
        calculo_troca = player1_soma_exp / player2_soma_exp;

        /* coloca 2 casas decimais na variavel */
        calculo_troca = calculo_troca.toFixed(2);

        var resposta = calculo_troca;

        /* verifica se o valor é 20% menor ou 20% maior para definir como uma troca justa */
        if (calculo_troca > 0.8 && calculo_troca < 1.2 ){

            resposta += " troca justa";
            document.getElementById("botao_troca").style.visibility = "visible";

        } else {

            resposta += " troca injusta";
            document.getElementById("botao_troca").style.visibility = "hidden";

        }
        
        //alert(resposta);
        if (resposta != 'undefined'){
            document.getElementById("combate").innerHTML = resposta;
        }
        
    }

    

}




/* verifica os elementos checkbox que estão clicados para somar o EXP do jogador */
function calcula_combinacao ( elemento, player  ){

    /* busca o elemento no documento */
    elm = document.getElementById(elemento);

    /* insere / remove o card do array - Toggle */
    if (window[player + "_cards"].includes(elm.id) == false ){

        /* verifica se a quantidade de cards é menor ou igual que a quantidade máxima de troca */
        if ( window[player + "_cards"].length < quantidade_maxima_troca ){

            /* insere o card no array do jogador */
            window[player + "_cards"].push(elm.id);

            /* soma o EXP do card escolhido */
            window[player + "_soma_exp"] += parseInt( elm.dataset.exp );

            /* insere uma classe CSS para identificação de card escolhido */
            document.getElementById(elm.id).classList.add("card_escolhido");

        } else {

            alert(`A quantidade máxima de cards para troca é : ${quantidade_maxima_troca}`);

        }

    } else {

        /* remove o card do array */
        window[player + "_cards"] =  window[player + "_cards"].filter(function(item) { return item !== elm.id });

        /* subtrai o EXP do card escolhido */
        window[player + "_soma_exp"] -= parseInt( elm.dataset.exp );

        /* remove uma classe CSS para identificação de card escolhido */
        document.getElementById(elm.id).classList.remove("card_escolhido");

    }

    /* mostra a soma de EXP dos cards do jogador */
    document.getElementById( player +  "_placar" ).innerHTML = "Soma: " + window[player + "_soma_exp"]; 

    /* calcula se a troca de cards é justa ou injusta */
    verificar_troca();

}





  



function salvar_troca (){

    document.getElementById("botao_troca").style.visibility = "hidden";
    
    /* monta objeto com os nomes e exp de cada pokemon enviado para o outro jogador */
    var pokemon_enviado = [];
    player1_cards.forEach((card) => { 
        var objeto = {
            "id":    document.getElementById(card).dataset.id,
            "name":  document.getElementById(card).dataset.name,
            "exp":   document.getElementById(card).dataset.exp
        }; 
        pokemon_enviado.push ( objeto );
    });


    /* monta objeto com os nomes e exp de cada pokemon recebido do outro jogador */
    var pokemon_recebido = [];
    player2_cards.forEach((card) => { 
        var objeto = {
            "id":    document.getElementById(card).dataset.id,
            "name":  document.getElementById(card).dataset.name,
            "exp":   document.getElementById(card).dataset.exp
        }; 
        pokemon_recebido.push ( objeto );
    });



    pokemon_enviado  = JSON.stringify(pokemon_enviado);
    
    pokemon_recebido = JSON.stringify(pokemon_recebido);
    
  
  
     
    //montar a lista com base na lista maior - lista de pokemon_enviado
    //salvar em banco - update
    
    
    
    

    var xhr = atomic('backend.php', {
        responseType: 'txt',
        cache: false,
        data:  {
            back_id_usuario:        id_usuario,
            back_pokemon_enviado:   pokemon_enviado,
            back_pokemon_recebido:  pokemon_recebido,
            back_calculo_troca:     calculo_troca
            
        },
        method: 'POST'
    })


    xhr.then(function (pokemon) {
        document.getElementById( "combate" ).innerHTML += "<br />Troca feita com sucesso!" ; 

        console.log(pokemon.data);
        
        /* recarrega a página */
        setTimeout(function () { location.reload() }, 3000);
    
    



    })
    .catch(function (error) {
        console.log(error.status); console.log(error.statusText);  
    });



}
















