<?php
session_start();
if ($_SESSION['cargo'] != 'tecnico') {
    header("Location: /login.php");
    exit();
}


require_once __DIR__ . "/../../bd/config.php";


// Consulta para buscar o nome do técnico
$query_tecnico = "
    SELECT nome_tecnico 
    FROM tecnicos 
    WHERE id_tecnico = ?
";


require_once __DIR__ . "/../../bd/config.php";

// Obter o ID do relatório da URL
$id_relatorio = $_GET['id'] ?? null;

if ($id_relatorio) {
    // Consulta para buscar os detalhes do relatório
    $query = "
        SELECT r.*, c.nome_cliente, e.estabelecimento_contrato, t.nome_tecnico, c.email_cliente
        FROM relatorios r
        JOIN clientes c ON r.id_cliente = c.id_cliente
        JOIN contratos e ON r.id_contrato = e.id_contrato
        LEFT JOIN tecnicos t ON r.id_tecnico = t.id_tecnico
        WHERE r.id_relatorio = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_relatorio);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $relatorio = $result->fetch_assoc();
    } else {
        echo '<div class="alert alert-warning text-center">Nenhum relatório encontrado.</div>';
        exit();
    }
} else {
    echo '<div class="alert alert-warning text-center">Nenhum ID fornecido!</div>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Relatório</title>
    <link rel="stylesheet" href="../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styles/styles.css"></head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Detalhes do Relatório</h2>
        <div class="card">
            <div class="card-header">
                <h5>Relatório ID: <?= htmlspecialchars($relatorio['id_relatorio']) ?></h5>
            </div>
            <div class="card-body">
                <p><strong>Cliente:</strong> <?= htmlspecialchars($relatorio['nome_cliente']) ?></p>
                <p><strong>Estabelecimento:</strong> <?= htmlspecialchars($relatorio['estabelecimento_contrato']) ?></p>
                <p><strong>Técnico:</strong> <?= htmlspecialchars($relatorio['nome_tecnico']) ?></p>
                <p><strong>Descrição:</strong> <?= htmlspecialchars($relatorio['descricao']) ?></p>
                <p><strong>Criado em:</strong> <?= date('d/m/Y H:i', strtotime($relatorio['criado_em'])) ?></p>
            </div>
        </div>

        <!-- Botões para gerar PDF, imprimir e enviar por e-mail -->
        <div class="mt-5">
            <!-- Botão para imprimir apenas o relatório -->
            <button class="btn btn-info" onclick="imprimirRelatorio();">Imprimir</button>

            <!-- Botão para download do relatório em PDF -->
            <button class="btn btn-primary" onclick="window.location.href='gerar_pdf.php?id=<?= $id_relatorio ?>'">Baixar Relatório em PDF</button>

            <!-- Botão para enviar por e-mail -->
            <button id="emailBtn" class="btn btn-success">Enviar por E-mail</button>
        </div>

        <div>
            <a href="trabalhos_realizados.php" class="btn btn-secondary mt-3">Voltar</a>
        </div>
    </div>

    <script>
    // Função para imprimir o relatório
    function imprimirRelatorio() {
        window.print();
    }

    // Quando o botão "Enviar por E-mail" for clicado
    document.getElementById('emailBtn').addEventListener('click', function() {
        const email = '<?= htmlspecialchars($relatorio['email_cliente'] ?? '') ?>'; // E-mail do cliente
        const id_relatorio = '<?= $id_relatorio ?>'; // ID do relatório

        if (!email) {
            alert('O e -mail do cliente não está disponível!');
            return;
        }

        // Cria o corpo da requisição
        const formData = new FormData();
        formData.append('email', email);
        formData.append('id_relatorio', id_relatorio);

        // Faz a requisição AJAX para enviar_email.php
        fetch('enviar_email.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Mostra a resposta do servidor (sucesso ou erro)
        })
        .catch(error => {
            console.error('Erro ao enviar o e-mail:', error);
            alert('Erro ao enviar o e-mail. Verifique a conexão.');
        });
    });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>