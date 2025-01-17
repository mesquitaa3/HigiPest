<?php
// Iniciar a sessão
session_start();

//ver se form é enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //dados do form
    $tipo = $_POST['tipo']; //residência ou Empresa
    $nome = $_POST['nome']; //nome do proprietário ou da empresa
    $telemovel = $_POST['telemovel']; //telemóvel
    $email = $_POST['email']; //email
    $descricao = $_POST['descricao']; //descricao

    //email onde vou receber os pedidos
    $to = 'dmmesquita0331@gmail.com';
    $subject = 'Pedido de Orçamento';
    
    //email
    $message = "Tipo: " . ($tipo == 'residencia' ? 'Residência' : 'Empresa') . "\n";
    $message .= "Nome: " . $nome . "\n";
    $message .= "Telemóvel: " . $telemovel . "\n";
    $message .= "E-mail: " . $email . "\n";
    $message .= "Descrição do problema: \n" . $descricao . "\n";

    //cabeçalhos do e-mail
    $headers = "From: no-reply@dominio.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    //ao enviar o email
    if (mail($to, $subject, $message, $headers)) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'O seu pedido foi enviado com sucesso!'];
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Erro ao enviar o pedido. Tente novamente.'];
    }

    //para evitar varios emails, redireciona
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HigiPest - Pedir Orçamento</title>
    
    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">

</head>
<body class="d-flex flex-column" style="min-height: 100vh;">

    <?php require('components/menu.php'); ?> <!-- Inclui menu - menu.php -->

<div class="container mt-5">
    <h2 class="text-center mb-4">Pedido de Orçamento</h2>

    <!-- Mostrar o alerta, se houver -->
    <?php
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        echo '<div class="alert alert-' . $alert['type'] . '" role="alert">' . $alert['message'] . '</div>';
        // Apagar a mensagem da sessão após exibi-la
        unset($_SESSION['alert']);
    }
    ?>

    <form action="orcamento.php" method="POST">
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de pedido:</label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="residencia">Residência</option>
                <option value="empresa">Empresa</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="nome" class="form-label">Nome do proprietário ou da empresa:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telemovel" class="form-label">Contacto (telemóvel):</label>
            <input type="text" name="telemovel" id="telemovel" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail de contacto:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição do problema:</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="5" required></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success fw-bold">Enviar Pedido de Orçamento</button>
        </div>
        
    </form>
        
</div>


    <!-- Footer -->
    <?php require('components/footer.php'); ?> <!-- Inclui o footer - footer.php -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script> <!-- menu dropdown funciona atraves deste script -->

</body>
</html>
