<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php"); //se não for administrador, volta para o login
    exit();
}

//conexao bd
include($_SERVER['DOCUMENT_ROOT'] . "/web/bd/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //guardar dados
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telemovel = $_POST['telemovel'];
    $morada = $_POST['morada'];
    $codigo_postal = $_POST['codigo_postal'];
    $zona = $_POST['zona'];
    $nif = $_POST['nif'];

    //palavra-passe é igual ao n telemovel (pode ser alterada depois)
    $palavra_passe = $telemovel;
    $hashed_password = password_hash($palavra_passe, PASSWORD_BCRYPT); //encriptar pass

    //verificar se todos os campos estão preenchidos
    if (empty($nome) || empty($email) || empty($telemovel) || empty($morada) || empty($codigo_postal) || empty($zona) || empty($nif)) {
        $error_message = "Por favor, preencha todos os campos!";
    } else {
        //verificar se o email ja existe
        $check_email_query = "SELECT * FROM utilizadores WHERE email = ?";
        $stmt_check_email = $conn->prepare($check_email_query);
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();

        if ($result_check_email->num_rows > 0) {
            $error_message = "Este email já está a ser utilizado. Por favor, use outro email.";
        } else {
            //guardar dados na tabela cliente, cliente está ativo (visivel = 1)
            $query_cliente = "INSERT INTO clientes (nome_cliente, email_cliente, telemovel_cliente, morada_cliente, codigopostal_cliente, zona_cliente, nif_cliente, visivel)
                              VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

            
            $stmt_cliente = $conn->prepare($query_cliente);
            $stmt_cliente->bind_param("sssssss", $nome, $email, $telemovel, $morada, $codigo_postal, $zona, $nif);

            if ($stmt_cliente->execute()) {
                
                $id_cliente = $stmt_cliente->insert_id;

                //inserir dados na tabela utilizadores
                $query_utilizador = "INSERT INTO utilizadores (nome, email, palavra_passe, cargo)
                                     VALUES (?, ?, ?, 'cliente')";

                $stmt_utilizador = $conn->prepare($query_utilizador);
                $stmt_utilizador->bind_param("sss", $nome, $email, $hashed_password);

                if ($stmt_utilizador->execute()) {
                    //redirecionar para a página tabelaclientes.php com mensagem de sucesso
                    header("Location: tabelaclientes.php?msg=cliente_adicionado");
                    exit();
                } else {
                    //caso haja erro ao inserir na tabela utilizadores
                    $error_message = "Erro ao adicionar cliente";
                }
            } else {
                //caso haja erro ao inserir na tabela clientes
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
