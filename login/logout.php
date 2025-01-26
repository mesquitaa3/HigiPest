<?php
//iniciar a sessão
session_start();

//destruir todas as variáveis de sessão
session_unset();

//destruir a sessão
session_destroy();

//redireciona utilizador para a página de login
header("Location: ../login.php");
exit();
?>