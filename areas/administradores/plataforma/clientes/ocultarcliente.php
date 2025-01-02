<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  //se não for administrador, volta para o login
    exit();
}

//conexao bd
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

//verifica o id do cliente
if (isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    //atualizar o cliente para visivel = 1 (ativo)
    $query = "UPDATE clientes SET visivel = 0 WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_cliente);

    if ($stmt->execute()) {
        //redirecionar para a pagina de clientes com mensagem de sucesso
        header("Location: tabelaclientes.php?msg=cliente_ocultado");
    } else {
        //redirecionar para a pagina de clientes com mensagem de erro
        header("Location: tabelaclientes.php?msg=erro_ocultando_cliente");
    }

    
    $stmt->close();
    $conn->close();
} else {
    
    header("Location: tabelaclientes.php?msg=erro");
}
?>
