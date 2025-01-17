<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, redireciona para o login
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/styles/styles.css">

        <style>
            .btn-index {
                width: 300px;
                height: 300px;
                font-size: 40px; /* Ajuste do tamanho da fonte para caber dentro do botão */
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                border-radius: 10px;
                background-color: #ff8800;
                text-decoration: none;
                color: black;
                transition: box-shadow 0.3s ease; /* Adiciona uma transição suave */
            }

            .btn-index:hover {
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra pequena e suave */
            }
        </style>
</head>
<body>

    <?php require('menu-index.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-4">Site</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <!-- Editar Equipa -->
            <div class="col">
                <div class="d-flex align-items-center justify-content-center">
                    <a href="equipa/equipa.php" class="btn-index">Editar Equipa</a>
                </div>
            </div>

            <!-- Editar Serviços -->
            <div class="col">
                <div class="d-flex align-items-center justify-content-center">
                    <a href="servicos/servicos.php" class="btn-index">Editar Serviços</a>
                </div>
            </div>

            <!-- Editar Pragas -->
            <div class="col">
                <div class="d-flex align-items-center justify-content-center">
                    <a href="pragas/pragas.php" class="btn-index">Editar Pragas</a>
                </div>
            </div>

            <!-- Editar Contactos -->
            <div class="col">
                <div class="d-flex align-items-center justify-content-center">
                    <a href="contactos/contactos.php" class="btn-index">Editar Contactos</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
