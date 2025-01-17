<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HigiPest</title>
    
    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">

</head>
<body class="d-flex flex-column" style="min-height: 100vh;">

    <?php require('components/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <!-- Cabeçalho -->
    <section id="home" class="hero text-white text-center py-5" style="background-color: #ff8800;">
        <div class="container">
            <h2 class="display-4 fw-bold">BEM VINDO À HIGIPEST</h2>
            <p class="lead">O seu parceiro especializado no controlo de pragas</p>
        </div>
    </section>

    <!-- cartões estão alinhados lado a lado -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Os Nossos Serviços e Informações</h2>
            <div class="row text-center">
                <!-- Pedir Orçamento -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm d-flex flex-column h-100">
                        <img src="/web/assets/img/orcamento.png" class="card-img-top" alt="Pedir Orçamento">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Pedir Orçamento</h5>
                            <p class="card-text">Peça o seu orçamento para o controlo de pragas na sua propriedade ou empresa.</p>
                            <a href="orcamento.php" class="btn mt-auto fw-bold" style="background-color: #ff8800;">Pedir Orçamento</a>
                        </div>
                    </div>
                </div>
                <!-- Onde Estamos -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm d-flex flex-column h-100">
                        <img src="/web/assets/img/mapa.png" class="card-img-top" alt="Ver no Mapa">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Onde Estamos</h5>
                            <p class="card-text">Consulte os nossos contactos e moradas, bem como informações sobre como chegar até nós, para mais detalhes e atendimento presencial.</p>
                            <a href="contactos.php" class="btn mt-auto fw-bold" style="background-color: #ff8800;">Ver no Mapa</a>
                        </div>
                    </div>
                </div>
                <!-- Serviços -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm d-flex flex-column h-100">
                        <img src="/web/assets/img/servicos.png" class="card-img-top" alt="Serviços">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Serviços</h5>
                            <p class="card-text">Descubra todos os serviços que disponibilizamos para eliminar pragas e manter o seu ambiente seguro.</p>
                            <a href="servicos.php" class="btn mt-auto fw-bold" style="background-color: #ff8800;">Ver Serviços</a>
                        </div>
                    </div>
                </div>
                <!-- Pragas -->
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm d-flex flex-column h-100">
                        <img src="/web/assets/img/pragas.png" class="card-img-top" alt="Pragas">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Pragas</h5>
                            <p class="card-text">Saiba mais sobre as principais pragas que afetam residências e empresas, e como combatê-las.</p>
                            <a href="pragas.php" class="btn mt-auto fw-bold" style="background-color: #ff8800;">Ver Pragas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require('components/footer.php'); ?> <!-- Inclui o footer - footer.php -->
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script> <!-- menu dropdown funciona atraves deste script -->

</body>
</html>
