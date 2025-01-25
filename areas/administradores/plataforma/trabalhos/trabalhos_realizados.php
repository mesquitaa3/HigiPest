<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

require_once __DIR__ . "/../../../../bd/config.php";

$query = "
    SELECT r.id_relatorio, c.nome_cliente, e.estabelecimento_contrato, t.nome_tecnico, r.descricao, r.criado_em
    FROM relatorios r
    JOIN clientes c ON r.id_cliente = c.id_cliente
    JOIN contratos e ON r.id_estabelecimento = e.id_contrato
    LEFT JOIN tecnicos t ON r.id_tecnico = t.id_tecnico
    WHERE r.id_visita IS NOT NULL
    ORDER BY r.criado_em DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabalhos Realizados</title>
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
</head>
<body>
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Trabalhos Realizados</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Estabelecimento</th>
                    <th>Técnico</th>
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
                        <td><?= htmlspecialchars($row['nome_tecnico']) ?></td>
                        <td><?= htmlspecialchars($row['descricao']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['criado_em'])) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>    
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
