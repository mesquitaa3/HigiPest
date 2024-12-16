<?php
session_start();
if ($_SESSION['cargo'] != 'cliente') {
    header("Location: login.php");  // Se não for cliente, redireciona para o login
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente</title>
    
    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    
</head>
<body>
    <div class="container mt-5">
        <p>cliente</p>
        <a href="logout.php" class="btn btn-danger">Sair</a>
    </div>
</body>
</html>
