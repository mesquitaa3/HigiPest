<?php
session_start();
if ($_SESSION['cargo'] != 'cliente') {
    header("Location: /web/login.php");
    exit();
}

//conexao bd
require_once __DIR__ . "/../../bd/config.php";

$id_contrato = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_contrato === 0) {
    echo "ID de contrato inválido.";
    exit();
}

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Contrato</title>
    <link rel="stylesheet" href="../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styles/styles.css">
    <link rel="stylesheet" href="/web/areas/clientes/assets/css/styles.css">
    
</head>
<body>
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
                    <!-- Adicione aqui o conteúdo da seção de relatórios -->
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

    <button><a href="/web/areas/clientes/contratos.php">voltar</button>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/web/areas/clientes/assets/js/scripts.js"></script>
    
</body>
</html>
