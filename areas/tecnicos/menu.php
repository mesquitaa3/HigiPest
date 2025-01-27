<?php

require_once __DIR__ . "/../../bd/config.php";

// Obter o ID do técnico da sessão
$tecnico_id = $_SESSION['id'];

// Consulta para buscar o nome do técnico
$query_tecnico = "
    SELECT nome_tecnico 
    FROM tecnicos 
    WHERE id_tecnico = ?
";
?>

<!-- menu-admin-page.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Área de Técnico</a> <!-- nome do admin que tem login feito -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="agenda.php">Agenda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="trabalhos_realizados.php">Trabalhos Realizados</a>
                </li>          
            </ul>
        </div>

        <div>
            <ul class="navbar-nav ms-auto"> <!-- ms-auto alinha os itens à direita -->
                <li class="nav-item">
                    <a href="../../login.php" class="nav-link d-flex align-items-center">
                        <img src="../../assets/img/logout.png" alt="Logout" class="img-fluid" style="max-width: 24px; height: auto;">
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>
