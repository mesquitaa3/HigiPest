<?php
session_start();
if ($_SESSION['cargo'] != 'cliente') {
    header("Location: /login.php");
    exit();
}

//conexao bd
require_once __DIR__ . "/../../bd/config.php";

$email = $_SESSION['email']; //email do cliente

//informacoes do cliente pelo email
$query_cliente = "SELECT id_cliente FROM clientes WHERE email_cliente = ?";
$stmt_cliente = $conn->prepare($query_cliente);
$stmt_cliente->bind_param("s", $email);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();

if ($result_cliente->num_rows === 0) {
    $error_message = "Cliente não encontrado.";
    $conn->close();
    exit();
}

$cliente = $result_cliente->fetch_assoc();
$id_cliente = $cliente['id_cliente'];

//select para msotrar contratos associados ao cliente 
$query_contratos = "SELECT id_contrato, estabelecimento_contrato, morada_contrato, pragas_contrato, 
                           data_inicio_contrato, tipo_contrato, valor_contrato 
                    FROM contratos 
                    WHERE id_cliente = ?";
$stmt_contratos = $conn->prepare($query_contratos);
$stmt_contratos->bind_param("i", $id_cliente);
$stmt_contratos->execute();
$result_contratos = $stmt_contratos->get_result();

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Contratos</title>
    <link rel="stylesheet" href="../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styles/styles.css">
    
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
        
        <?php if ($result_contratos->num_rows > 0): ?>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Estabelecimento</th>
                        <th>Morada</th>
                        <th>Pragas</th>
                        <th>Data Início</th>
                        <th>Tipo</th>
                        <th>Valor (€)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_contratos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['estabelecimento_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['morada_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['pragas_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_inicio_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($row['tipo_contrato']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['valor_contrato'], 2)); ?></td>
                            <td>
                                <a href="/web/areas/clientes/ver_contrato.php?id=<?php echo $row['id_contrato']; ?>" class="btn btn-primary btn-sm">Ver</a>
                            </td>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>