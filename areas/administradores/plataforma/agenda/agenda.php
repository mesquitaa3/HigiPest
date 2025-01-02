<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  //se não for administrador, volta para o login
    exit();
}

//conexao bd
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

//definir mes
$mes_atual = date('F');  //janeiro, fevereiro, etc.
$mes_atual_num = date('m');  //número do mês (01, 02, ..., 12)
$ano_atual = date('Y');  //ano atual

$query = "SELECT agendamentos.*, contratos.*, clientes.nome_cliente, tecnicos.nome_tecnico
    FROM agendamentos
    JOIN contratos ON agendamentos.id_contrato = contratos.id_contrato
    JOIN clientes ON contratos.id_cliente = clientes.id_cliente
    LEFT JOIN tecnicos ON agendamentos.tecnico = tecnicos.id_tecnico
    WHERE MONTH(agendamentos.data_agendada) = $mes_atual_num AND YEAR(agendamentos.data_agendada) = $ano_atual
";

$query_visita = "
    SELECT visitas.*, contratos.*, clientes.nome_cliente, tecnicos.nome_tecnico
    FROM visitas
    JOIN contratos ON visitas.id_contrato = contratos.id_contrato
    JOIN clientes ON contratos.id_cliente = clientes.id_cliente
    LEFT JOIN tecnicos ON visitas.id_tecnico = tecnicos.id_tecnico
    WHERE MONTH(visitas.data_visita) = $mes_atual_num AND YEAR(visitas.data_visita) = $ano_atual
";

$resultvisita = $conn->query($query_visita);

if ($resultvisita) {
    $visitasAgendadas = $resultvisita->fetch_all(MYSQLI_ASSOC);
} else {
    $visitasAgendadas = [];
}

echo '<script>';
echo 'const visitasAgendadas = ' . json_encode($visitasAgendadas) . ';';
echo '</script>';

$result = $conn->query($query);
$servicosAgendados = $result->fetch_all(MYSQLI_ASSOC);


//passar os serviços agendados para o js
?>

<script>
    const servicosAgendados = <?php echo json_encode($servicosAgendados); ?>;
</script>

<script>
    const visitasAgendadas = <?php echo json_encode($visitasAgendadas); ?>;
</script>





<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Serviços Agendados</title>

    <!-- Bootstrap e CSS -->
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/styles.css">
    <style>
        /*calendário*/
        .day-number {
            position: absolute;
            top: 5px;
            right: 10px;
            font-weight: bold;
        }
        td {
            position: relative;
            height: 100px;
            text-align: left;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/web/areas/administradores/menu.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center mb-5">Agenda de Serviços</h2>

        <!--botoes para avançar e recuar-->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button id="prev-period" class="btn btn-primary">Anterior</button>
            <h3 id="current-period" class="text-center"><?php echo $mes_atual; ?></h3>
            <button id="next-period" class="btn btn-primary">Próximo</button>
        </div>

        <!--botoes para alterar diario, semanal, mensal-->
        <div class="d-flex justify-content-center mb-4">
            <button id="today-button" class="btn btn-success me-2">Hoje</button>
            <button id="view-daily" class="btn btn-outline-secondary me-2">Diário</button>
            <button id="view-weekly" class="btn btn-outline-secondary me-2">Semanal</button>
            <button id="view-monthly" class="btn btn-outline-secondary">Mensal</button>
        </div>

        <!--tabela para o calendario-->
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead id="calendar-headers">
                    <tr>
                        <th>Dom</th>
                        <th>Seg</th>
                        <th>Ter</th>
                        <th>Qua</th>
                        <th>Qui</th>
                        <th>Sex</th>
                        <th>Sáb</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    <!--conteudo do calendario é gerado pelo js-->
                </tbody>
            </table>
        </div>

        <!--adicionar os dados dos serviços agendados ao js-->
        <script>
            const servicosAgendados = <?php echo json_encode($result->fetch_all(MYSQLI_ASSOC)); ?>;
        </script>
        <script>
            const visitasAgendadas = <?php echo json_encode($resultvisita->fetch_all(MYSQLI_ASSOC)); ?>;
        </script>
        
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/web/areas/administradores/plataforma/js/calendario.js"></script>  
</body>
</html>
