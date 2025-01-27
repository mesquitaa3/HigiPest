<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $praga = $_POST['praga'];
    $descricao = $_POST['descricao'];
    $ordem = $_POST['ordem'];
    $visivel = 1; // Sempre visível por padrão

    $img_path = NULL;
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
    $upload_ok = true;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $target_file = $target_dir . basename($_FILES['img']['name']);
        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $img_path = "/uploads/" . basename($_FILES['img']['name']);
        } else {
            $upload_ok = false;
        }
    }

    if ($upload_ok) {
        //conexao bd
        require_once __DIR__ . "/../../../../bd/config.php";

        $stmt = $conn->prepare("INSERT INTO pragas (praga, descricao, img, ordem, visivel) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $praga, $descricao, $img_path, $ordem, $visivel);

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Praga adicionada com sucesso!'];
            header("Location: pragas.php");
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Erro ao adicionar praga: ' . $stmt->error];
            header("Location: criar_pragas.php");
        }

        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Erro ao fazer upload da imagem.'];
        header("Location: criar_pragas.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Pragas</title>
    
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

<?php require_once __DIR__ . "/../menu.php"; ?> <!-- Inclui menu -->

    <div class="container mt-5">
        <h2 class="text-center">Adicionar Praga</h2>

        <?php
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            echo "<div class='alert alert-{$message['type']}' role='alert'>{$message['message']}</div>";
            unset($_SESSION['flash_message']);
        }
        ?>

        <form action="criar_pragas.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="praga" class="form-label">Nome da Praga:</label>
                <input type="text" name="praga" id="praga" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Imagem da Praga:</label>
                <input type="file" name="img" id="img" class="form-control">
            </div>

            <div class="mb-3">
                <label for="ordem" class="form-label">Ordem:</label>
                <input type="number" name="ordem" id="ordem" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Adicionar Praga</button>
                <a href="pragas.php" class="btn btn-primary">Voltar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
