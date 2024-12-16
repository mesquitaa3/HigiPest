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
                    <a class="nav-link" href="/web/areas/administradores/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/web/areas/administradores/plataforma/agenda.php">Agenda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/web/areas/administradores/agendamento.php">Agendamento</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownClientes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Clientes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownClientes">
                        <li><a class="dropdown-item" href="#">Clientes</a></li>
                        <li><a class="dropdown-item" href="#">Contratos</a></li>
                        <li><a class="dropdown-item" href="#">Propostas</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownTrabalhos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Trabalhos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownTrabalhos">
                        <li><a class="dropdown-item" href="#">Trabalhos Realizados</a></li>
                        <li><a class="dropdown-item" href="#">Trabalhos Agendados</a></li>
                        <li><a class="dropdown-item" href="#">Trabalhos Em Atraso</a></li>
                        <li><a class="dropdown-item" href="#">Trabalhos Solicitados</a></li>
                        <li><a class="dropdown-item" href="#">Trabalhos Extra</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownProdutos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Produtos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownProdutos">
                        <li><a class="dropdown-item" href="#">Produtos</a></li>
                        <li><a class="dropdown-item" href="#">Documentos - Produtos</a></li>
                        <li><a class="dropdown-item" href="#">Stock - Produtos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownListagens" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Listagens
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownListagens">
                        <li><a class="dropdown-item" href="#">Viaturas</a></li>
                        <li><a class="dropdown-item" href="#">Documentos</a></li>
                        <li><a class="dropdown-item" href="#">Equipa</a></li>
                        <li><a class="dropdown-item" href="#">Análises</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownConfiguracoes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Configurações
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownConfiguracoes">
                        <li><a class="dropdown-item" href="/web/areas/administradores/site/index.php">Site</a></li>
                        <li><a class="dropdown-item" href="#">Utilizadores</a></li>
                        <li><a class="dropdown-item" href="#">Serviços</a></li>
                        <li><a class="dropdown-item" href="#">Tratamentos</a></li>
                        <li><a class="dropdown-item" href="#">Equipamentos</a></li>
                        <li><a class="dropdown-item" href="#">Dispositivos</a></li>
                        <li><a class="dropdown-item" href="#">Classificação Dispositivos</a></li>
                    </ul>
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
