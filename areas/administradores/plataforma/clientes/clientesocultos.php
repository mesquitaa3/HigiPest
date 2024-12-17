<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  //se não for administrador, volta para o login
    exit();
}

// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Consultar os clientes ocultos (visivel = 0)
$query = "SELECT * FROM clientes WHERE visivel = 0";  
$result = $conn->query($query);

// Mensagens de status da operação
$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'cliente_ocultado') {
        $message = "Cliente ocultado com sucesso!";
    } elseif ($_GET['msg'] == 'erro_ocultando_cliente') {
        $message = "Erro ao ocultar cliente!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes Ocultos</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Clientes Ocultos</h2>

        <!-- Exibe a mensagem de status -->
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Morada</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Verificar se há clientes ocultos para exibir
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['nome_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['email_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['telemovel_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['morada_cliente']) . '</td>
                                <td>
                                    <a href="mostrarcliente.php?mostrar=' . $row['id_cliente'] . '" class="btn btn-success btn-sm">Mostrar</a>
                                </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">Nenhum cliente oculto no momento.</td></tr>';
                    }

                    // Fechar a conexão
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Button voltar para index-site -->
        <div class="d-flex justify-content-between mb-4">
            <a href="tabelaclientes.php" class="btn btn-primary">Voltar para Clientes</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
