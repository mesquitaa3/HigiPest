<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");  //se não for administrador, volta para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

//verifica o id do contrato
if (isset($_GET['id_contrato'])) {
    $id_contrato = $_GET['id_contrato'];

    //atualizar o cliente para visivel = 1 (ativo)
    $query = "UPDATE contratos SET visivel = 0 WHERE id_contrato = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_contrato);

    if ($stmt->execute()) {
        // Redirecionar para a página de contratos com uma mensagem de sucesso
        header("Location: tabelacontratos.php?msg=cliente_ocultado");
    } else {
        // Redirecionar para a página de contratos com uma mensagem de erro
        header("Location: id_contrato.php?msg=erro_ocultar_cliente");
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
} else {
    // Redirecionar caso o ID do contrato não seja passado
    header("Location: id_contrato.php?msg=erro");
}
?>
