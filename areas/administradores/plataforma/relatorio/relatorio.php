<?php
// Início da sessão e verificação de permissão
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

// Conexão com o banco de dados
require_once __DIR__ . "/../../../../bd/config.php";

// Obter o ID do agendamento ou visita da URL
$id = $_GET['id'] ?? null;

// Inicializar arrays para visita e agendamento
$visita = [];
$agendamento = [];

// Verifica se o ID foi fornecido
if ($id) {
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Consulta para buscar dados da visita
    $query_visita = "
        SELECT visitas.*, contratos.*, clientes.nome_cliente, tecnicos.nome_tecnico
        FROM visitas
        JOIN contratos ON visitas.id_contrato = contratos.id_contrato
        JOIN clientes ON contratos.id_cliente = clientes.id_cliente
        LEFT JOIN tecnicos ON visitas.id_tecnico = tecnicos.id_tecnico
        WHERE visitas.id_visita = ?
    ";

    $stmt = $conn->prepare($query_visita);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Se encontrar uma visita com o ID, armazena os dados
    if ($result->num_rows > 0) {
        $visita = $result->fetch_assoc();
    }

    // Se não encontrar visita, busca dados do agendamento
    if (empty($visita)) {
        $query_agendamento = "
            SELECT agendamentos.*, contratos.*, clientes.nome_cliente, tecnicos.nome_tecnico
            FROM agendamentos
            JOIN contratos ON agendamentos.id_contrato = contratos.id_contrato
            JOIN clientes ON contratos.id_cliente = clientes.id_cliente
            LEFT JOIN tecnicos ON agendamentos.tecnico = tecnicos.id_tecnico
            WHERE agendamentos.id_agendamento = ?
        ";

        $stmt = $conn->prepare($query_agendamento);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Se encontrar um agendamento com o ID, armazena os dados
        if ($result->num_rows > 0) {
            $agendamento = $result->fetch_assoc();
        }
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conn->close();
} else {
    echo '<div class="alert alert-warning text-center mt-5">Nenhum ID fornecido!</div>';
    exit();
}

// Atribuir automaticamente o ID do técnico com base no agendamento ou visita
$id_tecnico = null;
if (!empty($visita)) {
    $id_tecnico = $visita['id_tecnico']; // Se for uma visita, pegar o técnico da visita
} elseif (!empty($agendamento)) {
    $id_tecnico = $agendamento['tecnico']; // Se for um agendamento, pegar o técnico do agendamento
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório</title>
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
</head>
<body class="bg-light">
<?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?>
    <div class="container mt-5">
        <?php if (!empty($visita)) { ?>
            <h2 class="text-center mb-5">Relatório da Visita</h2>
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Detalhes da Visita</div>
                <div class="card-body">
                    <p><strong>Cliente:</strong> <?= htmlspecialchars($visita['nome_cliente']) ?></p>
                    <p><strong>Estabelecimento:</strong> <?= htmlspecialchars($visita['estabelecimento_contrato']) ?></p>
                    <p><strong>Morada:</strong> <?= htmlspecialchars($visita['morada_contrato']) ?></p>
                    <p><strong>Hora:</strong> <?= htmlspecialchars($visita['hora_visita']) ?></p>
                    <p><strong>Técnico:</strong> <?= htmlspecialchars($visita['nome_tecnico']) ?></p>
                    <p><strong>Tipo de Visita:</strong> <?= htmlspecialchars($visita['tipo_visita']) ?></p>
                    <p><strong>Observações:</strong> <?= htmlspecialchars($visita['observacoes']) ?></p>
                </div>
            </div>
        <?php } elseif (!empty($agendamento)) { ?>
            <h2 class="text-center mb-5">Relatório do Agendamento</h2>
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Detalhes do Agendamento</div>
                <div class="card-body">
                    <p><strong>Cliente:</strong> <?= htmlspecialchars($agendamento['nome_cliente']) ?></p>
                    <p><strong>Estabelecimento:</strong> <?= htmlspecialchars($agendamento['estabelecimento_contrato']) ?></p>
                    <p><strong>Morada:</strong> <?= htmlspecialchars($agendamento['morada_contrato']) ?></p>
                    <p><strong>Hora:</strong> <?= htmlspecialchars($agendamento['hora_servico']) ?></p>
                    <p><strong>Técnico:</strong> <?= htmlspecialchars($agendamento['nome_tecnico']) ?></p>
                    <p><strong>Serviço:</strong> <?= htmlspecialchars($agendamento['pragas_tratadas']) ?></p>
                    <p><strong>Observações:</strong> <?= htmlspecialchars($agendamento['observacoes']) ?></p>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning text-center">
                Nenhum registro encontrado para o ID fornecido.
            </div>
        <?php } ?>

        <div class="mt-5">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Gerar Relatório</div>
                <div class="card-body">
                <form action="gerar_relatorio.php" method="post">
                    <!-- Verificar se os valores das variáveis estão definidos antes de passar para os campos ocultos -->
                    <input type="hidden" name="id_cliente" value="<?= isset($agendamento['id_cliente']) ? htmlspecialchars($agendamento['id_cliente']) : htmlspecialchars($visita['id_cliente']) ?>">
                    <input type="hidden" name="id_estabelecimento" value="<?= isset($agendamento['id_contrato']) ? htmlspecialchars($agendamento['id_contrato']) : htmlspecialchars($visita['id_contrato']) ?>">
                    <input type="hidden" name="id_agendamento" value="<?= isset($agendamento['id_agendamento']) ? htmlspecialchars($agendamento['id_agendamento']) : '' ?>">
                    <input type="hidden" name="id_visita" value="<?= isset($visita['id_visita']) ? htmlspecialchars($visita['id_visita']) : '' ?>">
                    <input type="hidden" name="nome_tecnico" value="<?= htmlspecialchars($id_tecnico) ?>">
                    <input type="hidden" name="id_tecnico" value="<?= htmlspecialchars($id_tecnico) ?>">

                    <!-- Descrição do serviço -->
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição do Serviço</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Gerar Relatório</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
