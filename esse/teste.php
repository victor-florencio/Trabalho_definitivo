<?php
include ("code/conexao.php");
include ("code/loginC.php");
 session_start();

 $id_user = $_SESSION['id'];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
   if(isset($_POST['ganhar'])){
 $cards = [];
 $sql = "SELECT * FROM cards ORDER BY RAND() LIMIT 2";
 $result = $conexao->query($sql);
 while($linha = $result->fetch_assoc()){
    $cards[] = $linha;
 }

 foreach($cards as $card){
    $sql_in = "INSERT INTO user_cards (id_user, id_card) VALUES ('$id_user' , {$card['id']})";
    $conexao->query($sql_in);
 }
}
}
 $card_users = [];
 $sql_todes = "SELECT c.nome, c.img FROM user_cards cc JOIN cards c ON cc.id_card = c.id WHERE cc.id_user = $id_user";
 $result_todes = $conexao->query($sql_todes);
 while($linha = $result_todes->fetch_assoc()){
 $card_users[] = $linha;
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
</head>
<body>
   <form action="teste.php" method="post">
    <button type="submit" name="ganhar">Clica aqui</button>
    <h1> SUAS NOVASS CARTAS</h1>
   <?php foreach($cards as $card):?>
      <h3><?= htmlspecialchars($card['nome']) ?></h3>
      <img src="<?= htmlspecialchars($card['img']) ?>">
   <?php endforeach;?>
   </form>
</body>
</html><?php