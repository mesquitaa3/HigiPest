<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /login.php");
    exit();
}



//conexao bd
require_once __DIR__ . "/../../../../bd/config.php";

//verificar conexao à bd
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
} else {
    echo "<script>console.log('Conexão com a base de dados estabelecida com sucesso.');</script>";
}

//definir o mes atual
$meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
$mes_atual = isset($_GET['mes']) ? $_GET['mes'] : date('n');
$mes_atual_nome = $meses[$mes_atual - 1];

//procurar zonas disponiveis atraves da tabela clientes
$query_zonas = "SELECT DISTINCT zona_cliente FROM clientes WHERE visivel = 1";
$result_zonas = $conn->query($query_zonas);
$zonas = $result_zonas->fetch_all(MYSQLI_ASSOC);

//filtrar pela zona
$zona_selecionada = isset($_GET['zona_cliente']) ? $_GET['zona_cliente'] : '';
$zona_filtro = $zona_selecionada ? "AND clientes.zona_cliente = '$zona_selecionada'" : '';

//procurar pelo nome de cliente ou estabelecimento
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$busca_filtro = $busca ? "AND (clientes.nome_cliente LIKE '%$busca%' OR contratos.estabelecimento_contrato LIKE '%$busca%')" : '';

$query = "
    SELECT contratos.*, clientes.nome_cliente 
    FROM contratos 
    JOIN clientes ON contratos.id_cliente = clientes.id_cliente 
    WHERE contratos.visivel = 1 
    AND contratos.meses_contrato LIKE '%$mes_atual_nome%' 
    $zona_filtro 
    $busca_filtro
";

$result = $conn->query($query);

//select para tecnicos
$query_tecnicos = "SELECT id_tecnico, nome_tecnico FROM tecnicos WHERE visivel = 1";
$result_tecnicos = $conn->query($query_tecnicos);
$tecnicos = [];
while ($tecnico = $result_tecnicos->fetch_assoc()) {
    $tecnicos[] = $tecnico;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['saveVisit'])) {
    //dados do modal para criar visita
    $id_contrato = $_POST['contractSelect'];
    $tipo_visita = $_POST['visitType'];
    $data_visita = $_POST['visitDate'];
    $hora_visita = $_POST['visitTime'];
    $id_tecnico = $_POST['technicianSelect'];
    $observacoes = isset($_POST['observations']) && !empty(trim($_POST['observations'])) ? $_POST['observations'] : '';


    //insert tabela visitas atraves do modal
    $query_insert = "
        INSERT INTO visitas (id_cliente, id_contrato, tipo_visita, data_visita, hora_visita, id_tecnico, observacoes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    //obtem o id do cliente do contrato selecionado
    $query_cliente_id = "SELECT id_cliente FROM contratos WHERE id_contrato = ?";
    $stmt_cliente_id = $conn->prepare($query_cliente_id);
    
    if ($stmt_cliente_id) {
        $stmt_cliente_id->bind_param("i", $id_contrato);
        if ($stmt_cliente_id->execute()) {
            $result_cliente_id = $stmt_cliente_id->get_result();
            if ($result_cliente_id->num_rows > 0) {
                $cliente_data = $result_cliente_id->fetch_assoc();
                $id_cliente = $cliente_data['id_cliente'];

                //insere nova visita
                $stmt_insert = $conn->prepare($query_insert);
                if ($stmt_insert) {
                    
                    if (!$stmt_insert->bind_param("iisssss", $id_cliente, $id_contrato, $tipo_visita, $data_visita, $hora_visita, $id_tecnico, $observacoes)) {
                        error_log("Erro ao bind dos parâmetros: " . htmlspecialchars($stmt_insert->error));
                    }

                    if ($stmt_insert->execute()) {
                        $_SESSION['message'] = "Visita agendada com sucesso!";
                        $_SESSION['message_type'] = "success";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        $_SESSION['message'] = "Erro ao agendar visita: " . htmlspecialchars($stmt_insert->error);
                        $_SESSION['message_type'] = "error";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }

                    //exibe mensagem de erro ou sucesso ao criar visita
                    if (isset($_SESSION['message'])) {
                        echo "<script>alert('" . $_SESSION['message'] . "');</script>";
                        unset($_SESSION['message']); //limpa a mensagem da sessão
                    }
                    
                    $stmt_insert->close();
                } else {
                    echo "<script>alert('Erro ao preparar inserção: " . htmlspecialchars($conn->error) . "');</script>";
                }
            } else {
                echo "<script>alert('Contrato não encontrado.');</script>";
            }
        } else {
            echo "<script>alert('Erro ao executar consulta: " . htmlspecialchars($stmt_cliente_id->error) . "');</script>";
        }
        
        $stmt_cliente_id->close();
    } else {
        echo "<script>alert('Erro ao preparar consulta: " . htmlspecialchars($conn->error) . "');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Serviços</title>
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.css">
    <link rel="stylesheet" href="../../../../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../../../../assets/styles/styles.css">
    
</head>
<body>
    <?php require("../../menu.php"); ?> <!-- Inclui menu - menu.php -->

    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo ($_SESSION['message_type'] === 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-5">Agendamento de Serviços - Mês de <?php echo htmlspecialchars($mes_atual_nome); ?></h2>

        <div class="row mb-4">
            <div class="col-md-3">
                <select id="month-select" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($meses as $index => $mes): ?>
                        <option value="<?php echo ($index + 1); ?>" <?php echo ($index + 1 == $mes_atual) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($mes); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="zone-select" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas as Zonas</option>
                    <?php foreach ($zonas as $zona): ?>
                        <option value="<?php echo htmlspecialchars($zona['zona_cliente']); ?>" <?php echo ($zona['zona_cliente'] == htmlspecialchars($zona_selecionada)) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($zona['zona_cliente']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createVisitModal">Criar Visita</button>
            </div>
            <div class="col-md-3">
                <input type="text" id="search-input" class="form-control" placeholder="Pesquisar cliente/estabelecimento" value="<?php echo htmlspecialchars($busca); ?>">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome Cliente</th>
                        <th>Estabelecimento</th>
                        <th>Morada</th>
                        <th>Pragas</th>
                        <th>Meses</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id_contrato']); ?></td>
                                <td><?php echo htmlspecialchars($row['nome_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($row['estabelecimento_contrato']); ?></td>
                                <td><?php echo htmlspecialchars($row['morada_contrato']); ?></td>
                                <td><?php echo htmlspecialchars($row['pragas_contrato']); ?></td>
                                <td><?php echo htmlspecialchars($row['meses_contrato']); ?></td>
                                <td><a href="agendarservico.php?id_contrato=<?php echo htmlspecialchars($row['id_contrato']); ?>" class="btn btn-primary btn-sm">Agendar Serviço</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">Nenhum serviço pendente para este mês.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!--modal para criar visita -->
<div class="modal fade" id="createVisitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Criar Nova Visita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createVisitForm" method="POST">
                    <!--select de contrato/cliente-->
                    <div class="mb-3">
                        <label for="contractSelect" class="form-label">Selecionar Contrato</label>
                        <select id="contractSelect" name="contractSelect" class="form-select" required>
                            <?php
                            //select para mostrar contratos visiveis dos clientes
                            $query_contratos = "SELECT contratos.id_contrato, clientes.nome_cliente, contratos.estabelecimento_contrato FROM contratos JOIN clientes ON contratos.id_cliente = clientes.id_cliente WHERE contratos.visivel = 1";
                            $result_contratos = $conn->query($query_contratos);
                            while ($contrato = $result_contratos->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($contrato['id_contrato']) . '">' . htmlspecialchars($contrato['estabelecimento_contrato']) . ' - ' . htmlspecialchars($contrato['nome_cliente']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!--tipo de visita-->
                    <div class="mb-3">
                        <label for="visitType" class="form-label">Tipo de Visita</label>
                        <select id="visitType" name="visitType" class="form-select" required>
                            <option value="Solicitada">Solicitada</option>
                            <option value="Cortesia">Cortesia</option>
                            <option value="Inspeção">Inspeção</option>
                            <option value="Pagamento">Pagamento</option>
                            <option value="Entrega de Material">Entrega de Material</option>
                            <option value="Extra">Serviço Extra</option>
                        </select>
                    </div>

                    <!--data-->
                    <div class="mb-3">
                        <label for="visitDate" class="form-label">Data da Visita</label>
                        <input type="date" id="visitDate" name="visitDate" class="form-control" required />
                    </div>

                    <!--hora-->
                    <div class="mb-3">
                        <label for="visitTime" class="form-label">Hora da Visita</label>
                        <input type="time" id="visitTime" name="visitTime" class="form-control" required />
                    </div>

                    <!--técnico-->
                    <div class ="mb-3">
                        <label for="technicianSelect" class="form-label">Selecionar Técnico</label>
                        <select id="technicianSelect" name="technicianSelect" class="form-select" required>
                            <?php
                            //select para mostrar tecnicos visiveis
                            $query_tecnicos = "SELECT id_tecnico, nome_tecnico FROM tecnicos WHERE visivel = 1";
                            $result_tecnicos = $conn->query($query_tecnicos);
                            while ($tecnico = $result_tecnicos->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($tecnico['id_tecnico']) . '">' . htmlspecialchars($tecnico['nome_tecnico']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!--observacoes-->
                    <div class="mb-3">
                        <label for="observations" class="form-label">Observações</label>
                        <textarea id="observations" name="observations" class="form-control"></textarea>
                    </div>

                    <!--button para criar visita-->
                    <button type="submit" name="saveVisit" class="btn btn-primary">Criar Visita</button>
                </form>
            </div>
        </div>
    </div>
</div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="../../../../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../js/calendario.js"></script>
        <script id="tecnicos-data" type="application/json"><?php echo json_encode($tecnicos); ?></script>
    </body>
</html>

