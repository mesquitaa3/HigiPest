<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");
    exit();
}

// Incluir o arquivo de configuração para conectar ao banco
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Verificar se o id_contrato foi passado pela URL
if (isset($_GET['id_contrato'])) {
    $id_contrato = $_GET['id_contrato'];

    // Adicionar depuração para verificar se o id_contrato é válido
    if (!is_numeric($id_contrato)) {
        echo "ID do contrato inválido!";
        exit;
    }

    // Consultar dados do contrato com o cliente
    $query = "
        SELECT contratos.*, clientes.nome_cliente, clientes.morada_cliente, clientes.email_cliente, clientes.telemovel_cliente 
        FROM contratos
        JOIN clientes ON contratos.id_cliente = clientes.id_cliente
        WHERE contratos.id_contrato = $id_contrato
    ";
    $result = $conn->query($query);

    // Verificar se o contrato foi encontrado
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Definir a variável $row com os dados do contrato
        $pragas = explode(',', $row['pragas_contrato']); // Separar as pragas em um array
    } else {
        echo "Contrato não encontrado para o id_contrato: " . $id_contrato; // Exibir erro com o ID do contrato
        exit;
    }
} else {
    echo "ID do contrato não fornecido!";
    exit;
}

// Consulta para buscar técnicos
$query_tecnicos = "SELECT id, nome FROM utilizadores WHERE cargo = 'tecnico'";
$result_tecnicos = $conn->query($query_tecnicos);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $data_agendada = $_POST['data_agendada'];
    $hora_servico = $_POST['hora_servico'];
    $observacoes = $_POST['observacoes'];
    $tecnico = $_POST['tecnico'];
    $pragas_selecionadas = isset($_POST['pragas']) ? implode(', ', $_POST['pragas']) : '';

    // Inserir no banco de dados
    $insert_query = "
        INSERT INTO agendamentos (id_contrato, data_agendada, hora_servico, observacoes, tecnico, pragas_tratadas)
        VALUES (?, ?, ?, ?, ?, ?)
    ";
    
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isssss", $id_contrato, $data_agendada, $hora_servico, $observacoes, $tecnico, $pragas_selecionadas);

    if ($stmt->execute()) {
        $message = "Serviço agendado com sucesso!";
    } else {
        $message = "Erro ao agendar serviço: " . $conn->error;
    }
    
    $stmt->close();
}
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
        <div class="row">
            <!-- Coluna da esquerda: Informações do Cliente (Agora à esquerda) -->
            <div class="col-md-6">
                <h3>Informações do Cliente</h3>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Nome:</strong> <?php echo htmlspecialchars($row['nome_cliente']); ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($row['email_cliente']); ?></li>
                    <li class="list-group-item"><strong>Telemóvel:</strong> <?php echo htmlspecialchars($row['telemovel_cliente']); ?></li>
                    <li class="list-group-item"><strong>Estabelecimento:</strong> <?php echo htmlspecialchars($row['estabelecimento_contrato']); ?></li>
                    <li class="list-group-item"><strong>Morada do Estabelecimento:</strong> <?php echo htmlspecialchars($row['morada_contrato']); ?></li>
                </ul>
            </div>

            <!-- Coluna da direita: Formulário de Agendamento (Agora à direita) -->
            <div class="col-md-6">
                <h2>Agendar Serviço para o Cliente: <?php echo htmlspecialchars($row['nome_cliente']); ?></h2>

                <?php if (isset($message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <!-- Formulário de agendamento -->
                <form method="POST" action="agendarservico.php?id_contrato=<?php echo $id_contrato; ?>">
                    <!-- Data do Serviço -->
                    <div class="mb-3">
                        <label for="data_agendada" class="form-label">Data do Serviço</label>
                        <input type="date" class="form-control" id="data_agendada" name="data_agendada" required>
                    </div>

                    <!-- Hora do Serviço -->
                    <div class="mb-3">
                        <label for="hora_servico" class="form-label">Hora do Serviço</label>
                        <input type="time" class="form-control" id="hora_servico" name="hora_servico" required>
                    </div>

                    <!-- Pragas -->
                    <div class="mb-3">
                        <label for="pragas" class="form-label">Pragas a tratar neste serviço</label>
                        <select multiple class="form-select" id="pragas" name="pragas[]" required>
                            <?php
                            foreach ($pragas as $praga) {
                                $praga = trim($praga);
                                echo "<option value='" . htmlspecialchars($praga) . "'>" . htmlspecialchars($praga) . "</option>";
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Pressione Ctrl (ou Cmd no Mac) para selecionar múltiplas pragas.</small>
                    </div>

                    <!-- Observações -->
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                    </div>

                <!-- Técnico Responsável -->
                <div class="mb-3">
                    <label for="tecnico" class="form-label">Técnico Responsável</label>
                    <select class="form-select" id="tecnico" name="tecnico" required>
                        <option value="">Selecione um técnico</option>
                        <?php
                        if ($result_tecnicos->num_rows > 0) {
                            while ($row_tecnico = $result_tecnicos->fetch_assoc()) {
                                echo "<option value='" . $row_tecnico['id'] . "'>" . htmlspecialchars($row_tecnico['nome']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                    <button type="submit" class="btn btn-primary">Agendar Serviço</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
