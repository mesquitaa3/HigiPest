<?php
//início da sessão e verificação de permissão
session_start();
if ($_SESSION['cargo'] != 'tecnico') {
    header("Location: /login.php");
    exit();
}

//conexao bd
require_once __DIR__ . "/../../bd/config.php";

//inicializa uma variável para armazenar mensagens de erro
$errors = [];

//verifica se os dados do formulário foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //captura os dados do formulário
    $id_cliente = $_POST['id_cliente'] ?? null;
    $id_contrato = $_POST['id_contrato'] ?? null;
    $id_agendamento = $_POST['id_agendamento'] ?? null; // Caso tenha
    $id_visita = $_POST['id_visita'] ?? null; // Caso tenha
    $id_tecnico = $_POST['id_tecnico'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    //verifica quais campos não foram preenchidos
    if (!$id_cliente) {
        $errors[] = 'Cliente';
    }
    if (!$id_contrato) {
        $errors[] = 'Contrato';
    }
    if (!$id_tecnico) {
        $errors[] = 'Técnico';
    }
    if (!$descricao) {
        $errors[] = 'Descrição';
    }

    //se não houver erros, prossegue com a inserção no banco de dados
    if (empty($errors)) {
        //inicia a transação para garantir a consistência
        $conn->begin_transaction();

        try {
            // 1. Inserir na tabela de relatórios
            $stmt = $conn->prepare("
                INSERT INTO relatorios (id_cliente, id_contrato, id_agendamento, id_visita, id_tecnico, descricao)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iiiiss", $id_cliente, $id_contrato, $id_agendamento, $id_visita, $id_tecnico, $descricao);
            $stmt->execute();

            // 2. Remover o agendamento ou visita correspondente
            if (!empty($id_visita)) {
                // Remover a visita da tabela
                $deleteVisitQuery = "DELETE FROM visitas WHERE id_visita = ?";
                $deleteVisitStmt = $conn->prepare($deleteVisitQuery);
                $deleteVisitStmt->bind_param("i", $id_visita); // Removido o espaço extra
                $deleteVisitStmt->execute();
            } elseif (!empty($id_agendamento)) {
                // Remover o agendamento da tabela
                $deleteQuery = "DELETE FROM agendamentos WHERE id_agendamento = ?";
                $deleteStmt = $conn->prepare($deleteQuery);
                $deleteStmt->bind_param("i", $id_agendamento); // Removido o espaço extra
                $deleteStmt->execute();
            }

            // Confirma a transação
            $conn->commit();

            // Define uma mensagem de sucesso na sessão
            $_SESSION['mensagem_sucesso'] = 'Relatório finalizado com sucesso!';

            // Redireciona para a página da agenda do técnico
            header("Location: agenda.php");
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
        echo '<div class="alert alert-warning text-center" role="alert">';
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