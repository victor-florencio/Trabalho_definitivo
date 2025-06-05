<?php

include("code/loginC.php");
include("code/conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'><rect width='200' height='200' fill='black'/><text x='50%' y='50%' font-family='Roboto' font-size='100' fill='white' text-anchor='middle' dominant-baseline='middle'>V </text> </svg>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="css/login.css" />
  <!-- <link rel="stylesheet" href="css/navlogin.css" /> -->
</head>

<body>
  
<header>
   
    <nav>
        <div class="nav-container">
            <a href="#" class="logo">Treedom</a>
            
        </div>
    </nav>


</header>



  <div class="login-wrapper">
    
    <form action="login.php" method="POST" class="login-form">
      <a name="backbtn" href="index.html" class="back-button">← Voltar</a>
      <h2>Entrar</h2>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"  required />
      </div>

      <div class="form-group">
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required />
      </div>

      <button type="submit" class="btn-entrar" name="btns">Entrar</button>

      <div class="login-footer">
        <span>Não possui uma conta?</span>
        <a href="cadastro.php">Cadastre-se</a>
      </div>

      <div class="error-msg">
        <?php 
          if(isset($erros)){
            foreach ($erros as $erro) {
              echo $erro . "<br>";
            }
          }
        ?>
      </div>
    </form>
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