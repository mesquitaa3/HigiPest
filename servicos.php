<?php
// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php"); //script de acesso à base de dados

// Consultar serviços visíveis na tabela `servicos`
$sql = "SELECT * FROM servicos WHERE visivel = 1 ORDER BY ordem ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HigiPest - Serviços</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    
</head>
<body class="d-flex flex-column" style="min-height: 100vh;">

    <!-- menu -->
    <?php require('components/menu.php'); ?> <!-- Inclui o menu aqui -->

    <!-- cabeçalho -->
    <section id="hero" class="text-white text-center py-5" style="background-color: #ff8800;">
        <div class="container">
            <h1 class="display-4 fw-bold">Serviços</h1>
            <p class="lead">Eliminamos pragas de forma eficaz e segura. Proteja a sua casa ou empresa.</p>
        </div>
    </section>

    <!-- Serviços -->
    <section id="tipos-pragas" class="py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="' . htmlspecialchars($row['img']) . '" class="card-img-top" alt="' . htmlspecialchars($row['servico']) . '" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">' . htmlspecialchars($row['servico']) . '</h5>
                                <p class="card-text flex-grow-1">' . htmlspecialchars($row['descricao']) . '</p>
                                <a href="#" class="btn mt-auto fw-bold" style="background-color: #ff8800;">Soluções</a>
                            </div>
                        </div>
                    </div>
                    ';
                }
            } else {
                echo '<p class="text-center">Nenhum serviço disponível no momento.</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>
    </section>


    <!-- Footer -->
    <?php require('components/footer.php'); ?> <!-- Inclui o footer aqui -->

    <!-- Bootstrap JS, Popper.js e jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
