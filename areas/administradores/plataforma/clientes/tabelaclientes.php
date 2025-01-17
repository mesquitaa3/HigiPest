<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

//select para clientes visiveis = 1
$query = "SELECT * FROM clientes WHERE visivel = 1";  
$result = $conn->query($query);


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
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?> <!-- Inclui menu - menu.php -->

    <div class="container mt-5">
        <h2 class="text-center mb-5">Clientes</h2>

        <!--mostrar mensagem-->
        <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!--button adicionar novo cliente-->
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
                    //verificar se ha clientes para mostrar
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            
                            $status_botao = ($row['visivel'] == 1) ? "Ocultar" : "Mostrar";
                            $cor_botao = ($row['visivel'] == 1) ? "btn-danger" : "btn-success";

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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
