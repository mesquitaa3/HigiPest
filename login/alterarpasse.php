<?php
session_start();

require_once __DIR__ . "/../bd/config.php";

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    // Verificar se o token é válido 
    $sql = "SELECT * FROM utilizadores WHERE reset_token = '$token' AND token_expiry > NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nova_password = password_hash($_POST['nova_password'], PASSWORD_BCRYPT);

            // Atualizar a palavra-passe na base de dados
            $sql_update = "UPDATE utilizadores SET palavra_passe = '$nova_password', reset_token = NULL, token_expiry = NULL WHERE reset_token = '$token'";
            if (mysqli_query($conn, $sql_update)) {
                echo "<div class='alert alert-success'>Palavra-passe alterada com sucesso. Já pode fazer login com a nova palavra-passe.</div>";
                header("Location: ../login.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Erro ao atualizar a palavra-passe.</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>O Token é inválido ou está expirado.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Token não fornecido.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repor Palavra-Passe</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../assets/styles/bootstrap.min.css">
</head>
<body class="d-flex flex-column" style="min-height: 100vh; background-color: #f8f9fa;">

    <div class="container d-flex align-items-center justify-content-center" style="flex: 1;">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Repor Palavra-Passe</h2>

                        <!-- Formulário de redefinição -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nova_password" class="form-label">Nova Palavra-Passe</label>
                                <input type="password" id="nova_password" name="nova_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Redefinir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
