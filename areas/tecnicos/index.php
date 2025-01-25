<?php
session_start();
if ($_SESSION['cargo'] != 'tecnico') {
    header("Location: login.php");  // Se não for técnico, redireciona para o login
    exit();
}

require_once __DIR__ . "/../../bd/config.php";

// Obter o ID do técnico da sessão
$tecnico_id = $_SESSION['id'];

// Consulta para buscar o nome do técnico
$query_tecnico = "
    SELECT nome_tecnico 
    FROM tecnicos 
    WHERE id_tecnico = ?
";

$stmt = $conn->prepare($query_tecnico);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o técnico foi encontrado
if ($result->num_rows > 0) {
    $tecnico = $result->fetch_assoc();
    $nome_tecnico = $tecnico['nome_tecnico'];
} else {
    die("Técnico não encontrado.");
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnico</title>
    
    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/tecnicos/menu.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Bem-vindo, <?= htmlspecialchars($nome_tecnico) ?></h2>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Agenda</h5>
                        <p class="card-text">Visualize e gerencie sua agenda de serviços e visitas.</p>
                        <a href="/web/areas/tecnicos/agenda.php" class="btn btn-primary">Ir para Agenda</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Trabalhos Realizados</h5>
                        <p class="card-text">Veja os relatórios dos trabalhos realizados.</p>
                        <a href="/web/areas/tecnicos/trabalhos_realizados.php" class="btn btn-primary">Ver Trabalhos Realizados</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>