<?php
session_start();

// conexao com bd
$servername = "localhost";
$username = "web";
$password = "web";
$dbname = "web-project";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    //verificar se o token é valido 
    $sql = "SELECT * FROM utilizadores WHERE reset_token = '$token' AND token_expiry > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nova_password = password_hash($_POST['nova_password'], PASSWORD_BCRYPT);

            //atualizar a palavra-passe na bd
            $sql_update = "UPDATE utilizadores SET palavra_passe = '$nova_password', reset_token = NULL, token_expiry = NULL WHERE reset_token = '$token'";
            if (mysqli_query($conn, $sql_update)) {
                echo "Palavra-passe alterada com sucesso. Já pode fazer login com a nova palavra-passe.";
                header("Location: /web/login.php");
                exit();
            } else {
                echo "Erro ao atualizar a palavra-passe.";
            }
        }
    } else {
        echo "O Token é inválido ou está expirado.";
        exit();
    }
} else {
    echo "Token não fornecido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repor Palavra-Passe</title>
</head>
<body>
    <h2>Repor Palavra-Passe</h2>
    <form method="POST">
        <label for="nova_password">Nova Palavra-Passe:</label>
        <input type="password" id="nova_password" name="nova_password" required>
        <button type="submit">Redefinir</button>
    </form>
</body>
</html>
