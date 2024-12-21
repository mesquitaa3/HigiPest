<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  //se não for administrador, volta para o login
    exit();
}

//conexao bd
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

//seleciona todos os contratos ocultos (visivel = 0)
$query = "SELECT contratos.*, clientes.nome_cliente FROM contratos 
          JOIN clientes ON contratos.id_cliente = clientes.id_cliente 
          WHERE contratos.visivel = 0";  
$result = $conn->query($query);

//mensagem ao ocultar contrato
$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'contrato_ocultado') {
        $message = "Contrato ocultado com sucesso!";
    } elseif ($_GET['msg'] == 'erro_ocultar_contrato') {
        $message = "Erro ao ocultar contrato!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratos Ocultos</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Contratos Ocultos</h2>

        <!-- mensagem ao ativar contrato -->
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome Cliente</th>
                        <th>Estabelecimento</th>
                        <th>Morada Estabelecimento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //verifica se ha contratos ocultos
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['nome_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['estabelecimento_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['morada_contrato']) . '</td>
                                <td>
                                    <a href="mostrarcontrato.php?mostrar=' . $row['id_contrato'] . '" class="btn btn-success btn-sm">Mostrar</a>
                                </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">Nenhum contrato oculto no momento.</td></tr>';
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Button voltar para index-site -->
        <div class="d-flex justify-content-between mb-4">
            <a href="tabelacontratos.php" class="btn btn-primary">Voltar para Contratos</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
