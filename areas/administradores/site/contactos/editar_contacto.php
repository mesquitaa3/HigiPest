<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, redireciona para o login
    exit();
}

  // Incluir arquivo de conexão à base de dados
  include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php"); //script de acesso à base de dados

// Verifique se o ID do serviço foi passado na URL
if (isset($_GET['id_contacto']) && is_numeric($_GET['id_contacto'])) {
    $id_contacto = $_GET['id_contacto'];

    // Consultar os dados do serviço
    $query = "SELECT * FROM contactos WHERE id_contacto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_contacto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $contacto = $result->fetch_assoc();
    } else {
        echo "Contacto não encontrado.";
        exit();
    }
} else {
    echo "ID do contacto não especificado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualiza os dados do serviço
    $tipo_contacto = $_POST['tipo_contacto'];
    $informacao = $_POST['informacao'];
    $ordem = $_POST['ordem'];

    // Atualize a base de dados com os novos valores
    $update_query = "UPDATE contactos SET tipo_contacto = ?, informacao = ?, ordem = ? WHERE id_contacto = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ssii', $tipo_contacto, $informacao, $ordem, $id_contacto);


    if ($stmt->execute()) {
        echo "Contacto atualizado com sucesso!";
        // Redireciona para outra página (por exemplo, para a lista de serviços)
        header("Location: contactos.php");
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
    <title>Editar Contactos</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/site/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Contactos</h2>
        <form method="POST" action="editar_contacto.php?id_contacto=<?php echo $contacto['id_contacto']; ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="tipo_contacto" class="form-label">Tipo de Contacto</label>
                <input type="text" class="form-control" id="tipo_contacto" name="tipo_contacto" value="<?php echo htmlspecialchars($contacto['tipo_contacto']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="informacao" class="form-label">Informação:</label>
                <textarea class="form-control" id="informacao" name="informacao" rows="4" required><?php echo htmlspecialchars($contacto['informacao']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="ordem" class="form-label">Ordem</label>
                <input type="number" class="form-control" id="ordem" name="ordem" value="<?php echo $praga['ordem']; ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Atualizar Serviço</button>
            <a href="contactos.php" class="btn btn-secondary">Cancelar</a>
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
