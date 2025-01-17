<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, redireciona para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

// Verifique se o ID do serviço foi passado na URL
if (isset($_GET['id_praga']) && is_numeric($_GET['id_praga'])) {
    $id_praga = $_GET['id_praga'];

    // Consultar os dados do serviço
    $query = "SELECT * FROM pragas WHERE id_praga = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_praga);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $praga = $result->fetch_assoc();
    } else {
        echo "praga não encontrado.";
        exit();
    }
} else {
    echo "ID da praga não especificado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualiza os dados do serviço
    $praga_nome = $_POST['praga'];
    $descricao = $_POST['descricao'];
    $ordem = $_POST['ordem'];

    // Mantém a imagem atual se não houver nova imagem
    $img = $praga['img'];  // valor original da imagem

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
    $update_query = "UPDATE pragas SET praga = ?, descricao = ?, ordem = ?, img = ? WHERE id_praga = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssisi", $praga_nome, $descricao, $ordem, $img, $id_praga);

    if ($stmt->execute()) {
        echo "Praga atualizado com sucesso!";
        // Redireciona para outra página (por exemplo, para a lista de serviços)
        header("Location: pragas.php");
        exit();
    } else {
        echo "Erro ao atualizar a praga.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Praga</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/site/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Praga</h2>
        <form method="POST" action="editar_praga.php?id_praga=<?php echo $praga['id_praga']; ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="praga" class="form-label">Praga</label>
                <input type="text" class="form-control" id="praga" name="praga" value="<?php echo htmlspecialchars($praga['praga']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($praga['descricao']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Imagem Atual</label>
                <div>
                    <?php if ($praga['img']) : ?>
                        <img src="<?php echo $praga['img']; ?>" alt="Imagem do Serviço" class="img-fluid" width="200">
                    <?php else : ?>
                        <p>Sem imagem cadastrada.</p>
                    <?php endif; ?>
                </div>
                <label for="new_img" class="form-label">Nova Imagem (se desejar alterar)</label>
                <input type="file" class="form-control" id="new_img" name="new_img">
            </div>

            <div class="mb-3">
                <label for="ordem" class="form-label">Ordem</label>
                <input type="number" class="form-control" id="ordem" name="ordem" value="<?php echo $praga['ordem']; ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Atualizar Serviço</button>
            <a href="pragas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>
