<?php
session_start();
if ($_SESSION['cargo'] != 'tecnico') {
    header("Location: login.php");  // Se não for técnico, redireciona para o login
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnico</title>
    
    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/tecnicos/menu.php'); ?>


    <div class="container mt-5">
        <p>técnico</p>
        <a href="/web/login/logout.php" class="btn btn-danger">Sair</a>
    </div>
</body>
</html>
