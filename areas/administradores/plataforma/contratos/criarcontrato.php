<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, volta para o login
    exit();
}

// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Buscar todos os clientes para o select
$query = "SELECT id_cliente, nome_cliente FROM clientes WHERE visivel = 1";
$result = $conn->query($query);

// Verificar se houve erro na consulta
if (!$result) {
    die("Erro na consulta SQL: " . $conn->error);
}

// Processar o formulário ao submeter
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter os dados do formulário
    $id_cliente = $_POST['cliente'];
    $morada_contrato = $_POST['morada_contrato'];
    $estabelecimento_contrato = $_POST['estabelecimento_contrato'];
    $pragas_contrato = isset($_POST['pragas_contrato']) ? implode(", ", $_POST['pragas_contrato']) : "";
    $meses_contrato = isset($_POST['meses_contrato']) ? implode(", ", $_POST['meses_contrato']) : "";
    $data_inicio_contrato = $_POST['data_inicio_contrato'];
    $observacoes_contrato = $_POST['observacoes_contrato'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $valor_contrato = isset($_POST['valor_contrato']) ? $_POST['valor_contrato'] : null;

    // Validações simples
    if (empty($id_cliente) || empty($morada_contrato) || empty($estabelecimento_contrato) || empty($data_inicio_contrato) || empty($tipo_contrato)) {
        $error_message = "Por favor, preencha todos os campos obrigatórios!";
    } else {
        // Preparar a consulta para evitar SQL Injection
        $insert_query = $conn->prepare("INSERT INTO contratos 
            (id_cliente, estabelecimento_contrato, morada_contrato, pragas_contrato, meses_contrato, data_inicio_contrato, observacoes_contrato, tipo_contrato, valor_contrato, visivel) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");

        // Bind dos parâmetros
        $insert_query->bind_param("isssssssd", $id_cliente, $estabelecimento_contrato, $morada_contrato, $pragas_contrato, $meses_contrato, $data_inicio_contrato, $observacoes_contrato, $tipo_contrato, $valor_contrato);

        // Executar a consulta
        if ($insert_query->execute()) {
            header("Location: tabelacontratos.php?msg=contrato_criado");
            exit();
        } else {
            $error_message = "Erro ao criar contrato: " . $insert_query->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Contrato</title>
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
</head>
<body>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu -->

<div class="container mt-5">
    <h2 class="text-center mb-4">Criar Contrato</h2>

    <!-- Mensagem de erro -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form action="criarcontrato.php" method="POST">
        <div class="form-group mb-3">
            <label for="cliente">Cliente:</label>
            <select id="cliente" name="cliente" class="form-control" required>
                <option value="">Selecione um cliente</option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['id_cliente']) . '">' . htmlspecialchars($row['nome_cliente']) . '</option>';
                    }
                } else {
                    echo '<option value="">Nenhum cliente disponível</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="estabelecimento_contrato">Nome do Estabelecimento:</label>
            <input type="text" id="estabelecimento_contrato" name="estabelecimento_contrato" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="morada_contrato">Morada do Estabelecimento:</label>
            <input type="text" id="morada_contrato" name="morada_contrato" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Pragas:</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="praga_ratos" name="pragas_contrato[]" value="Ratos">
                <label class="form-check-label" for="praga_ratos">Ratos</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="praga_baratas" name="pragas_contrato[]" value="Baratas">
                <label class="form-check-label" for="praga_baratas">Baratas</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="praga_formigas" name="pragas_contrato[]" value="Formigas">
                <label class="form-check-label" for="praga_formigas">Formigas</label>
            </div>
        </div>

        <div class="form-group mb-3">
            <label>Meses de Serviço:</label>
            <?php
            $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
            foreach ($meses as $mes):
            ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="mes_<?php echo strtolower($mes); ?>" name="meses_contrato[]" value="<?php echo $mes; ?>">
                    <label class="form-check-label" for="mes_<?php echo strtolower($mes); ?>"><?php echo $mes; ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="form-group mb-3">
            <label for="data_inicio_contrato">Data de Início:</label>
            <input type="date" id="data_inicio_contrato" name="data_inicio_contrato" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="observacoes_contrato">Observações:</label>
            <textarea id="observacoes_contrato" name="observacoes_contrato" class="form-control"></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="tipo_contrato">Tipo de Contrato:</label>
            <select id="tipo_contrato" name="tipo_contrato" class="form-control" required>
                <option value="Unico">Único</option>
                <option value="Renovavel">Renovável</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="valor_contrato">Valor do Contrato (opcional):</label>
            <input type="number" id="valor_contrato" name="valor_contrato" class="form-control" step="0.01">
        </div>

        <button type="submit" class="btn btn-primary">Criar Contrato</button>
        <a href="tabelacontratos.php" class="btn btn-secondary">Cancelar</a>
    </form>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
