<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");  // Se não for administrador, volta para o login
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $nome_membro = $_POST['nome_membro'];
    $funcao = $_POST['funcao'];
    $ordem = $_POST['ordem'];
    $visivel = isset($_POST['visivel']) ? 1 : 0; // Checkbox

    // Inicializar a variável $img com NULL, caso não seja fornecida imagem
    $img_path = NULL;

    // Configurar diretório para upload
    $target_dir = __DIR__ . "/../../../../uploads/"; // Caminho absoluto do diretório
    $upload_ok = true;

    // Verificar se o diretório existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Criar diretório se não existir
    }

    // Verificar se o arquivo foi enviado sem erros
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        // Definir o caminho do arquivo
        $target_file = $target_dir . basename($_FILES['img']['name']);

        // Mover o arquivo para o diretório desejado
        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $img_path = "/uploads/" . basename($_FILES['img']['name']); // Caminho relativo para a imagem
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Arquivo enviado com sucesso!'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Erro ao mover o arquivo para o diretório.'];
            $upload_ok = false;
        }
    }

    // Salvar informações na base de dados
    if ($upload_ok) {
        // Conexão com a base de dados
        require_once __DIR__ . "/../../../../bd/config.php";

        // Verifica se uma imagem foi fornecida, senão insere NULL
        if ($img_path === NULL) {
            $query = "INSERT INTO equipa (nome_membro, funcao, img, ordem, visivel) 
                      VALUES ('$nome_membro', '$funcao', NULL, '$ordem', '$visivel')";
        } else {
            $query = "INSERT INTO equipa (nome_membro, funcao, img, ordem, visivel) 
                      VALUES ('$nome_membro', '$funcao', '$img_path', '$ordem', '$visivel')";
        }

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Membro adicionado com sucesso!'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Erro ao adicionar membro: ' . mysqli_error($conn)];
        }

        mysqli_close($conn);
    }

    // Redirecionar para evitar reenvio
    header("Location: criar_equipa.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIGIPEST - Definições | Adicionar Membro</title>
    
    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

    <!-- Menu -->
    <?php require_once __DIR__ . "/../menu.php"; ?> <!-- Inclui menu -->


    <div class="container mt-5">
        <h2 class="text-center">Adicionar Novo Membro</h2>

        <!-- Exibir alertas -->
        <?php
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            echo '<div class="alert alert-' . $alert['type'] . '" role="alert">' . $alert['message'] . '</div>';
            unset($_SESSION['alert']); // Apagar mensagem após exibir
        }
        ?>

        <form action="criar_equipa.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nome_membro" class="form-label">Nome:</label>
                <input type="text" name="nome_membro" id="nome_membro" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="funcao" class="form-label">Função:</label>
                <textarea name="funcao" id="funcao" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Imagem do membro:</label>
                <input type="file" name="img" id="img" class="form-control">
            </div>

            <div class="mb-3">
                <label for="ordem" class="form-label">Ordem:</label>
                <input type="number" name="ordem" id="ordem" class="form-control" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="visivel" id="visivel">
                <label class="form-check-label" for="visivel">Visível</label>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Adicionar Membro</button>
                <a href="equipa.php" class="btn btn-primary">Voltar</a>
            </div>
        </form>
    </div>

    <!-- Footer -->

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
