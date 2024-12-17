<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // se não for administrador, volta para o login
    exit();
}

// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Verificar se o ID do cliente foi passado
if (isset($_GET['mostrar']) && is_numeric($_GET['mostrar'])) {
    $id_cliente = $_GET['mostrar'];

    // Atualizar o cliente para visivel = 1 (mostrar novamente)
    $query = "UPDATE clientes SET visivel = 1 WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cliente);

    // Executar a consulta
    if ($stmt->execute()) {
        // Redirecionar para a página de clientes ocultos com mensagem de sucesso
        header("Location: clientesocultos.php?msg=cliente_mostrado");
        exit();
    } else {
        // Caso haja erro na atualização
        header("Location: clientesocultos.php?msg=erro_mostrando_cliente");
        exit();
    }
} else {
    // Se o ID não for passado ou não for válido
    header("Location: clientesocultos.php");
    exit();
}
?>
