<?php
session_start();
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

if (!isset($_SESSION['id']) || $_SESSION['cargo'] !== 'cliente') {
    header("Location: /web/login.php");
    exit();
}

//guardar os dados do utilizador
$id_utilizador = $_SESSION['id'];
$nome = $_SESSION['nome'];  //nome do utilizador
$email = $_SESSION['email'];  //email do utilizador

//envio do form de contacto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = trim($_POST['mensagem']);

    if (!empty($mensagem)) {
        $query = "
            INSERT INTO alertas (id_utilizador, nome, email, mensagem)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $id_utilizador, $nome, $email, $mensagem);

        if ($stmt->execute()) {
            $sucesso = "Mensagem enviada com sucesso!";
        } else {
            $erro = "Ocorreu um erro ao enviar a mensagem. Tente novamente.";
        }
        $stmt->close();
    } else {
        $erro = "A mensagem não pode estar vazia.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">

</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/web/areas/clientes/index.php">Área de Cliente</a>
            <form class="d-flex" action="/web/logout.php" method="POST">
                <button class="btn btn-outline-light" type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="text-center mb-4">Contacte-nos</h2>
        <div class="row">
            <!-- Informações  --> <!-- atualizar de acordo com tabela "contactos" -->
            <div class="col-md-6">
                <h4>Informações da Empresa</h4>
                <p><strong>Morada:</strong> Rua Exemplo, 123, Lisboa</p> 
                <p><strong>Email:</strong> empresa@exemplo.com</p>
                <p><strong>Telefone:</strong> +351 123 456 789</p>
                <p><strong>Horário:</strong> Segunda a Sexta, das 9h às 18h</p>
            </div>

            <!-- fomrm de contacto -->
            <div class="col-md-6">
                <h4>Envie-nos uma mensagem</h4>
                <?php if (isset($sucesso)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
                <?php elseif (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="mensagem" class="form-label">Mensagem</label>
                        <textarea name="mensagem" id="mensagem" rows="5" class="form-control" placeholder="Escreva a mensagem aqui..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="/web/assets/scripts/bootstrap.bundle.min.js"></script>
</body>
</html>
