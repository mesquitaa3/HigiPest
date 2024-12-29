<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");
    exit();
}

include($_SERVER['DOCUMENT_ROOT'] . "/web/bd/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telemovel = $_POST['telemovel'];
    $morada = $_POST['morada'];
    $codigo_postal = $_POST['codigo_postal'];
    $zona = $_POST['zona'];
    $nif = $_POST['nif'];

    $palavra_passe = $telemovel;
    $hashed_password = password_hash($palavra_passe, PASSWORD_BCRYPT);

    if (empty($nome) || empty($email) || empty($telemovel) || empty($morada) || empty($codigo_postal) || empty($zona) || empty($nif)) {
        $error_message = "Por favor, preencha todos os campos!";
    } else {
        // Verificar se o email já existe em ambas as tabelas
        $check_email_query = "SELECT * FROM utilizadores WHERE email = ? UNION SELECT * FROM clientes WHERE email_cliente = ?";
        $stmt_check_email = $conn->prepare($check_email_query);
        $stmt_check_email->bind_param("ss", $email, $email);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();

        if ($result_check_email->num_rows > 0) {
            $error_message = "Este email já está em uso. Por favor, use outro email.";
        } else {
            // Iniciar transação
            $conn->begin_transaction();

            try {
                // Inserir na tabela clientes
                $query_cliente = "INSERT INTO clientes (nome_cliente, email_cliente, telemovel_cliente, morada_cliente, codigopostal_cliente, zona_cliente, nif_cliente, visivel)
                                  VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
                $stmt_cliente = $conn->prepare($query_cliente);
                $stmt_cliente->bind_param("sssssss", $nome, $email, $telemovel, $morada, $codigo_postal, $zona, $nif);
                $stmt_cliente->execute();

                // Inserir na tabela utilizadores
                $query_utilizador = "INSERT INTO utilizadores (nome, email, palavra_passe, cargo)
                                     VALUES (?, ?, ?, 'cliente')";
                $stmt_utilizador = $conn->prepare($query_utilizador);
                $stmt_utilizador->bind_param("sss", $nome, $email, $hashed_password);
                $stmt_utilizador->execute();

                // Commit da transação
                $conn->commit();

                header("Location: tabelaclientes.php?msg=cliente_adicionado");
                exit();
            } catch (Exception $e) {
                // Rollback em caso de erro
                $conn->rollback();
                $error_message = "Erro ao adicionar cliente: " . $e->getMessage();
            }
        }
    }
}

$conn->close();
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
