<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

if (isset($_GET['id'])) {
    $id_praga = $_GET['id'];
    
    // Verificar o estado atual da praga
    $query = "SELECT visivel FROM pragas WHERE id_praga = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_praga);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Inverter o estado de visibilidade
    $novo_estado = $row['visivel'] ? 0 : 1;
    
    // Atualizar o estado de visibilidade
    $update_query = "UPDATE pragas SET visivel = ? WHERE id_praga = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $novo_estado, $id_praga);
    
    if ($stmt->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Estado atualizado com sucesso!'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Erro ao atualizar o estado.'];
    }
    
    // Redirecionar de volta para a página apropriada
    if ($novo_estado == 1) {
        header("Location: pragas.php");
    } else {
        header("Location: pragas_ocultas.php");
    }
    exit();
}

$conn->close();
?>
