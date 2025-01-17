<?php
// Iniciar a sessão
session_start();

//conexao bd
require_once __DIR__ . "/bd/config.php";


// Consultar serviços visíveis na tabela `contactos`
$sql = "SELECT * FROM contactos WHERE visivel = 1 ORDER BY ordem ASC";
$result = $conn->query($sql);


// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome']; // nomea
    $email = $_POST['email']; //email
    $telemovel = $_POST['telemovel']; //telemovel
    $descricao = $_POST['descricao']; //descricao

    //email onde vou receber o pedido de contacto, e titulo do email
    $to = 'dmmesquita0331@gmail.com';
    $subject = 'Pedido de Contacto';
    
    //mensagem do email
    $message .= "Nome: " . $nome . "\n";
    $message .= "E-mail: " . $email . "\n";
    $message .= "Telemóvel: " . $telemovel . "\n";
    $message .= "Descrição do problema: \n" . $descricao . "\n";

    //cabeçalho do email
    $headers = "From: no-reply@dominio.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    //mensagem ao enviar o email
    if (mail($to, $subject, $message, $headers)) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'O seu pedido foi enviado com sucesso!'];
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Erro ao enviar o pedido. Tente novamente.'];
    }

    //para evitar o envio de varios emails
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HigiPest - Contactos</title>
    
    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">

</head>
<body>
    <!-- menu -->
    <?php require('components/menu.php'); ?> <!-- Inclui o menu - menu.php -->

    <!-- Cabeçalho -->
    <section class="hero text-white text-center py-5" style="background-color: #ff8800;">
        <div class="container">
            <h1 class="display-4 fw-bold">Entre em Contacto Conosco</h1>
            <p class="lead">Estamos aqui para ajudar. Envie a sua mensagem ou fale connosco diretamente.</p>
        </div>
    </section>

    <!-- Contactos -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Informações de Contacto</h2>
            
            <!-- morada e mapa com localização lado a lado -->
            <div class="row mb-5">
                <!-- morada -->
                <div class="col-md-6">
                    <h4>Morada</h4>
                    <p>rua <br>coimbra , Portugal</p>

                    <h4>Telefone</h4>
                    <p><a href="tel:+351123456789" class="text-decoration-none text-dark">+351 123 456 789</a></p>

                    <h4>Email</h4>
                    <p><a href="mailto:dmmesquita0331@gmail.com" class="text-decoration-none text-dark">dmmesquita0331@gmail.com</a></p> <!-- criar email para projeto e alterar -->
                </div>

                <!-- mapa -->
                <div class="col-md-6">
                    <iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
                        src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=pt&amp;q=Quinta%20Agr%C3%ADcola,%203045-601%20Coimbra+(ISCAC)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
                    </iframe>
                </div>
            </div>

            <!-- form para contacto -->
            <div class="row">
                <div class="col-md-12">
                    <h4>Envie-nos uma Mensagem</h4>

                        <!-- Mostrar o alerta, se houver -->
                        <?php
                        if (isset($_SESSION['alert'])) {
                            $alert = $_SESSION['alert'];
                            echo '<div class="alert alert-' . $alert['type'] . '" role="alert">' . $alert['message'] . '</div>';
                            // Apagar a mensagem da sessão após exibi-la
                            unset($_SESSION['alert']);
                        }
                        ?>

                    <form action="contactos.php" method="post">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telemovel" class="form-label">Telemovel</label>
                            <input type="text" class="form-control" id="telemovel" name="telemovel" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição do problema:</label>
                            <textarea name="descricao" id="descricao" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success fw-bold">Enviar Mensagem</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require('components/footer.php'); ?> <!-- Inclui o footer - footer.php -->

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
