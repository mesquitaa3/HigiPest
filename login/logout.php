<?php
// Iniciar a sessão
session_start();

// Destruir todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar o utilizador para a página de login
header("Location: /web/login.php");
exit();
?>