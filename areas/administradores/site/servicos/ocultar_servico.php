<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, redireciona para o login
    exit();
}

// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Verificar se o ID do serviço foi passado
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_servico = $_GET['id'];

    // Consultar o status atual de visibilidade
    $query = "SELECT visivel FROM servicos WHERE id_servico = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_servico);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $visivel = $row['visivel'];

        // Alternar entre visível e oculto
        $novo_status = ($visivel == 1) ? 0 : 1;

        // Atualizar o status de visibilidade
        $update_query = "UPDATE servicos SET visivel = ? WHERE id_servico = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $novo_status, $id_servico);

        if ($stmt->execute()) {
            // Redireciona de volta para a página de serviços
            header("Location: servicos.php");
            exit();
        } else {
            echo "Erro ao atualizar o status do serviço.";
        }
    } else {
        echo "Serviço não encontrado.";
    }
} else {
    echo "ID do serviço não especificado.";
}

$conn->close();
?>
