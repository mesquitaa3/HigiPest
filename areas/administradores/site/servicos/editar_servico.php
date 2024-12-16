<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, redireciona para o login
    exit();
}

  // Incluir arquivo de conexão à base de dados
  include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php"); //script de acesso à base de dados

// Verifique se o ID do serviço foi passado na URL
if (isset($_GET['id_servico']) && is_numeric($_GET['id_servico'])) {
    $id_servico = $_GET['id_servico'];

    // Consultar os dados do serviço
    $query = "SELECT * FROM servicos WHERE id_servico = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_servico);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $servico = $result->fetch_assoc();
    } else {
        echo "Serviço não encontrado.";
        exit();
    }
} else {
    echo "ID do serviço não especificado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualiza os dados do serviço
    $servico_nome = $_POST['servico'];
    $descricao = $_POST['descricao'];
    $ordem = $_POST['ordem'];

    // Mantém a imagem atual se não houver nova imagem
    $img = $servico['img'];  // valor original da imagem

    // Verificar se foi feito upload de uma nova imagem
    if (isset($_FILES['new_img']) && $_FILES['new_img']['error'] == 0) {
        // Definir o diretório de upload
        $target_dir = "/web/uploads/";
        $target_file = $target_dir . basename($_FILES['new_img']['name']);
        
        // Mover o arquivo da imagem para o diretório de uploads
        if (move_uploaded_file($_FILES['new_img']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
            // Substituir a imagem no banco de dados
            $img = $target_file;  // Caminho da nova imagem
        } else {
            echo "Erro ao enviar a imagem.";
        }
    }

    // Atualize a base de dados com os novos valores
    $update_query = "UPDATE servicos SET servico = ?, descricao = ?, ordem = ?, img = ? WHERE id_servico = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssisi", $servico_nome, $descricao, $ordem, $img, $id_servico);

    if ($stmt->execute()) {
        echo "Serviço atualizado com sucesso!";
        // Redireciona para outra página (por exemplo, para a lista de serviços)
        header("Location: servicos.php");
        exit();
    } else {
        echo "Erro ao atualizar o serviço.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/site/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Serviço</h2>
        <form method="POST" action="editar_servico.php?id_servico=<?php echo $servico['id_servico']; ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="servico" class="form-label">Serviço</label>
                <input type="text" class="form-control" id="servico" name="servico" value="<?php echo htmlspecialchars($servico['servico']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($servico['descricao']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Imagem Atual</label>
                <div>
                    <?php if ($servico['img']) : ?>
                        <img src="<?php echo $servico['img']; ?>" alt="Imagem do Serviço" class="img-fluid" width="200">
                    <?php else : ?>
                        <p>Sem imagem cadastrada.</p>
                    <?php endif; ?>
                </div>
                <label for="new_img" class="form-label">Nova Imagem (se desejar alterar)</label>
                <input type="file" class="form-control" id="new_img" name="new_img">
            </div>

            <div class="mb-3">
                <label for="ordem" class="form-label">Ordem</label>
                <input type="number" class="form-control" id="ordem" name="ordem" value="<?php echo $servico['ordem']; ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Atualizar Serviço</button>
            <a href="servicos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>
