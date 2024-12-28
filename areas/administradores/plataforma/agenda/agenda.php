<?php
session_start();
if ($_SESSION['cargo'] != 'administrador') {
    header("Location: /web/login.php");  // Se não for administrador, volta para o login
    exit();
}

// Incluir arquivo de conexão à base de dados
include ($_SERVER['DOCUMENT_ROOT']."/web/bd/config.php");

// Definir o mês atual
$mes_atual = date('F');  // Ex: Janeiro, Fevereiro, etc.
$mes_atual_num = date('m');  // Número do mês (01, 02, ..., 12)
$ano_atual = date('Y');  // Ano atual

$query = "
    SELECT agendamentos.*, contratos.*, clientes.nome_cliente 
    FROM agendamentos
    JOIN contratos ON agendamentos.id_contrato = contratos.id_contrato
    JOIN clientes ON contratos.id_cliente = clientes.id_cliente
    WHERE MONTH(agendamentos.data_agendada) = $mes_atual_num AND YEAR(agendamentos.data_agendada) = $ano_atual
";

$result = $conn->query($query);
$servicosAgendados = $result->fetch_all(MYSQLI_ASSOC);

// Passando os serviços agendados para o JavaScript
?>

<script>
    const servicosAgendados = <?php echo json_encode($servicosAgendados); ?>;
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
        /* Estilos adicionais para o calendário */
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

        <!-- Controles de navegação e visualização -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button id="prev-period" class="btn btn-primary">Anterior</button>
            <h3 id="current-period" class="text-center"><?php echo $mes_atual; ?></h3>
            <button id="next-period" class="btn btn-primary">Próximo</button>
        </div>

        <!-- Botões de alternância de visualização -->
        <div class="d-flex justify-content-center mb-4">
            <button id="view-daily" class="btn btn-outline-secondary me-2">Diário</button>
            <button id="view-weekly" class="btn btn-outline-secondary me-2">Semanal</button>
            <button id="view-monthly" class="btn btn-outline-secondary">Mensal</button>
        </div>

        <!-- Tabela do calendário -->
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
                    <!-- O conteúdo do calendário será gerado dinamicamente pelo JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Adicionando os dados dos serviços agendados ao JavaScript -->
        <script>
            const servicosAgendados = <?php echo json_encode($result->fetch_all(MYSQLI_ASSOC)); ?>;
        </script>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/web/areas/administradores/plataforma/js/calendario.js"></script>  
</body>
</html>
