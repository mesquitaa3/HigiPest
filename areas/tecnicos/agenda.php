<?php
session_start();

//verifica se é tecnico que está logado
if ($_SESSION['cargo'] != 'tecnico') {
    header("Location: /login.php");
    exit();
}

require_once __DIR__ . "/../../bd/config.php";

//obtem id do tecnico da sessao
$tecnico_id = $_SESSION['id'];

//select para nome do tecnico
$query_tecnico = "
    SELECT nome_tecnico 
    FROM tecnicos 
    WHERE id_tecnico = ?
";

//guardar id do tecnico
$tecnico_id = $_SESSION['id'];

//guardar o dia atual
$data_selecionada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

//select para agendamentos do técnico
$query_agendamentos = "
    SELECT agendamentos.*, contratos.*, clientes.nome_cliente AS nome_cliente 
    FROM agendamentos 
    JOIN contratos ON agendamentos.id_contrato = contratos.id_contrato
    JOIN clientes ON contratos.id_cliente = clientes.id_cliente
    WHERE agendamentos.tecnico = ? AND agendamentos.data_agendada = ?
";

//select para visitas do técnico
$query_visitas = "
SELECT visitas.*, contratos.*, clientes.nome_cliente AS nome_cliente
FROM visitas
JOIN contratos ON visitas.id_contrato = contratos.id_contrato
JOIN clientes ON contratos.id_cliente = clientes.id_cliente
WHERE visitas.id_tecnico = ? AND visitas.data_visita = ?
";

//agendamentos
$stmt = $conn->prepare($query_agendamentos);
$stmt->bind_param("is", $tecnico_id, $data_selecionada);
$stmt->execute();
$result_agendamentos = $stmt->get_result();

//verificar se existem agendamentos
$agendamentos = [];
if ($result_agendamentos->num_rows > 0) {
    while ($row = $result_agendamentos->fetch_assoc()) {
        // Serviço para os agendamentos
        $row['tipo'] = 'Serviço';
        $agendamentos[] = $row;
    }
} else {
    $mensagem = "Nenhum serviço agendado.";
}
$stmt->close();

//visitas
$stmt = $conn->prepare($query_visitas);
$stmt->bind_param("is", $tecnico_id, $data_selecionada);
$stmt->execute();
$result_visitas = $stmt->get_result();

//verificar se existem visitas
$visitas = [];
if ($result_visitas->num_rows > 0) {
    while ($row = $result_visitas->fetch_assoc()) {
        // Visita para as visitas
        $row['tipo'] = 'Visita';
        $visitas[] = $row;
    }
} else {
    $mensagem_visitas = "Nenhuma visita agendada.";
}
$stmt->close();

//combinar os agendamentos com as visitas
$agendamentos_visitas = array_merge($agendamentos, $visitas);

// Ordenar por hora (mais cedo para mais tarde)
usort($agendamentos_visitas, function($a, $b) {
    $horaA = strtotime(($a['hora_servico'] ?? $a['hora_visita']));
    $horaB = strtotime(($b['hora_servico'] ?? $b['hora_visita']));
    return $horaA - $horaB;
});
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda do Técnico</title>
    <link rel="stylesheet" href="../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styles/styles.css">
</head>
<body>

<?php require_once 'menu.php'; ?> <!-- Inclui menu para o técnico -->

    <div class="container mt-5">
        <!-- Exibe a mensagem de sucesso, se existir, e a remove da sessão -->
        <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
            <div class="alert alert-success text-center" role="alert">
                <?php echo htmlspecialchars($_SESSION['mensagem_sucesso']); ?>
            </div>
            <?php unset($_SESSION['mensagem_sucesso']); // Remove a mensagem para não exibi-la novamente ?>
        <?php endif; ?>

        <h2>Agenda do Técnico</h2>

        <?php if (isset($mensagem)): ?>
            <div class="alert alert-warning"><?php echo htmlspecialchars($mensagem); ?></div>
        <?php endif; ?>

        <?php if (isset($mensagem_visitas)): ?>
            <div class="alert alert-warning"><?php echo htmlspecialchars($mensagem_visitas); ?></div>
        <?php endif; ?>

        <!-- Botões para avançar e recuar nos dias -->
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-primary" onclick="alterarData(-1)">Recuar</button>
            <span id="data-selecionada"><?php echo htmlspecialchars($data_selecionada); ?></span>
            <button class="btn btn-primary" onclick="alterarData(1)">Avançar</button>
        </div>

        <?php if (!empty($agendamentos_visitas)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Observações</th>
                        <th>Pragas/Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos_visitas as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($item['nome_cliente']); ?></td>
                            <td>
                                <?php
                                    if ($item['tipo'] == 'Serviço' && isset($item['data_agendada'])) {
                                        echo htmlspecialchars($item['data_agendada']);
                                    } elseif ($item['tipo'] == 'Visita' && isset($item['data_visita'])) {
                                        echo htmlspecialchars($item['data_visita']);
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    if ($item['tipo'] == 'Serviço' && isset($item['hora_servico'])) {
                                        echo htmlspecialchars($item['hora_servico']);
                                    } elseif ($item['tipo'] == 'Visita' && isset($item['hora_visita'])) {
                                        echo htmlspecialchars($item['hora_visita']);
                                    }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['observacoes']); ?></td>
                            <td>
                                <?php
                                    if ($item['tipo'] == 'Serviço' && isset($item['pragas_tratadas'])) {
                                        echo htmlspecialchars($item['pragas_tratadas']);
                                    } elseif ($item['tipo'] == 'Visita' && isset($item['tipo_visita'])) {
                                        echo htmlspecialchars($item['tipo_visita']);
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="relatorio.php?id=<?php echo $item['id_agendamento'] ?? $item['id_visita']; ?>" class="btn btn-info">Relatório</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="js/calendario.js"></script>

</body>
</html>