<?php
// gerar_relatorio.php


// Inclua o arquivo de configuração do banco de dados
require_once __DIR__ . "/../../../../bd/config.php";

// Inicializa uma variável para armazenar mensagens de erro
$errors = [];

// Verifica se os dados do formulário foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $id_cliente = $_POST['id_cliente'] ?? null;
    $id_estabelecimento = $_POST['id_estabelecimento'] ?? null;
    $id_agendamento = $_POST['id_agendamento'] ?? null; // Caso tenha
    $id_visita = $_POST['id_visita'] ?? null; // Caso tenha
    $id_tecnico = $_POST['id_tecnico'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    // Verifica quais campos não foram preenchidos
    if (!$id_cliente) {
        $errors[] = 'Cliente';
    }
    if (!$id_estabelecimento) {
        $errors[] = 'Estabelecimento';
    }
    if (!$id_tecnico) {
        $errors[] = 'Técnico';
    }
    if (!$descricao) {
        $errors[] = 'Descrição';
    }

    // Se não houver erros, prossegue com a inserção no banco de dados
    if (empty($errors)) {
        // Inicia a transação para garantir a consistência
        $conn->begin_transaction();

        try {
            // 1. Inserir na tabela de relatórios
            $stmt = $conn->prepare("
                INSERT INTO relatorios (id_cliente, id_estabelecimento, id_agendamento, id_visita, id_tecnico, descricao)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iiiiss", $id_cliente, $id_estabelecimento, $id_agendamento, $id_visita, $id_tecnico, $descricao);
            $stmt->execute();

            // 2. Remover o agendamento ou visita correspondente
            if (!empty($id_visita)) {
                // Remover a visita da tabela
                $deleteVisitQuery = "DELETE FROM visitas WHERE id_visita = ?";
                $deleteVisitStmt = $conn->prepare($deleteVisitQuery);
                $deleteVisitStmt->bind_param("i", $id_visita);
                $deleteVisitStmt->execute();
            } elseif (!empty($id_agendamento)) {
                // Remover o agendamento da tabela
                $deleteQuery = "DELETE FROM agendamentos WHERE id_agendamento = ?";
                $deleteStmt = $conn->prepare($deleteQuery);
                $deleteStmt->bind_param("i", $id_agendamento);
                $deleteStmt->execute();
            }

            // Confirma a transação
            $conn->commit();

            // Define uma mensagem de sucesso na sessão
            session_start();
            $_SESSION['mensagem_sucesso'] = 'Relatório finalizado com sucesso!';

            // Redireciona para a página da agenda
            header("Location: /web/areas/administradores/plataforma/agenda/agenda.php");
            exit();

        } catch (Exception $e) {
            // Se ocorrer erro, desfaz a transação
            $conn->rollback();

            echo '<div class="container mt-5">';
            echo '<div class="alert alert-danger text-center" role="alert">';
            echo 'Erro ao gerar o relatório: ' . $e->getMessage();
            echo '</div></div>';
        }

        // Fecha a conexão
        $stmt->close();
        if (isset($deleteVisitStmt)) {
            $deleteVisitStmt->close();
        }
        if (isset($deleteStmt)) {
            $deleteStmt->close();
        }
        $conn->close();
    } else {
        // Se houver erros, exibe uma mensagem de erro
        echo '<div class="container mt-5">';
        echo '<div class="alert alert -warning text-center" role="alert">';
        echo 'Os seguintes campos não foram preenchidos: ' . implode(', ', $errors) . '.';
        echo '</div></div>';
    }
} else {
    echo '<div class="container mt-5">';
    echo '<div class="alert alert-warning text-center" role="alert">';
    echo 'Acesso inválido.';
    echo '</div></div>';
}
?>
