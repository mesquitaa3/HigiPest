<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

//select para mostrar todos os contratos visiveis
$query = "SELECT contratos.*, clientes.nome_cliente FROM contratos 
          JOIN clientes ON contratos.id_cliente = clientes.id_cliente 
          WHERE contratos.visivel = 1";  
$result = $conn->query($query);



$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'contrato_adicionado') {
        $message = "Contrato adicionado com sucesso!";
    } elseif ($_GET['msg'] == 'erro_adicionando_contrato') {
        $message = "Erro ao adicionar contrato!";
    } elseif ($_GET['msg'] == 'contrato_ocultado') {
        $message = "Contrato ocultado com sucesso!";
    } elseif ($_GET['msg'] == 'erro_ocultando_contrato') {
        $message = "Erro ao ocultar Contrato!";
    } elseif ($_GET['msg'] == 'contrato_atualizado') {
        $message = "Contrato atualizado com sucesso!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratos</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Contratos</h2>

        <!--mensagem-->
        <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!--button adicionar novo contrato-->
        <div class="d-flex justify-content-between mb-4">
            <a href="criarcontrato.php" class="btn btn-primary">Adicionar novo contrato</a>
            <a href="contratosocultos.php" class="btn btn-secondary">Mostrar Contratos Ocultos</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome Cliente</th>
                        <th>Estabelecimento</th>
                        <th>Morada</th>
                        <th>Pragas</th>
                        <th>Meses</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //veriuficar se ha contratos para mostrar
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_botao = ($row['visivel'] == 1) ? "Ocultar" : "Mostrar";
                            $cor_botao = ($row['visivel'] == 1) ? "btn-danger" : "btn-success";

                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['nome_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['estabelecimento_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['morada_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['pragas_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['meses_contrato']) . '</td>
                                <td>
                                    <a href="vercontrato.php?id_contrato=' .$row['id_contrato'] . '" class="btn btn-primary btn-sm">Ver</a>
                                    <a href="editarcontrato.php?id_contrato=' . $row['id_contrato'] . '" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="ocultarcontrato.php?id_contrato=' . $row['id_contrato'] . '" class="btn ' . $cor_botao . ' btn-sm">' . $status_botao . '</a>
                                </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">Nenhum contrato disponível no momento.</td></tr>';
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Button voltar para index-site -->
        <div class="d-flex justify-content-between mb-4">
            <a href="/web/areas/administradores/index.php" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
