<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  //se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

// Consultar os serviços
$query = "SELECT * FROM pragas WHERE visivel = 1";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/site/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Pragas</h2>

        <!-- button criar serviço e mostrar pragas ocultas -->
        <div class="d-flex justify-content-between mb-4">
            <a href="criar_pragas.php" class="btn btn-primary">Criar Nova Praga</a>
            <a href="pragas_ocultas.php" class="btn btn-primary">Pragas Ocultas</a>
        </div>

        <!-- alerta de sucesso -->
        <?php
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            echo "<div class='alert alert-{$message['type']}' role='alert'>{$message['message']}</div>";
            unset($_SESSION['flash_message']); //remove a mensagem da sessão ao dar refresh
        }
        ?>


        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Praga</th>
                        <th>Descrição</th>
                        <th>Foto</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Verificar se há pragas para exibir
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_botao = ($row['visivel'] == 1) ? "Ocultar" : "Mostrar";
                            $cor_botao = ($row['visivel'] == 1) ? "btn-danger" : "btn-success";
                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_praga']) . '</td>
                                <td>' . htmlspecialchars($row['praga']) . '</td>
                                <td>' . htmlspecialchars($row['descricao']) . '</td>
                                <td><img src="' . htmlspecialchars($row['img']) . '" alt="' . htmlspecialchars($row['praga']) . '" style="width: 100px; height: 60px; object-fit: cover;"></td>
                                <td>
                                    <a href="editar_praga.php?id_praga=' . $row['id_praga'] . '" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="ocultar_praga.php?id=' . $row['id_praga'] . '" class="btn ' . $cor_botao . ' btn-sm">' . $status_botao . '</a>
                                </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Nenhum serviço disponível no momento.</td></tr>';
                    }

                    // Fechar a conexão
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Button voltar para index-site -->
        <div class="d-flex justify-content-between mb-4">
            <a href="/web/areas/administradores/site/index.php" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
