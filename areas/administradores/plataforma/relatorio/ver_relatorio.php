<?php
// Início da sessão e verificação de permissão
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

// Conexão com o banco de dados
require_once __DIR__ . "/../../../../bd/config.php";

// Obter o ID do relatório da URL
$id_relatorio = $_GET['id'] ?? null;

// Inicializar variável $relatorio
$relatorio = [];

// Verifica se o ID foi fornecido
if ($id_relatorio) {
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Consulta para buscar os dados do relatório, incluindo o e-mail do cliente
    $query_relatorio = "
        SELECT r.*, c.nome_cliente, c.email_cliente, e.estabelecimento_contrato, t.nome_tecnico
        FROM relatorios r
        JOIN clientes c ON r.id_cliente = c.id_cliente
        JOIN contratos e ON r.id_contrato = e.id_contrato
        LEFT JOIN tecnicos t ON r.id_tecnico = t.id_tecnico
        WHERE r.id_relatorio = ?
    ";

    $stmt = $conn->prepare($query_relatorio);
    $stmt->bind_param("i", $id_relatorio);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o relatório foi encontrado
    if ($result->num_rows > 0) {
        $relatorio = $result->fetch_assoc();
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conn->close();
} else {
    echo '<div class="alert alert-warning text-center mt-5">Nenhum ID fornecido!</div>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Relatório</title>
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
</head>
<body class="bg-light">
<?php require("../../menu.php"); ?> <!-- Inclui menu - menu.php -->
    <div class="container mt-5">
        <?php if (!empty($relatorio)) { ?>
            <h2 class="text-center mb-5">Detalhes do Relatório</h2>
            <div id="relatorio-imprimir">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Detalhes do Relatório</div>
                <div class="card-body">
                    <p><strong>Cliente:</strong> <?= htmlspecialchars($relatorio['nome_cliente']) ?></p>
                    <p><strong>Estabelecimento:</strong> <?= htmlspecialchars($relatorio['estabelecimento_contrato']) ?></p>
                    <p><strong>Técnico:</strong> <?= htmlspecialchars($relatorio['nome_tecnico']) ?></p>
                    <p><strong>Data de Criação:</strong> <?= date('d/m/Y H:i', strtotime($relatorio['criado_em'])) ?></p>
                    <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($relatorio['descricao'])) ?></p>
                </div>
            </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning text-center mt-5">
                Nenhum relatório encontrado para o ID fornecido.
            </div>
        <?php } ?>

        <!-- Botões para gerar PDF, imprimir e enviar por e-mail -->
        <?php if (!empty($relatorio)) { ?>
            <div class="mt-5">
                <!-- Botão para imprimir apenas o relatório -->
                <button class="btn btn-info" onclick="imprimirRelatorio();">Imprimir</button>

                <!-- Botão para download do relatório em PDF -->
                <button class="btn btn-primary" onclick="window.location.href='gerar_pdf.php?id=<?= $id_relatorio ?>'">Baixar Relatório em PDF</button>


                <!-- Botão para enviar por e-mail -->
                <button id="emailBtn" class="btn btn-success">Enviar por E-mail</button>

            </div>
        <?php } ?>

        <div>
        <?php if (isset($relatorio['id_contrato'])): ?>
            <a href="../contratos/vercontrato.php?id_contrato=<?= htmlspecialchars($relatorio['id_contrato'], ENT_QUOTES, 'UTF-8') ?>" 
            class="btn btn-primary btn-mt3">Voltar</a>
        <?php else: ?>
            <span class="text-danger">ID do contrato não encontrado</span>
        <?php endif; ?>

        </div>
    </div>

    <script>
    // Quando o botão "Enviar por E-mail" for clicado
    document.getElementById('emailBtn').addEventListener('click', function() {
        const email = '<?= htmlspecialchars($relatorio['email_cliente'] ?? '') ?>'; // E-mail do cliente
        const id_relatorio = '<?= $id_relatorio ?>'; // ID do relatório

        if (!email) {
            alert('O e-mail do cliente não está disponível!');
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

    <script src="../js/scripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>