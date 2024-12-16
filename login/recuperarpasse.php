<?php
session_start();

// conexão com bd
$servername = "localhost";
$username = "web";
$password = "web";
$dbname = "web-project";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    //verificar se o email está na bd
    $sql = "SELECT * FROM utilizadores WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        //gerar token único e definir tempo para expirar
        $token = bin2hex(random_bytes(32)); //token
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); //valido por 1 hora

        //atualizar bd com o token e o tempo para expirar o token
        $sql_update = "UPDATE utilizadores SET reset_token = '$token', token_expiry = '$expiry' WHERE email = '$email'";
        if (mysqli_query($conn, $sql_update)) {
            //usar phpmailer para enviar o email
            require '../vendor/autoload.php';  //caminho para o autoloader do Composer

            //criar uma instância do PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  //servidor SMTP do Google
            $mail->SMTPAuth = true;
            $mail->Username = 'dmmesquita0331@gmail.com'; //email de onde é enviado
            $mail->Password = 'uqdq uxfy omfd ebre';  //palavrapasse 
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; //

            $mail->setFrom('dmmesquita0331@gmail.com', 'HigiPest');
            $mail->addAddress($email);  //enviar para o destino
            $mail->Subject = 'Recuperar Palavra-Passe - HigiPest';
            $mail->Body    = "Para poder alterar a sua palavra-passe, clique no seguinte link: http://localhost/web/login/alterarpasse.php?token=$token";

            if ($mail->send()) {
                echo "Email para repor palavra-passe enviado. Verifique o seu email.";
            } else {
                echo "Falha ao enviar e-mail: " . $mail->ErrorInfo;
            }
        } else {
            echo "Erro ao atualizar o token.";
        }
    } else {
        echo "O email não se encontra registado na nossa base de dados.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Palavra-Passe</title>
</head>
<body>
    <h2>Recuperar Palavra-Passe</h2>
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
