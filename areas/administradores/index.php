<?php
session_start();

// Verifica se o usuário é administrador
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

// Conexão com o banco de dados
require_once __DIR__ . "/../../bd/config.php";

// Buscar os relatórios recentes
$query = "
    SELECT relatorios.*, clientes.nome_cliente, contratos.estabelecimento_contrato, tecnicos.nome_tecnico
    FROM relatorios
    JOIN clientes ON relatorios.id_cliente = clientes.id_cliente
    JOIN contratos ON relatorios.id_estabelecimento = contratos.id_contrato
    JOIN tecnicos ON relatorios.id_tecnico = tecnicos.id_tecnico
    ORDER BY relatorios.criado_em DESC
    LIMIT 10
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração</title>
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center">Últimos Relatórios</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Estabelecimento</th>
                    <th>Técnico</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nome_cliente']) ?></td>
                        <td><?= htmlspecialchars($row['estabelecimento_contrato']) ?></td>
                        <td><?= htmlspecialchars($row['nome_tecnico']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($row['criado_em']))) ?></td>
                        <td>
                            <!-- Botões para visualizar e editar -->
                            <a href="plataforma/relatorio/ver_relatorio.php?id=<?= $row['id_relatorio'] ?>" class="btn btn-info btn-sm">Ver</a>
                            <a href="editar_relatorio.php?id=<?= $row['id_relatorio'] ?>" class="btn btn-warning btn-sm">Editar</a>

                            <!-- Excluir desativado, botão apenas visível -->
                            <!-- <a href="?delete=<?= $row['id_relatorio'] ?>" class="btn btn-danger btn-sm">Excluir</a> -->
                            <button class="btn btn-danger btn-sm" disabled>Excluir</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhum relatório encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    

</body>
</html>
