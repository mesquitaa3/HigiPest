<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, volta para o login
    exit();
}

// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Definir o mês atual
$mes_atual = 'Dezembro';  // Exemplo: Dezembro

$query = "
    SELECT contratos.*, clientes.nome_cliente 
    FROM contratos
    JOIN clientes ON contratos.id_cliente = clientes.id_cliente
    WHERE contratos.visivel = 1 AND contratos.meses_contrato LIKE '%Dezembro%'
";


$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Serviços</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Agendamento de Serviços - Mês de <?php echo $mes_atual; ?></h2>

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
                    // Verificar se há contratos para o mês atual
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['nome_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['estabelecimento_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['morada_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['pragas_contrato']) . '</td>
                                <td>' . htmlspecialchars($row['meses_contrato']) . '</td>
                                <td>
                                    <a href="agendarservico.php?id_contrato=' . $row['id_contrato'] . '" class="btn btn-primary btn-sm">Agendar Serviço</a>
                                </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">Nenhum serviço pendente para este mês.</td></tr>';
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
