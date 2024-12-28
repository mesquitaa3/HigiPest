<?php
// Iniciar sessão
session_start();

//
if (!isset($_SESSION['id']) || $_SESSION['cargo'] != 'cliente') {
    header("Location: /web/login.php");
    exit();
}

//ligacao bd
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

//obter id do cliente
$id_cliente = $_SESSION['id'];

//nome do cliente atraves da bd
$query_nome_cliente = "SELECT nome FROM utilizadores WHERE id = ? AND cargo = 'cliente'";
$stmt = $conn->prepare($query_nome_cliente);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nome_cliente = $row['nome'];
} else {
    //caso nao encontre o nome, voltar para a pagina de login
    header("Location: /web/login.php");
    exit();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Cliente</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    <style>
        /* Tornar a página fixa sem overflow vertical */
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        /* Centralizar conteúdo */
        .center-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Área de Cliente</a>
            <form class="d-flex" action="/web/login.php" method="POST">
                <button class="btn btn-outline-light" type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Container principal -->
    <div class="container center-container">
        <div class="text-center mb-5">
            <h1 class="mb-4">Bem-vindo, <?php echo ($nome_cliente); ?>!</h1>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-4 w-100">
            <!-- Contratos -->
            <div class="col">
                <a href="/web/areas/clientes/contratos.php" class="btn btn-primary text-white shadow w-100 h-100 py-4 d-flex justify-content-center align-items-center">
                    <h3 class="mb-0">Contratos</h3>
                </a>
            </div>
            <!-- Documentos -->
            <div class="col">
                <a href="/web/cliente/documentos.php" class="btn btn-primary text-white shadow w-100 h-100 py-4 d-flex justify-content-center align-items-center">
                    <h3 class="mb-0">Documentos</h3>
                </a>
            </div>
            <!-- Conta -->
            <div class="col">
                <a href="/web/areas/clientes/conta.php" class="btn btn-primary text-white shadow w-100 h-100 py-4 d-flex justify-content-center align-items-center">
                    <h3 class="mb-0">Conta</h3>
                </a>
            </div>
            <!-- Contactos -->
            <div class="col">
                <a href="/web/areas/clientes/contactos.php" class="btn btn-primary text-white shadow w-100 h-100 py-4 d-flex justify-content-center align-items-center">
                    <h3 class="mb-0">Contactos</h3>
                </a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
