<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

require_once __DIR__ . "/../../../../bd/config.php";

// Atualizando a consulta SQL para refletir a coluna correta na tabela 'agendamentos'
$query = "
    SELECT 
        a.id_agendamento, 
        c.nome_cliente, 
        e.estabelecimento_contrato, 
        t.nome_tecnico, 
        a.data_agendada -- Removendo a coluna 'a.descricao'
    FROM agendamentos a
    JOIN clientes c ON a.id_contrato = c.id_cliente
    JOIN contratos e ON a.id_contrato = e.id_contrato  -- Corrigido para 'id_contrato' se for o caso
    LEFT JOIN tecnicos t ON a.tecnico = t.id_tecnico
    WHERE a.status != 'finalizado' -- Exclui agendamentos concluídos
    ORDER BY a.data_agendada ASC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabalhos Agendados</title>
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
</head>
<body>

<?php require("../../menu.php"); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-4">Trabalhos Agendados</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Agendamento</th>
                        <th>Cliente</th>
                        <th>Estabelecimento</th>
                        <th>Técnico</th>
                        <th>Data Agendada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id_agendamento'] ?></td>
                            <td><?= htmlspecialchars($row['nome_cliente']) ?></td>
                            <td><?= htmlspecialchars($row['estabelecimento_contrato']) ?></td>
                            <td><?= htmlspecialchars($row['nome_tecnico'] ?: 'N/A') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['data_agendamento'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Nenhum trabalho agendado foi encontrado.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
