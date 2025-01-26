<?php
session_start();
if ($_SESSION['cargo'] != 'cliente') {
    header("Location: /login.php");
    exit();
}

// Conexão com o banco de dados
require_once __DIR__ . "/../../bd/config.php";

$id_contrato = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_contrato === 0) {
    echo "ID de contrato inválido.";
    exit();
}

// Detalhes do contrato
$query = "SELECT * FROM contratos WHERE id_contrato = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_contrato);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Contrato não encontrado.";
    exit();
}

$contrato = $result->fetch_assoc();

// Consulta para obter relatórios associados ao contrato
$query_relatorios = "SELECT * FROM relatorios WHERE id_estabelecimento = ?"; // Altere 'contrato_id' para o nome correto, se necessário
$stmt_relatorios = $conn->prepare($query_relatorios);
$stmt_relatorios->bind_param("i", $id_contrato);
$stmt_relatorios->execute();
$result_relatorios = $stmt_relatorios->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Contrato</title>
    <link rel="stylesheet" href="../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styles/styles.css">
    <link rel="stylesheet" href="/web/areas/clientes/assets/css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Detalhes do Contrato</h1>
        
        <div class="card">
            <div class="card-body">
                <div class="menu-container">
                    <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-target="informacao">Informação</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-target="relatorios">Relatórios</a>
                        </li>
                    </ul>
                </div>
                
                <div id="informacao" class="conteudo-secao">
                    <h2>Informação do Contrato</h2>
                    <table class="table table-striped">
                        <tr>
                            <th>Estabelecimento:</th>
                            <td><?php echo htmlspecialchars($contrato['estabelecimento_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Morada:</th>
                            <td><?php echo htmlspecialchars($contrato['morada_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Pragas:</th>
                            <td><?php echo htmlspecialchars($contrato['pragas_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Data de Início:</th>
                            <td><?php echo htmlspecialchars($contrato['data_inicio_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Tipo de Contrato:</th>
                            <td><?php echo htmlspecialchars($contrato['tipo_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Valor do Contrato:</th>
                            <td><?php echo htmlspecialchars(number_format($contrato['valor_contrato'], 2)); ?> €</td>
                        </tr>
                    </table>
                </div>

                <div id="relatorios" class="conteudo-secao" style="display: none;">
                    <h2>Relatórios</h2>
                    <?php if ($result_relatorios->num_rows > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID do Relatório</th>
                                    <th>Descrição</th>
                                    <th>Data</th>
                                    <th>Ações</th> <!-- Nova coluna para ações -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($relatorio = $result_relatorios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($relatorio['id_relatorio']); ?></td>
                                        <td><?php echo htmlspecialchars($relatorio['descricao']); ?></td>
                                        <td><?php echo htmlspecialchars($relatorio['criado_em']); ?></td>
                                        <td>
                                            <a href="/web/areas/clientes/ver_relatorio.php?id=<?php echo $relatorio['id_relatorio']; ?>" class="btn btn-primary btn-sm">Ver Relatório</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            Não há relatórios associados a este contrato.
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <div class="text-center mt-4">
        <a href="/web/areas/clientes/contratos.php" class="btn btn-secondary">Voltar</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="/web/areas/clientes/assets/js/scripts.js"></script>
    
</body>
</html>