<?php
// Conexão com a bd
$servername = "localhost";
$username = "web";
$password = "web";
$dbname = "web-project";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Definir charset para UTF-8
mysqli_set_charset($conn, "utf8");

// Verificação do Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //dados do form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // verificar se o email existe e se a palavra-passe é válida
    $stmt = $conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $utilizador = $result->fetch_assoc();
    

    // verificar se o utilizador existe e se a palavra-passe está correta
    if ($utilizador && password_verify($password, $utilizador['palavra_passe'])) {
        // Iniciar a sessão
        session_start();
        $_SESSION['id'] = $utilizador['id'];  // Adicione esta linha para armazenar o ID
        $_SESSION['utilizador'] = $utilizador['email'];
        $_SESSION['cargo'] = $utilizador['cargo'];

        // página correspondente ao cargo do utilizador
        if ($utilizador['cargo'] == 'cliente') {
            header("Location: /web/areas/clientes/index.php");
        } elseif ($utilizador['cargo'] == 'administrador') {
            header("Location: /web/areas/administradores/index.php");
        } elseif ($utilizador['cargo'] == 'tecnico') {
            header("Location: /web/areas/tecnicos/index.php");
        }
        exit();
    } else {
        // Redirecionar de volta para a página de login com mensagem de erro
        header("Location: /web/login.php?erro=1");
        exit();
    }
}

mysqli_close($conn);
?>
