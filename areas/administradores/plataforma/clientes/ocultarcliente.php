<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  //se não for administrador, volta para o login
    exit();
}

// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Verificar se o ID do cliente foi passado na URL
if (isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    // Atualizar o status de visibilidade do cliente para 0 (ocultar)
    $query = "UPDATE clientes SET visivel = 0 WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_cliente);

    if ($stmt->execute()) {
        // Redirecionar para a página de clientes com uma mensagem de sucesso
        header("Location: tabelaclientes.php?msg=cliente_ocultado");
    } else {
        // Redirecionar para a página de clientes com uma mensagem de erro
        header("Location: tabelaclientes.php?msg=erro_ocultando_cliente");
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
} else {
    // Redirecionar caso o ID do cliente não seja passado
    header("Location: tabelaclientes.php?msg=erro");
}
?>
