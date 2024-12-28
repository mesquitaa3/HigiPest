<?php
// Iniciar sessão
session_start();

// Verificar se o utilizador está autenticado
if (!isset($_SESSION['id']) || $_SESSION['cargo'] != 'cliente') {
    header("Location: /web/login.php");
    exit();
}

// Conectar ao banco de dados
include($_SERVER['DOCUMENT_ROOT'] . "/web/bd/config.php");

// Obter email do utilizador da sessão
$id_utilizador = $_SESSION['id'];
$query_email = "SELECT email FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($query_email);
$stmt->bind_param("i", $id_utilizador);
$stmt->execute();
$result_email = $stmt->get_result();

if ($result_email->num_rows === 0) {
    die("Erro: Utilizador não encontrado.");
}

$row_email = $result_email->fetch_assoc();
$email_utilizador = $row_email['email'];

// Buscar o id_cliente correspondente ao email do utilizador
$query_cliente = "SELECT id_cliente FROM clientes WHERE email_cliente = ?";
$stmt = $conn->prepare($query_cliente);
$stmt->bind_param("s", $email_utilizador);
$stmt->execute();
$result_cliente = $stmt->get_result();

if ($result_cliente->num_rows === 0) {
    die("Erro: Cliente não encontrado para este utilizador.");
}

$row_cliente = $result_cliente->fetch_assoc();
$id_cliente = $row_cliente['id_cliente'];

// Buscar contratos associados ao cliente
$query_contratos = "SELECT id_contrato, estabelecimento_contrato, morada_contrato, pragas_contrato, data_inicio_contrato, tipo_contrato, valor_contrato 
                    FROM contratos 
                    WHERE id_cliente = ? AND visivel = 1";

$stmt = $conn->prepare($query_contratos);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Contratos</title>
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/web/areas/clientes/index.php">Área do Cliente</a>
            <form class="d-flex" action="/web/logout.php" method="POST">
                <button class="btn btn-outline-light" type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Meus Contratos</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Estabelecimento</th>
                        <th>Morada</th>
                        <th>Pragas</th>
                        <th>Data Início</th>
                        <th>Tipo</th>
                        <th>Valor (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['estabelecimento_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['morada_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['pragas_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_inicio_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['tipo_contrato']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['valor_contrato'], 2)); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center" role="alert">
                Não há contratos associados à sua conta.
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="/web/areas/clientes/index.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
