<?php
session_start();
if ($_SESSION['cargo'] != 'tecnico') {
    header("Location: /login.php");
    exit();
}

require_once __DIR__ . "/../../bd/config.php";


// Consulta para buscar o nome do técnico
$query_tecnico = "
    SELECT nome_tecnico 
    FROM tecnicos 
    WHERE id_tecnico = ?
";

// Obter o ID do técnico da sessão
$tecnico_id = $_SESSION['id'];

// Consulta para buscar os relatórios feitos pelo técnico
$query = "
    SELECT r.id_relatorio, c.nome_cliente, e.estabelecimento_contrato, r.descricao, r.criado_em
    FROM relatorios r
    JOIN clientes c ON r.id_cliente = c.id_cliente
    JOIN contratos e ON r.id_contrato = e.id_contrato
    WHERE r.id_tecnico = ? AND r.id_visita IS NOT NULL
    ORDER BY r.criado_em DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios do Técnico</title>
    <link rel="stylesheet" href="../../assets/styles/bootstrap.min.css">
</head>
<body>
<?php require_once 'menu.php'; ?> <!-- Inclui menu para o técnico -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Relatórios do Técnico</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Estabelecimento</th>
                    <th>Descrição</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id_relatorio'] ?></td>
                        <td><?= htmlspecialchars($row['nome_cliente']) ?></td>
                        <td><?= htmlspecialchars($row['estabelecimento_contrato']) ?></td>
                        <td><?= htmlspecialchars($row['descricao']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['criado_em'])) ?></td>
                        <td>
                            <a href="ver_relatorio.php?id=<?= $row['id_relatorio'] ?>" class="btn btn-info">Ver Relatório</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>    
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>