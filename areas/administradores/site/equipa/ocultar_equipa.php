<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, redireciona para o login
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

// Verificar se o ID do serviço foi passado
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_membro = $_GET['id'];

    // Consultar o status atual de visibilidade
    $query = "SELECT visivel FROM equipa WHERE id_membro = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_membro);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $visivel = $row['visivel'];

        // Alternar entre visível e oculto
        $novo_status = ($visivel == 1) ? 0 : 1;

        // Atualizar o status de visibilidade
        $update_query = "UPDATE equipa SET visivel = ? WHERE id_membro = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $novo_status, $id_membro);

        if ($stmt->execute()) {
            // Redireciona de volta para a página de serviços
            header("Location: equipa.php");
            exit();
        } else {
            echo "Erro ao atualizar o status da praga.";
        }
    } else {
        echo "praga não encontrada.";
    }
} else {
    echo "ID da praga não especificado.";
}

$conn->close();
?>
