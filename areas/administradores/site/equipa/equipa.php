<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");  //se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

// Consultar os serviços
$query = "SELECT * FROM equipa";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

<?php require_once __DIR__ . "/../menu.php"; ?> <!-- Inclui menu -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Equipa</h2>

        <!-- button criar serviço -->
        <div class="d-flex justify-content-between mb-4">
            <a href="criar_equipa.php" class="btn btn-primary">Adicionar novo membro</a>
        </div>

        <div class="table-responsive">
        <table class="table table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Função</th>
            <th>Foto</th>
            <th>Ordem</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Verificar se há membros para exibir
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status_botao = ($row['visivel'] == 1) ? "Ocultar" : "Mostrar";
                $cor_botao = ($row['visivel'] == 1) ? "btn-danger" : "btn-success";
                $img_path = htmlspecialchars(basename($row['img'])); // Obter o nome da imagem
                echo '
                <tr>
                    <td>' . htmlspecialchars($row['id_membro']) . '</td>
                    <td>' . htmlspecialchars($row['nome_membro']) . '</td>
                    <td>' . htmlspecialchars($row['funcao']) . '</td>
                    <td>
                        <img src="uploads/' . $img_path . '" alt="' . htmlspecialchars($row['nome_membro']) . '" style="width: 100px; height: 60px; object-fit: cover;">
                    </td>
                    <td>' . htmlspecialchars($row['ordem']) . '</td>
                    <td>
                        <a href="editar_equipa.php?id_membro=' . $row['id_membro'] . '" class="btn btn-warning btn-sm">Editar</a>
<a href="ocultar_equipa.php?id=' . $row['id_membro'] . '" class="btn ' . $cor_botao . ' btn-sm">' . $status_botao . '</a>                    </td>
                </tr>
                ';
            }
        } else {
            echo '<tr><td colspan="6" class="text-center">Nenhum membro disponível no momento.</td></tr>';
        }

        // Fechar a conexão
        $conn->close();
        ?>
    </tbody>
</table>
        </div>
        <!-- Button voltar para index-site -->
        <div class="d-flex justify-content-between mb-4">
            <a href="../index.php" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
