<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  //se não for administrador, volta para o login
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $servico = $_POST['servico'];
    $descricao = $_POST['descricao'];
    $ordem = $_POST['ordem'];
    $visivel = isset($_POST['visivel']) ? 1 : 0; // Checkbox

    // Configurar diretório para upload
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/web/uploads/"; // Caminho absoluto do diretório
    $img_path = NULL; // Inicializa a variável para a imagem (pode ser NULL se nenhuma imagem for enviada)

    // Verificar se o diretório existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Criar diretório se não existir
    }

    // Verificar se o arquivo foi enviado sem erros
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        // Definir o caminho do arquivo de imagem
        $target_file = $target_dir . basename($_FILES['img']['name']);
        
        // Mover o arquivo para o diretório desejado
        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Arquivo enviado com sucesso!'];
            $img_path = "/web/uploads/" . basename($_FILES['img']['name']); // Caminho relativo para a imagem
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Erro ao mover o arquivo para o diretório.'];
        }
    } else {
        // Se não houver imagem, definir uma imagem padrão ou deixar como NULL
        $img_path = NULL; // Pode ser "/web/uploads/sem_imagem.jpg" se desejar uma imagem padrão
    }

    // Salvar informações na base de dados
    if ($img_path !== NULL) {
        // Incluir arquivo de conexão à base de dados
        include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php"); //script de acesso à base de dados

        // Insere no banco de dados
        $query = "INSERT INTO servicos (servico, descricao, img, ordem, visivel) 
                  VALUES ('$servico', '$descricao', '$img_path', '$ordem', '$visivel')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Serviço adicionado com sucesso!'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Erro ao adicionar serviço: ' . mysqli_error($conn)];
        }

        mysqli_close($conn);
    }

    // Redirecionar para evitar reenvio
    header("Location: criar_servicos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Serviço</title>
    
    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

    <!-- Menu -->
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/site/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center">Adicionar Serviço</h2>

        <!-- Exibir alertas -->
        <?php
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            echo '<div class="alert alert-' . $alert['type'] . '" role="alert">' . $alert['message'] . '</div>';
            unset($_SESSION['alert']); // Apagar mensagem após exibir
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

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="visivel" id="visivel">
                <label class="form-check-label" for="visivel">Visível</label>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Adicionar Serviço</button>
                <a href="/web/areas/administradores/site/servicos/servicos.php" class="btn btn-primary">Voltar</a>
            </div>
        </form>
    </div>

    <!-- Footer -->

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
