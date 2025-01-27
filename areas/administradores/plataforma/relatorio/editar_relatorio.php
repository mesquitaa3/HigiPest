<?php
// Início da sessão e verificação de permissão
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

// Conexão com o banco de dados
require_once __DIR__ . "/../../../../bd/config.php";

// Obter o ID do relatório da URL
$id_relatorio = $_GET['id'] ?? null;

// Verifica se o ID foi fornecido
if (!$id_relatorio) {
    echo '<div class="alert alert-warning text-center mt-5">Nenhum ID fornecido!</div>';
    exit();
}

// Consulta para buscar dados do relatório
$query_relatorio = "
    SELECT * 
    FROM relatorios
    WHERE id_relatorio = ?
";

$stmt = $conn->prepare($query_relatorio);
$stmt->bind_param("i", $id_relatorio);
$stmt->execute();
$result = $stmt->get_result();

// Se o relatório for encontrado, armazena os dados
if ($result->num_rows > 0) {
    $relatorio = $result->fetch_assoc();
} else {
    echo '<div class="alert alert-danger text-center mt-5">Relatório não encontrado!</div>';
    exit();
}

// Fechar a conexão após a consulta
$stmt->close();

// Se o formulário for enviado, faz a atualização no banco
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os valores do formulário
    $descricao = $_POST['descricao'];
    $criado_em = $_POST['criado_em'];

    // Consulta para atualizar o relatório
    $update_query = "
        UPDATE relatorios
        SET descricao = ?, criado_em = ?
        WHERE id_relatorio = ?
    ";

    // Prepara a declaração
    $stmt_update = $conn->prepare($update_query);
    if ($stmt_update === false) {
        echo "Erro ao preparar a consulta: " . $conn->error;
        exit();
    }

    // Liga os parâmetros e executa a consulta
    $stmt_update->bind_param("ssi", $descricao, $criado_em, $id_relatorio);
    $stmt_update->execute();

    // Verifica se a execução foi bem-sucedida
    if ($stmt_update->affected_rows > 0) {
        // Redireciona para a página de visualização
        header("Location: ../../index.php");
        exit();
    } else {
        echo '<div class="alert alert-danger text-center mt-5">Erro ao atualizar o relatório ou nenhuma alteração foi feita.</div>';
    }

    // Fechar o statement após a execução
    $stmt_update->close();
}

// Fechar a conexão após a atualização
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Relatório</title>
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
</head>
<body class="bg-light">
    <?php require("../../menu.php"); ?> <!-- Inclui o menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Editar Relatório</h2>

        <!-- Formulário para editar o relatório -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Detalhes do Relatório</div>
            <div class="card-body">
                <form action="" method="post">
                    <!-- Descrição do serviço -->
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição do Serviço</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?= htmlspecialchars($relatorio['descricao']) ?></textarea>
                    </div>

                    <!-- Data do relatório -->
                    <div class="mb-3">
                        <label for="criado_em" class="form-label">Data do Relatório</label>
                        <input type="date" class="form-control" id="criado_em" name="criado_em" value="<?= htmlspecialchars($relatorio['criado_em']) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Atualizar Relatório</button>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="../contrato.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
