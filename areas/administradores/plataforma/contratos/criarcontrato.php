<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

//select para todos os clientes visiveis
$query = "SELECT id_cliente, nome_cliente FROM clientes WHERE visivel = 1";
$result = $conn->query($query);

//verificar se ha erros na consulta
if (!$result) {
    die("Erro na consulta SQL: " . $conn->error);
}

//processar form para criar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //dados do form
    $id_cliente = $_POST['cliente'];
    $morada_contrato = $_POST['morada_contrato'];
    $estabelecimento_contrato = $_POST['estabelecimento_contrato'];
    $pragas_contrato = isset($_POST['pragas_contrato']) ? implode(", ", $_POST['pragas_contrato']) : "";
    $meses_contrato = isset($_POST['meses_contrato']) ? implode(", ", $_POST['meses_contrato']) : "";
    $data_inicio_contrato = $_POST['data_inicio_contrato'];
    $observacoes_contrato = $_POST['observacoes_contrato'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $valor_contrato = isset($_POST['valor_contrato']) ? $_POST['valor_contrato'] : null;
    $dia_descanso = isset($_POST['dia_descanso']) ? implode(", ", $_POST['dia_descanso']) : "";

    //verificar se todos os campos estao preenchidos
    if (empty($id_cliente) || empty($morada_contrato) || empty($estabelecimento_contrato) || empty($data_inicio_contrato) || empty($tipo_contrato)) {
        $error_message = "Por favor, preencha todos os campos obrigatórios!";
    } else {
        //eviar sql injection
        $insert_query = $conn->prepare("INSERT INTO contratos 
            (id_cliente, estabelecimento_contrato, morada_contrato, pragas_contrato, meses_contrato, data_inicio_contrato, observacoes_contrato, tipo_contrato, dia_descanso, valor_contrato, visivel) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");

        $insert_query->bind_param("issssssssd", $id_cliente, $estabelecimento_contrato, $morada_contrato, $pragas_contrato, $meses_contrato, $data_inicio_contrato, $observacoes_contrato, $tipo_contrato, $dia_descanso, $valor_contrato);

        
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
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu -->

<div class="container mt-5">
    <h2 class="text-center mb-4">Criar Contrato</h2>

    <!--mensagem de errio-->
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
            <label>Dia de descanso:</label>
            <?php
            $dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
            foreach ($dias as $dia):
            ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="dia_<?php echo strtolower($dia); ?>" name="dia_descanso[]" value="<?php echo $dia; ?>">
                    <label class="form-check-label" for="dia_<?php echo strtolower($dia); ?>"><?php echo $dia; ?></label>
                </div>
            <?php endforeach; ?>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
