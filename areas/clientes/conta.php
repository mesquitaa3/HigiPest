<?php
session_start();


if (!isset($_SESSION['id'])) {
    header('Location: /login.php');
    exit();
}

//conexao bd
require_once __DIR__ . "/../../bd/config.php";


$id_cliente = $_SESSION['id'];

// select para dados do cliente na base de dados - tabela "utilizadores"
$sql = "SELECT nome, email FROM utilizadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
} else {
    echo "Erro: Cliente não encontrado.";
    exit();
}

//atualizar dados do cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_nome = trim($_POST['nome']);
    $novo_email = trim($_POST['email']);
    $nova_password = trim($_POST['password']);

    //atualiza o nome e o email
    $update_sql = "UPDATE utilizadores SET nome = ?, email = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $novo_nome, $novo_email, $id_cliente);
    $update_stmt->execute();

    //atualiza a pass se for altrada
    if (!empty($nova_password)) {
        $hashed_password = password_hash($nova_password, PASSWORD_DEFAULT);
        $password_sql = "UPDATE utilizadores SET palavra_passe = ? WHERE id = ?";
        $password_stmt = $conn->prepare($password_sql);
        $password_stmt->bind_param("si", $hashed_password, $id_cliente);
        $password_stmt->execute();
    }

    header("Location: /web/areas/clientes/conta.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definições da Conta</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styles/styles.css">

</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/web/areas/clientes/index.php">Área de Cliente</a>
            <form class="d-flex" action="/web/login.php" method="POST">
                <button class="btn btn-outline-light" type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Definições da Conta</h3>
                    </div>
                    <div class="card-body">
                        <!-- Mensagem caso dados sejam atualizados -->
                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                            <div class="alert alert-success">
                                Dados atualizados com sucesso!
                            </div>
                        <?php endif; ?>

                        <form action="/web/areas/clientes/conta.php" method="POST">
                            <!-- Nome -->
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
                            </div>
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
                            </div>
                            <!-- Palavra-passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Palavra-Passe (deixe em branco para não alterar)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Nova Palavra-passe">
                            </div>
                            <!-- Botões -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Guardar Alterações</button>
                                <a href="/web/areas/clientes/index.php" class="btn btn-primary">Voltar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
