<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, volta para o login
    exit();
}

//conexao bd
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

//verificar id do cliente
if (!isset($_GET['id_cliente']) || !is_numeric($_GET['id_cliente'])) {
    header("Location: tabelaclientes.php"); //se o id nao for valido, volta para a pagina tabelaclientes.php
    exit();
}

$id_cliente = $_GET['id_cliente'];

//seleciona os dados do cliente
$query = "SELECT * FROM clientes WHERE id_cliente = $id_cliente";
$result = $conn->query($query);

//verifica se o cliente foi encontrado
if ($result->num_rows == 0) {
    header("Location: tabelaclientes.php");  //volta para a pagina tabelaclientes.php se o cliente nao for encontrado
    exit();
}

$cliente = $result->fetch_assoc();

//atualiza dados do cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //guarda dados do form de editar
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telemovel = $_POST['telemovel'];
    $morada = $_POST['morada'];
    $codigo_postal = $_POST['codigo_postal'];
    $zona = $_POST['zona'];

    //verifica se todos os dados estão preenchidos
    if (empty($nome) || empty($email) || empty($telemovel) || empty($morada) || empty($codigo_postal) || empty($zona)) {
        $error_message = "Por favor, preencha todos os campos!";
    } else {
        //atualiza os dados do cliente na tabela clientes
        $update_query = "UPDATE clientes 
                         SET nome_cliente = '$nome', email_cliente = '$email', telemovel_cliente = '$telemovel', 
                             morada_cliente = '$morada', codigopostal_cliente = '$codigo_postal', zona_cliente = '$zona'
                         WHERE id_cliente = $id_cliente";
        
        if ($conn->query($update_query) === TRUE) {
            //volta para a pagina tabelaclientes.php com a mmensagem de sucesso
            header("Location: tabelaclientes.php?msg=cliente_atualizado");
            exit();
        } else {
            $error_message = "Erro ao atualizar cliente: " . $conn->error;
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
    <title>Editar Cliente</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu - menu.php -->

<div class="container mt-5">
    <h2 class="text-center mb-5">Editar Cliente</h2>

    <!-- Mensagem de erro ou sucesso -->
    <?php
    if (isset($error_message)) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
    }
    if (isset($success_message)) {
        echo '<div class="alert alert-success">' . htmlspecialchars($success_message) . '</div>';
    }
    ?>

    <!-- form para editar cliente -->
    <form action="editarcliente.php?id_cliente=<?php echo $id_cliente; ?>" method="POST">
        <div class="form-group mb-3">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($cliente['nome_cliente']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($cliente['email_cliente']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="telemovel">Telemovel:</label>
            <input type="text" id="telemovel" name="telemovel" class="form-control" value="<?php echo htmlspecialchars($cliente['telemovel_cliente']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="morada">Morada:</label>
            <input type="text" id="morada" name="morada" class="form-control" value="<?php echo htmlspecialchars($cliente['morada_cliente']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="codigo_postal">Código Postal:</label>
            <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" value="<?php echo htmlspecialchars($cliente['codigopostal_cliente']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="zona">Zona:</label>
            <input type="text" id="zona" name="zona" class="form-control" value="<?php echo htmlspecialchars($cliente['zona_cliente']); ?>" required>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-primary">Editar</button>
            <a href="tabelaclientes.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
