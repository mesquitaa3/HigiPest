<?php
session_start();

// Verificar se o técnico está logado
if ($_SESSION['cargo'] != 'tecnico') {
    header("Location: /web/login.php");
    exit();
}

// Incluir o arquivo de configuração para conectar ao banco de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Pegar o ID do técnico logado
$tecnico_id = $_SESSION['id']; // ID do técnico logado

// Consultar os agendamentos para o técnico
$query_agendamentos = "
    SELECT agendamentos.*, contratos.*, cliente.nome AS nome_cliente 
    FROM agendamentos 
    JOIN contratos ON agendamentos.id_contrato = contratos.id_contrato
    JOIN utilizadores AS cliente ON contratos.id_cliente = cliente.id
    WHERE agendamentos.tecnico = ?
";

$stmt = $conn->prepare($query_agendamentos);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result_agendamentos = $stmt->get_result();

// Verificar se existem agendamentos para o técnico
$agendamentos = [];
if ($result_agendamentos->num_rows > 0) {
    while ($row = $result_agendamentos->fetch_assoc()) {
        $agendamentos[] = $row;
    }
} else {
    $mensagem = "Nenhum serviço agendado para este técnico.";
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda do Técnico</title>
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/tecnicos/menu.php'); ?> <!-- Inclui menu para o técnico -->

    <div class="container mt-5">
        <h2>Agenda do Técnico</h2>

        <?php if (isset($mensagem)): ?>
            <div class="alert alert-warning"><?php echo htmlspecialchars($mensagem); ?></div>
        <?php endif; ?>

        <!-- Exibir os agendamentos -->
        <?php if (!empty($agendamentos)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Observações</th>
                        <th>Pragas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $agendamento): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($agendamento['nome_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['data_agendada']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['hora_servico']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['observacoes']); ?></td>
                            <td><?php echo htmlspecialchars($agendamento['pragas_tratadas']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
