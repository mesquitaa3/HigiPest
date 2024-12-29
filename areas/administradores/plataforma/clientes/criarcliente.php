<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php"); // Se não for administrador, volta para o login
    exit();
}

// Conexão com a base de dados
include($_SERVER['DOCUMENT_ROOT'] . "/web/bd/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Guardar dados do form de criar cliente
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telemovel = $_POST['telemovel'];
    $morada = $_POST['morada'];
    $codigo_postal = $_POST['codigo_postal'];
    $zona = $_POST['zona'];
    $nif = $_POST['nif'];

    // A palavra-passe será igual ao telemóvel
    $palavra_passe = $telemovel;
    $hashed_password = password_hash($palavra_passe, PASSWORD_BCRYPT); // Encriptação segura

    // Verificar se todos os campos estão preenchidos
    if (empty($nome) || empty($email) || empty($telemovel) || empty($morada) || empty($codigo_postal) || empty($zona) || empty($nif)) {
        $error_message = "Por favor, preencha todos os campos!";
    } else {
        // Verificar se o email já existe
        $check_email_query = "SELECT * FROM utilizadores WHERE email = ?";
        $stmt_check_email = $conn->prepare($check_email_query);
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();

        if ($result_check_email->num_rows > 0) {
            $error_message = "Este email já está a ser utilizado. Por favor, use outro email.";
        } else {
            // Guardar os dados na tabela cliente, visivel = 1 para o cliente estar ativo
            $query_cliente = "INSERT INTO clientes (nome_cliente, email_cliente, telemovel_cliente, morada_cliente, codigopostal_cliente, zona_cliente, nif_cliente, visivel)
                              VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

            // Preparar e executar a consulta para inserir dados na tabela cliente
            $stmt_cliente = $conn->prepare($query_cliente);
            $stmt_cliente->bind_param("sssssss", $nome, $email, $telemovel, $morada, $codigo_postal, $zona, $nif);

            if ($stmt_cliente->execute()) {
                // Obter o id_cliente gerado automaticamente (auto_increment)
                $id_cliente = $stmt_cliente->insert_id;

                // Inserir dados na tabela utilizadores
                $query_utilizador = "INSERT INTO utilizadores (nome, email, palavra_passe, cargo)
                                     VALUES (?, ?, ?, 'cliente')";

                // Preparar e executar a consulta para inserir dados na tabela utilizadores
                $stmt_utilizador = $conn->prepare($query_utilizador);
                $stmt_utilizador->bind_param("sss", $nome, $email, $hashed_password);

                if ($stmt_utilizador->execute()) {
                    // Redirecionar para a página tabelaclientes.php com mensagem de sucesso
                    header("Location: tabelaclientes.php?msg=cliente_adicionado");
                    exit();
                } else {
                    // Caso haja erro ao inserir na tabela utilizadores
                    $error_message = "Erro ao adicionar cliente";
                }
            } else {
                // Caso haja erro ao inserir na tabela clientes
                $error_message = "Erro ao adicionar cliente";
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
    <title>Criar Cliente</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Criar Novo Cliente</h2>

        <!-- Mensagem de erro ou sucesso -->
        <?php
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>

        <form action="criarcliente.php" method="POST">
            <div class="form-group mb-3">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="telemovel">Telemóvel:</label>
                <input type="text" id="telemovel" name="telemovel" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="nif">NIF:</label>
                <input type="text" id="nif" name="nif" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="morada">Morada:</label>
                <input type="text" id="morada" name="morada" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="zona">Zona:</label>
                <input type="text" id="zona" name="zona" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">Adicionar Cliente</button>
                <a href="tabelaclientes.php" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
