<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");
    exit();
}

include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

if (isset($_GET['id'])) {
    $id_servico = $_GET['id'];
    
    // Verificar o estado atual do serviço
    $query = "SELECT visivel FROM servicos WHERE id_servico = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_servico);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Inverter o estado de visibilidade
    $novo_estado = $row['visivel'] ? 0 : 1;
    
    // Atualizar o estado de visibilidade
    $update_query = "UPDATE servicos SET visivel = ? WHERE id_servico = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $novo_estado, $id_servico);
    
    if ($stmt->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Estado atualizado com sucesso!'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Erro ao atualizar o estado.'];
    }
    
    // Redirecionar de volta para a página apropriada
    if ($novo_estado == 1) {
        header("Location: servicos.php");
    } else {
        header("Location: servicos_ocultos.php");
    }
    exit();
}

$conn->close();
?>
