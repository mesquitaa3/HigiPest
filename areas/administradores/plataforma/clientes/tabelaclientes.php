<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, volta para o login
    exit();
}

// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Consultar os clientes visíveis (visivel = 1)
$query = "SELECT * FROM clientes WHERE visivel = 1";  
$result = $conn->query($query);

// Mensagens de status da operação
$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'cliente_adicionado') {
        $message = "Cliente adicionado com sucesso!";
    } elseif ($_GET['msg'] == 'erro_adicionando_cliente') {
        $message = "Erro ao adicionar cliente!";
    } elseif ($_GET['msg'] == 'cliente_ocultado') {
        $message = "Cliente ocultado com sucesso!";
    } elseif ($_GET['msg'] == 'erro_ocultando_cliente') {
        $message = "Erro ao ocultar cliente!";
    } elseif ($_GET['msg'] == 'cliente_atualizado') {
        $message = "Cliente atualizado com sucesso!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Clientes</h2>

        <!-- Exibe a mensagem de status -->
        <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Button adicionar novo cliente -->
        <div class="d-flex justify-content-between mb-4">
            <a href="criarcliente.php" class="btn btn-primary">Adicionar novo cliente</a>
            <a href="clientesocultos.php" class="btn btn-secondary">Mostrar Clientes Ocultos</a>
        </div>

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
                    // Verificar se há clientes para exibir
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Definir o texto e a cor do botão com base no estado de visibilidade
                            $status_botao = ($row['visivel'] == 1) ? "Ocultar" : "Mostrar";
                            $cor_botao = ($row['visivel'] == 1) ? "btn-danger" : "btn-success"; // Define a cor do botão

                            echo '
                            <tr>
                                <td>' . htmlspecialchars($row['id_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['nome_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['email_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['telemovel_cliente']) . '</td>
                                <td>' . htmlspecialchars($row['morada_cliente']) . '</td>
                                <td>
                                    <a href="editarcliente.php?id_cliente=' . $row['id_cliente'] . '" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="ocultarcliente.php?id_cliente=' . $row['id_cliente'] . '" class="btn ' . $cor_botao . ' btn-sm">' . $status_botao . '</a>
                                </td>
                            </tr>
                            ';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">Nenhum cliente disponível no momento.</td></tr>';
                    }

                    // Fechar a conexão
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Button voltar para index-site -->
        <div class="d-flex justify-content-between mb-4">
            <a href="/web/areas/administradores/index.php" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
