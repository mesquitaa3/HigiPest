<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  //se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

//select para contactos
$query = "SELECT * FROM contactos";
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

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/site/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Contactos</h2>

        <!-- button criar contacto -->
        <div class="d-flex justify-content-between mb-4">
            <a href="criar_contacto.php" class="btn btn-primary">Adicionar novo contacto</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Tipo de Contacto</th>
                        <th>Informação</th>
                        <th>Ordem</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //verificar se há contactos para exibir
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_botao = ($row['visivel'] == 1) ? "Ocultar" : "Mostrar";
                            $cor_botao = ($row['visivel'] == 1) ? "btn-danger" : "btn-success";
                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_contacto']) . '</td>
                                <td>' . htmlspecialchars($row['tipo_contacto']) . '</td>
                                <td>' . htmlspecialchars($row['informacao']) . '</td>
                                <td>' . htmlspecialchars($row['ordem']) . '</td>
                                <td>
                                    <a href="editar_contacto.php?id_contacto=' . $row['id_contacto'] . '" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="ocultar_contacto.php?id=' . $row['id_contacto'] . '" class="btn ' . $cor_botao . ' btn-sm">' . $status_botao . '</a>
                                </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Nenhum contacto disponível no momento.</td></tr>';
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Button voltar para index-site -->
        <div class="d-flex justify-content-between mb-4">
            <a href="/web/areas/administradores/site/index.php" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>
