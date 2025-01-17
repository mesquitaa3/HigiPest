<?php
//conexao bd
require_once __DIR__ . "/bd/config.php";

// Consultar serviços visíveis na tabela `pragas`
$sql = "SELECT * FROM pragas WHERE visivel = 1 ORDER BY ordem ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HigiPest - Pragas</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    
</head>
<body class="d-flex flex-column" style="min-height: 100vh;">

    <!-- Menu -->
    <?php require('components/menu.php'); ?> <!-- Inclui o menu -->

    <!-- Cabeçalho -->
    <section class="text-white text-center py-5" style="background-color: #ff8800;">
        <div class="container">
            <h1 class="display-4 fw-bold">Pragas</h1>
            <p class="lead">Saiba mais sobre as pragas mais comuns e como lidar com elas de forma eficaz.</p>
        </div>
    </section>

    <!-- Texto sobre Pragas -->
    <section id="sobre-pragas" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Pragas? O que são?</h2>
            <p class="text-center mb-4">As pragas são organismos que interferem na saúde, segurança e bem-estar das pessoas, assim como nas atividades agrícolas e industriais. Podem incluir insetos, roedores, aves e até microrganismos.</p>
            <p class="text-center">O controlo de pragas é essencial para prevenir doenças, proteger estruturas e manter um ambiente seguro e saudável.</p>
        </div>
    </section>

    <!-- Serviços -->
    <section id="tipos-pragas" class="py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            // Verificar se há pragas para exibir
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="' . htmlspecialchars($row['img']) . '" class="card-img-top" alt="' . htmlspecialchars($row['praga']) . '" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">' . htmlspecialchars($row['praga']) . '</h5>
                                <p class="card-text flex-grow-1">' . htmlspecialchars($row['descricao']) . '</p>
                                <a href="#" class="btn mt-auto fw-bold" style="background-color: #ff8800;">Saber Mais</a>
                            </div>
                        </div>
                    </div>
                    ';
                }
            } else {
                echo '<p class="text-center">Nenhuma praga disponível no momento.</p>';
            }

            // Fechar a conexão
            $conn->close();
            ?>
        </div>
    </div>
    </section>


    <!-- Footer -->
    <?php require('components/footer.php'); ?> <!-- Inclui o footer aqui -->

    <!-- Bootstrap JS, Popper.js e jQuery -->
     
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
