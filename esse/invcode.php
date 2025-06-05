
<?php

if(isset($_POST['finalizar'])){
    include_once("code/loginC.php");

$cards = array(
    ['imagem'=> 'agua.png','nome' => 'Àgua'],
['imagem'=> 'sol.png','nome' => 'Sol'],
['imagem'=> 'vento.png','nome' => 'Vento'],
['imagem'=> 'saitama.png','nome' => 'Saitama'],
['imagem'=> 'genos.png','nome' => 'Genos']
);

shuffle($cards);

$cards_sorteados = array_slice($cards, 0, 2);

$usuario = $_SESSION['email'];

//só pra garantir que o array do usuário tá criado
if (!isset($_SESSION['usuarios_cards'])) {
    $_SESSION['usuarios_cards'] = [];
}

if (!isset($_SESSION['usuarios_cards'][$usuario])) {
    $_SESSION['usuarios_cards'][$usuario] = [];
}

// Armazena os cards sorteados no array do usuário, o array_merge mescla com o próximo que vier, precisa das garantias do array do usuário, pois não recebe valor nulo!
$_SESSION['usuarios_cards'][$usuario] = array_merge(
    $_SESSION['usuarios_cards'][$usuario],
    $cards_sorteados
);
// $_SESSION['usuarios_cards'][$usuario]=$cards_sorteados; só tirar o comentário se quiser reiniciar a quantidade de cards
foreach($_SESSION['usuarios_cards'][$usuario] as $card){
?>
<!-- 
 <div class="card-container">
            <a class="card">
                <img src="<?php echo $card['imagem']?>" alt="" class="card-image">
                <div class="card-content">
                    <h3 class="card-title"><?php echo $card['nome'] ?></h3>
                   
                </div>
            </a>
 </div> -->


</div>
<!-- AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA FILHA DA PUTAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA-->
<?php }  }
?>


