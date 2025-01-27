<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Dados do formulário
    $servico = $_POST['servico'];
    $descricao = $_POST['descricao'];
    $ordem = $_POST['ordem'];
    $visivel = 1; // Sempre visível

    //conexao bd
    require_once __DIR__ . "/../../../../bd/config.php";

    // Preparar consulta com prepared statement
    $stmt = $conn->prepare("INSERT INTO servicos (servico, descricao, img, ordem, visivel) VALUES (?, ?, ?, ?, ?)");
    
    // Verificar upload de imagem
    $img_path = NULL;
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
        $target_file = $target_dir . basename($_FILES['img']['name']);
        
        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $img_path = "/uploads/" . basename($_FILES['img']['name']);
        }
    }

    // Bind parameters
    $stmt->bind_param("ssssi", $servico, $descricao, $img_path, $ordem, $visivel);

    //executar e verificar se foi criado
    if ($stmt->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Serviço adicionado com sucesso!'];
        header("Location: servicos.php");
        exit();
    } else {
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Erro ao adicionar serviço.'];
        header("Location: criar_servicos.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Serviço</title>
    
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

<?php require_once __DIR__ . "/../menu.php"; ?> <!-- Inclui menu -->

    <div class="container mt-5">
        <h2 class="text-center">Adicionar Serviço</h2>

        <?php
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            echo '<div class="alert alert-' . $alert['type'] . '" role="alert">' . $alert['message'] . '</div>';
            unset($_SESSION['alert']);
        }
        ?>

        <form action="criar_servicos.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="servico" class="form-label">Nome do Serviço:</label>
                <input type="text" name="servico" id="servico" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Imagem do Serviço (opcional):</label>
                <input type="file" name="img" id="img" class="form-control">
            </div>

            <div class="mb-3">
                <label for="ordem" class="form-label">Ordem:</label>
                <input type="number" name="ordem" id="ordem" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Adicionar Serviço</button>
                <a href="../servicos/servicos.php" class="btn btn-primary">Voltar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
