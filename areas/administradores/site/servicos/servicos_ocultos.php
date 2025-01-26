<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

// Consultar apenas os serviços ocultos
$query = "SELECT * FROM servicos WHERE visivel = 0";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços Ocultos</title>

    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

<?php require_once __DIR__ . "/../menu.php"; ?> <!-- Inclui menu -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Serviços Ocultos</h2>

        <?php
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            echo "<div class='alert alert-{$message['type']}' role='alert'>{$message['message']}</div>";
            unset($_SESSION['flash_message']);
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
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_servico']) . '</td>
                                <td>' . htmlspecialchars($row['servico']) . '</td>
                                <td>' . htmlspecialchars($row['descricao']) . '</td>
                                <td><img src="' . htmlspecialchars($row['img']) . '" alt="' . htmlspecialchars($row['servico']) . '" style="width: 100px; height: 60px; object-fit: cover;"></td>
                                <td>' . htmlspecialchars($row['ordem']) . '</td>
                                <td>
                                    <a href="editar_servico.php?id_servico=' . $row['id_servico'] . '" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="ocultar_servico.php?id=' . $row['id_servico'] . '" class="btn btn-success btn-sm">Mostrar</a>
                                </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">Nenhum serviço oculto no momento.</td></tr>';
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mb-4">
            <a href="servicos.php" class="btn btn-primary">Voltar para Serviços Visíveis</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
