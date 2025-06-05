<?php
          
session_unset();           // Limpa todas as variáveis de sessão
session_destroy();         // Destroi a sessão atual


header("Location:./login.php");
exit();


?>