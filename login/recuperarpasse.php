<?php
session_start();

//conexao com a bd
require_once __DIR__ . "/../bd/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Verificar se o email está na base de dados
    $sql = "SELECT * FROM utilizadores WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Gerar token único e definir tempo para expirar
        $token = bin2hex(random_bytes(32)); // Token
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Válido por 1 hora

        // Atualizar base de dados com o token e o tempo para expirar o token
        $sql_update = "UPDATE utilizadores SET reset_token = '$token', token_expiry = '$expiry' WHERE email = '$email'";
        if (mysqli_query($conn, $sql_update)) {
            // Usar PHPMailer para enviar o email
            require '../vendor/autoload.php';  // Caminho para o autoloader do Composer

            // Criar uma instância do PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Servidor SMTP do Google
            $mail->SMTPAuth = true;
            $mail->Username = 'dmmesquita0331@gmail.com'; // Email de onde é enviado
            $mail->Password = 'uqdq uxfy omfd ebre';  // Palavra-passe 
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('dmmesquita0331@gmail.com', 'HigiPest');
            $mail->addAddress($email);  // Enviar para o destino
            $mail->Subject = 'Recuperar Palavra-Passe - HigiPest';
            $mail->Body    = "Para poder alterar a sua palavra-passe, clique no seguinte link: http://localhost/web/login/alterarpasse.php?token=$token";

            if ($mail->send()) {
                $_SESSION['flash_message'] = "Email para repor palavra-passe enviado. Verifique o seu email.";
                header("Location: /web/login.php"); // Redireciona para a página de login
                exit();
            } else {
                echo "<div class='alert alert-danger'>Falha ao enviar e-mail: " . $mail->ErrorInfo . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Erro ao atualizar o token.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>O email não se encontra registado na nossa base de dados.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Palavra-Passe</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    
</head>
<body class="d-flex flex-column" style="min-height: 100vh; background-color: #f8f9fa;">

<?php require('../components/menu.php'); ?> <!-- Inclui menu - menu.php -->


    <div class="container d-flex align-items-center justify-content-center" style="flex: 1;">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Recuperar Palavra-Passe</h2>

                        <!-- Formulário de recuperação -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require('../components/footer.php'); ?> <!-- Inclui o footer - footer.php -->

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
