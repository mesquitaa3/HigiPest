<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

//verifica id contrato
if (isset($_GET['mostrar']) && is_numeric($_GET['mostrar'])) {
    $id_contrato = $_GET['mostrar'];

    //atualizar o contrato para visivel = 1
    $query = "UPDATE contratos SET visivel = 1 WHERE id_contrato = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_contrato);

    
    if ($stmt->execute()) {
        //volta para a pagina contratosocultos.php com mensagem de sucesso ao ativar cliente
        header("Location: contratosocultos.php?msg=cliente_mostrado");
        exit();
    } else {
        //
        header("Location: contratosocultos.php?msg=erro_mostrar_cliente");
        exit();
    }
} else {
    //se o id nao for valido
    header("Location: contratosocultos.php");
    exit();
}
?>
