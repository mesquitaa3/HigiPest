<?php
// Calcular caminho relativo dinâmico
$currentDir = dirname($_SERVER['PHP_SELF']); // Diretório atual da página
$rootDir = '/web'; // Raiz do sistema sem incluir /areas/
$relativePath = str_repeat('../', substr_count($currentDir, '/') - substr_count($rootDir, '/'));

// Agora vamos tratar os links para garantir que a estrutura de diretórios seja corrigida.
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $relativePath ?>areas/administradores/index.php">Administração</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $relativePath ?>areas/administradores/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $relativePath ?>areas/administradores/plataforma/agenda/agenda.php">Agenda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $relativePath ?>areas/administradores/plataforma/agenda/agendamento.php">Agendamento</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="dropdownClientes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Clientes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownClientes">
                        <li><a class="dropdown-item" href="<?= $relativePath ?>areas/administradores/plataforma/clientes/tabelaclientes.php">Clientes</a></li>
                        <li><a class="dropdown-item" href="<?= $relativePath ?>areas/administradores/plataforma/contratos/tabelacontratos.php">Contratos</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $relativePath ?>areas/administradores/plataforma/trabalhos/trabalhos_realizados.php">Trabalhos Realizados</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownConfiguracoes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Configurações
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownConfiguracoes">
                        <li><a class="dropdown-item" href="<?= $relativePath ?>areas/administradores/site/index.php">Site</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div>
            <ul class="navbar-nav ms-auto"> <!-- ms-auto alinha os itens à direita -->
                <li class="nav-item">
                    <a href="<?= $relativePath ?>login/logout.php" class="nav-link d-flex align-items-center">
                        <img src="../../../../assets/img/logout.png" alt="Logout" class="img-fluid" style="max-width: 24px; height: auto;">
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
