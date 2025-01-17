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
    $id_contacto = $_GET['id'];

    // Consultar o status atual de visibilidade
    $query = "SELECT visivel FROM contacto WHERE id_contacto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_contacto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $visivel = $row['visivel'];

        // Alternar entre visível e oculto
        $novo_status = ($visivel == 1) ? 0 : 1;

        // Atualizar o status de visibilidade
        $update_query = "UPDATE contacto SET visivel = ? WHERE id_contacto = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $novo_status, $id_contacto);

        if ($stmt->execute()) {
            // Redireciona de volta para a página de serviços
            header("Location: contactos.php");
            exit();
        } else {
            echo "Erro ao atualizar o status do contacto.";
        }
    } else {
        echo "contacto não encontrada.";
    }
} else {
    echo "ID da praga não especificado.";
}

$conn->close();
?>
