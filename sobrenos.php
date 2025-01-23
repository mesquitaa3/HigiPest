<?php
//conexao bd
require_once __DIR__ . "/bd/config.php";

// Consultar serviços visíveis na tabela `equipa`
$sql = "SELECT * FROM equipa WHERE visivel = 1 ORDER BY ordem ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HigiPest - Sobre Nós</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="assets/styles/bootstrap.css">
    <link rel="stylesheet" href="assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles/styles.css">
    
</head>
<body>

    <!-- Menu -->
    <?php require('components/menu.php'); ?> <!-- Inclui o menu aqui -->

    <!-- Cabeçalho -->
    <section id="hero" class=" text-white text-center py-5" style="background-color: #ff8800;">
        <div class="container">
            <h1 class="display-4 fw-bold">Sobre Nós</h1>
            <p class="lead">Conheça a nossa missão, os nossos valores e como trabalhamos.</p>
        </div>
    </section>

    <!-- Sobre Nós -->
    <section id="about" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Quem Somos</h2>
            <div class="row align-items-center">
                <!-- Texto sobre a empresa -->
                <div class="col-lg-6 mb-4">
                    <h3>A Nossa História</h3>
                    <p>
                        Fundada em 2024, a nossa empresa é especializada no controlo de pragas e na desinfestação de ambientes, com o objetivo de proporcionar um espaço seguro e saudável para residências e empresas.
                    </p>
                    <p>
                        Através da nossa equipa de profissionais altamente qualificados e com vasto conhecimento na área, oferecemos soluções eficazes e personalizadas para eliminar pragas, garantindo a proteção da saúde e o bem-estar dos nossos clientes. Somos reconhecidos pelo nosso compromisso com a excelência no serviço e pelo atendimento próximo e personalizado.
                    </p>
                    <p>
                        Utilizando as mais avançadas tecnologias e métodos sustentáveis, asseguramos que as nossas intervenções não resolvam apenas o problema de forma eficiente, mas também respeitem o meio ambiente e a segurança das pessoas.
                    </p>
                </div>

                <!-- Imagem -->
                <div class="col-lg-6">
                    <img src="assets/img/servicos.png" class="img-fluid rounded shadow" alt="HigiPest"> <!-- criar img 600x400 -->
                </div>
            </div>
        </div>
    </section>

            <!-- Missão, Visão e Valores -->
                <section id="mission" class="bg-light py-5">
                    <div class="container">
                    <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <h3 style="color: #ff8800;">Missão</h3>
                    <p>A nossa missão é proteger pessoas e propriedades, oferecendo soluções inovadoras e seguras no controlo de pragas. Trabalhamos para garantir ambientes livres de riscos à saúde, utilizando tecnologias eficientes e métodos sustentáveis.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h3 style="color: #ff8800;">Visão</h3>
                    <p>A nossa visão é ser a empresa líder em desinfestação e controlo de pragas, reconhecida pela excelência dos nossos serviços e pelo nosso compromisso com a responsabilidade ambiental. Buscamos sempre inovar para proporcionar os melhores resultados aos nossos clientes.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h3 style="color: #ff8800;">Valores</h3>
                    <p>Os nossos valores incluem o compromisso com a satisfação do cliente, a inovação contínua dos nossos serviços, o respeito pelo meio ambiente e a prática de ética profissional em todas as nossas ações.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Equipa --> <!-- espaço de equipa tem que estar ligado à base de dados para poder ser alterado a partir da página de administração -->
    <section id="team" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">A Nossa Equipa</h2>
        <div class="row d-flex justify-content-center align-items-center">
            <?php

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Exibir cada membro da equipe
                    echo '<div class="col-md-4 text-center mb-4">';
                    echo '<img src="' . htmlspecialchars($row['img']) . '" class="rounded-circle border border-bg border-1 img-fluid" alt="' . htmlspecialchars($row['nome_membro']) . '" style="max-width: 150px; height: auto; object-fit: cover;">';
                    echo '<h5>' . htmlspecialchars($row['nome_membro']) . '</h5>';
                    echo '<p class="text-muted">' . htmlspecialchars($row['funcao']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center text-muted">Nenhum membro da equipa foi encontrado.</p>';
            }

            // Fechar conexão com a base de dados
            mysqli_close($conn);
            ?>
        </div>
    </div>
</section>


    <!-- Footer -->
    <?php require('components/footer.php'); ?> <!-- Inclui o footer aqui -->

    <!-- Bootstrap JS, Popper.js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script> <!-- dropdown menu -->

</body>
</html>
