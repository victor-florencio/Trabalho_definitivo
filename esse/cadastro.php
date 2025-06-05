<?php
include("code/formC.php");
include_once("code/conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'><rect width='200' height='200' fill='black'/><text x='50%' y='50%' font-family='Roboto' font-size='100' fill='white' text-anchor='middle' dominant-baseline='middle'>V </text> </svg>">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro</title>
  <link rel="stylesheet" href="cus.css">
</head>
<body>

<header>
   
   <nav>
        <div class="nav-container">
            <a href="#" class="logo">Treedom</a>
            
        </div>
    </nav>


</header>

 <div class="form-container">
    <a href="index.html" class="back-button">← Voltar</a>
    <form action="cadastro.php" method="POST" class="form-box">
      <h2>Cadastro</h2>

      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="senha">Senha</label>
      <input type="password" id="senha" name="senha" required>

      <button type="submit" class="btn-cadastro" name="btn">Cadastrar</button>
    </form>
 
  
      <div class="login-link">
        <?php 
        if(isset($erros)){
          foreach ($erros as $erro) {
      echo $erro . "<br>";
    }
        }
        ?>
      <a href="login.php"><span>Já possui uma conta?</span></a>
    </div>
  </div>


<script>
    
    function togglePassword() {
      const senhaInput = document.getElementById('senha');
      const toggleIcon = document.querySelector('.toggle-password i');
      if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        senhaInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }
  </script>

</body>
</html>