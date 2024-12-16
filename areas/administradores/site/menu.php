<!-- menu-admin-page.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/web/areas/administradores/index.php">Administração</a> <!-- nome do admin que tem login feito -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/web/areas/administradores/index.php">Voltar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/web/areas/administradores/editar_index.php">Página Inicial</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownClientes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Sobre Nos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownClientes">
                        <li><a class="dropdown-item" href="#">Editar Página</a></li> <!-- editar texto, imagens da pagina -->
                        <li><a class="dropdown-item" href="#">Editar Equipa</a></li> <!-- tabela para adicionar, remover, editar equipa -->
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/web/areas/administradores/site/servicos.php">Serviços</a> <!-- tabela para adicionar, remover, editar serviços -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/web/areas/administradores/editar_pragas.php">Pragas</a><!-- tabela para adicionar, remover, editar pragas -->
                    </li>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/web/areas/administradores/editar_contactos.php">Contactos</a>
                    </li>
                </li>
                
                
                
            </ul>
        </div>

        <div>
            <ul class="navbar-nav ms-auto"> <!-- ms-auto alinha os itens à direita -->
                <li class="nav-item">
                    <a href="/web/login/logout.php" class="nav-link d-flex align-items-center">
                        <img src="/web/areas/administradores/assets/img/logout.png" alt="Logout" class="img-fluid" style="max-width: 24px; height: auto;">
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>
