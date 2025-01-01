<?php
session_start();
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Verificação do Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dados do formulário
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Verificar se o email existe
    $stmt = $conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $utilizador = $result->fetch_assoc();

    // Verificar se o utilizador existe e se a palavra-passe está correta
    if ($utilizador && password_verify($password, $utilizador['palavra_passe'])) {
        // Iniciar a sessão
        $_SESSION['id'] = $utilizador['id'];  // Armazenar o ID do utilizador
        $_SESSION['nome'] = $utilizador['nome'];  // Armazenar o nome do utilizador
        $_SESSION['email'] = $utilizador['email'];  // Armazenar o email do utilizador
        $_SESSION['cargo'] = $utilizador['cargo'];  // Armazenar o cargo do utilizador

        // Redirecionar para a página correspondente ao cargo do utilizador
        if ($utilizador['cargo'] == 'cliente') {
            header("Location: /web/areas/clientes/index.php");
        } elseif ($utilizador['cargo'] == 'administrador') {
            header("Location: /web/areas/administradores/index.php");
        } elseif ($utilizador['cargo'] == 'tecnico') {
            header("Location: /web/areas/tecnicos/index.php");
        }
        exit(); // Certifique-se de usar exit() após header()
    } else {
        // Redirecionar de volta para a página de login com mensagem de erro
        header("Location: /web/login.php?erro=1");
        exit();
    }
}

mysqli_close($conn);
?>
