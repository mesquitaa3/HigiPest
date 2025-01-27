<?php
session_start();
if ($_SESSION['cargo'] != 'cliente') {
    header("Location: ../../login.php");
    exit();
}

// Conexão com o banco de dados
require_once __DIR__ . "/../../bd/config.php";

$id_relatorio = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_relatorio === 0) {
    echo "ID de relatório inválido.";
    exit();
}

// Detalhes do relatório
$query = "SELECT * FROM relatorios WHERE id_relatorio = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_relatorio);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Relatório não encontrado.";
    exit();
}

$relatorio = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Relatório</title>
    <link rel="stylesheet" href="../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styles/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Detalhes do Relatório</h1>
        
        <div class="card">
            <div class="card-body">
                <h2><?php echo htmlspecialchars($relatorio['descricao']); ?></h2>
                <p><strong>Data: </strong> <?php echo htmlspecialchars($relatorio['criado_em']); ?></p>
                <p><strong>Descrição: </strong><?php echo nl2br(htmlspecialchars($relatorio['descricao'])); ?></p><!-- Supondo que você tenha uma coluna 'conteudo' -->
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="ver_contrato.php?id=<?php echo $relatorio['id_contrato']; ?>" class="btn btn-secondary">Voltar</a>
        </div>

    </div>
</body>
</html>