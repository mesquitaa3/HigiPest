<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");  //se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

// Consultar os serviços
$query = "SELECT * FROM servicos WHERE visivel = 1";
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

<?php require_once __DIR__ . "/../menu.php"; ?> <!-- Inclui menu -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Serviços</h2>

        <!-- button criar serviço -->
        <div class="d-flex justify-content-between mb-4">
            <a href="criar_servicos.php" class="btn btn-primary">Criar Novo Serviço</a>
            <a href="servicos_ocultos.php" class="btn btn-primary">Mostrar Serviços Ocultos</a>
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
                        <th>Serviço</th>
                        <th>Descrição</th>
                        <th>Foto</th>
                        <th>Ordem</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Verificar se há serviços para exibir
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_botao = ($row['visivel'] == 1) ? "Ocultar" : "Mostrar";
                            $cor_botao = ($row['visivel'] == 1) ? "btn-danger" : "btn-success";
                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_servico']) . '</td>
                                <td>' . htmlspecialchars($row['servico']) . '</td>
                                <td>' . htmlspecialchars($row['descricao']) . '</td>
                                <td><img src="' . htmlspecialchars($row['img']) . '" alt="' . htmlspecialchars($row['servico']) . '" style="width: 100px; height: 60px; object-fit: cover;"></td>
                                <td>' . htmlspecialchars($row['ordem']) . '</td>
                                <td>
                                    <a href="editar_servico.php?id_servico=' . $row['id_servico'] . '" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="ocultar_servico.php?id=' . $row['id_servico'] . '" class="btn ' . $cor_botao . ' btn-sm">' . $status_botao . '</a>
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
            <a href="../index.php" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
