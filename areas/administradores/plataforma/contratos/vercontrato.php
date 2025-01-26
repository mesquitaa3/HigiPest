<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}

require_once __DIR__ . "/../../../../bd/config.php";

// Verifica se o ID do contrato foi fornecido e é válido
if (empty($_GET['id_contrato']) || !is_numeric($_GET['id_contrato'])) {
    echo "ID de contrato inválido.";
    exit();
}

$id_contrato = intval($_GET['id_contrato']); // Pega o valor correto do parâmetro

//detalhes do contrato
$query = "SELECT * FROM contratos WHERE id_contrato = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_contrato);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Contrato não encontrado.";
    exit();
}

$contrato = $result->fetch_assoc();

// Consulta para buscar os relatórios associados ao contrato
$query_relatorios = "
    SELECT r.id_relatorio, r.descricao, r.criado_em, t.nome_tecnico
    FROM relatorios r
    LEFT JOIN tecnicos t ON r.id_tecnico = t.id_tecnico
    WHERE r.id_estabelecimento = ?
";
$stmt_relatorios = $conn->prepare($query_relatorios);
$stmt_relatorios->bind_param("i", $id_contrato);
$stmt_relatorios->execute();
$result_relatorios = $stmt_relatorios->get_result();

// Verifica se existem relatórios
$relatorios = [];
if ($result_relatorios->num_rows > 0) {
    while ($row = $result_relatorios->fetch_assoc()) {
        $relatorios[] = $row;
    }
}

// Fechar a conexão e liberar recursos após todas as consultas
$stmt_relatorios->close();
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Contrato (Administração)</title>
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
</head>
<body>
<?php require("../../menu.php"); ?> <!-- Inclui menu - menu.php -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Detalhes do Contrato</h1>
        
        <div class="card">
            <div class="card-body">
                <div class="menu-container">
                    <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-target="informacao">Informação</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-target="relatorios">Relatórios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-target="plantas">Plantas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-target="documentos">Documentos</a>
                        </li>
                    </ul>
                </div>
                
                <div id="informacao" class="conteudo-secao">
                    <h2>Informação do Contrato</h2>
                    <table class="table table-striped">
                        <tr>
                            <th>Estabelecimento:</th>
                            <td><?php echo htmlspecialchars($contrato['estabelecimento_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Morada:</th>
                            <td><?php echo htmlspecialchars($contrato['morada_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Pragas:</th>
                            <td><?php echo htmlspecialchars($contrato['pragas_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Data de Início:</th>
                            <td><?php echo htmlspecialchars($contrato['data_inicio_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Tipo de Contrato:</th>
                            <td><?php echo htmlspecialchars($contrato['tipo_contrato']); ?></td>
                        </tr>
                        <tr>
                            <th>Valor do Contrato:</th>
                            <td><?php echo htmlspecialchars(number_format($contrato['valor_contrato'], 2)); ?> €</td>
                        </tr>
                    </table>
                </div>

                <div id="relatorios" class="conteudo-secao" style="display: none;">
                    <h2>Relatórios</h2>
                    <?php if (!empty($relatorios)) { ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Técnico</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($relatorios as $relatorio) { ?>
                                    <tr>
                                        <td><?= $relatorio['id_relatorio'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($relatorio['criado_em'])) ?></td>
                                        <td><?= htmlspecialchars($relatorio['nome_tecnico'] ?? 'N/A') ?></td>
                                        <td>
                                            <a href="/web/areas/administradores/plataforma/relatorio/ver_relatorio.php?id=<?= $relatorio['id_relatorio'] ?>" class="btn btn-primary btn-sm">Ver</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>Não há relatórios associados a este contrato.</p>
                    <?php } ?>
                </div>

                <div id="plantas" class="conteudo-secao" style="display: none;">
                    <h2>Plantas</h2>
                    <!-- Adicione aqui o conteúdo da seção de plantas -->
                </div>

                <div id="documentos" class="conteudo-secao" style="display: none;">
                    <h2>Documentos</h2>
                    <!-- Adicione aqui o conteúdo da seção de documentos -->
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="./tabelacontratos.php" class="btn btn-secondary">Voltar</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Seção padrão que deve estar visível ao carregar a página
    const defaultSection = 'informacao';

    // Esconde todas as seções inicialmente
    document.querySelectorAll('.conteudo-secao').forEach(function(secao) {
        secao.style.display = 'none';
    });

    // Mostra apenas a seção padrão
    document.getElementById(defaultSection).style.display = 'block';

    // Marca a aba da seção padrão como ativa
    document.querySelector(`.nav-link[data-target="${defaultSection}"]`).classList.add('active');

    // Adiciona funcionalidade de troca de seções ao clicar no menu
    document.querySelectorAll('.nav-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Oculta todas as seções
            document.querySelectorAll('.conteudo-secao').forEach(function(secao) {
                secao.style.display = 'none';
            });

            // Remove a classe "active" de todas as abas
            document.querySelectorAll('.nav-link').forEach(function(nav) {
                nav.classList.remove('active');
            });

            // Mostra a seção correspondente ao botão clicado
            const target = link.getAttribute('data-target');
            document.getElementById(target).style.display = 'block';

            // Adiciona a classe "active" na aba clicada
            link.classList.add('active');
        });
    });
});
</script>


</body>
</html>
