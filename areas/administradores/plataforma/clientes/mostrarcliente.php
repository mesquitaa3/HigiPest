<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");  // se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

//verifica id cliente
if (isset($_GET['mostrar']) && is_numeric($_GET['mostrar'])) {
    $id_cliente = $_GET['mostrar'];

    //atualizar o cliente para visivel = 1
    $query = "UPDATE clientes SET visivel = 1 WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cliente);

    
    if ($stmt->execute()) {
        //volta para a pagina clientesocultos.php com mensagem de sucesso ao ativar cliente
        header("Location: clientesocultos.php?msg=cliente_mostrado");
        exit();
    } else {
        //
        header("Location: clientesocultos.php?msg=erro_mostrando_cliente");
        exit();
    }
} else {
    //se o id nao for valido
    header("Location: clientesocultos.php");
    exit();
}
?>
