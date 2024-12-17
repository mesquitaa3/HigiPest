<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>agenda</title>

    <link rel="stylesheet" href="/web/assets/styles/bootstrap.css">
    <link rel="stylesheet" href="/web/assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/web/assets/styles/stylesagenda.css">
</head>

<body id="body-menu"> <!-- Adicionando o ID aqui -->

<?php require('../menu.php'); ?> <!-- Inclui menu - menu.php -->


    <h1>Agenda</h1>

<div class="calendar-header">
    <button id="prev-period">&lt; Anterior</button>
    <span id="current-period">Setembro 2024</span>
    <button id="next-period">Próximo &gt;</button>
</div>

<div class="calendar-dates" id="calendar-dates"></div>

<div class="view-options">
    <button id="view-daily">Diária</button>
    <button id="view-weekly">Semanal</button>
    <button id="view-monthly">Mensal</button>
</div>

<table class="calendar" id="calendar">
    <thead>
        <tr id="calendar-headers">
            <th>Domingo</th>
            <th>Segunda</th>
            <th>Terça</th>
            <th>Quarta</th>
            <th>Quinta</th>
            <th>Sexta</th>
            <th>Sábado</th>
        </tr>
    </thead>
    <tbody id="calendar-body">
    </tbody>
</table>


    <!-- jQuery (opcional, mas recomendado se você usar outros scripts que dependem dele) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle com Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Incluir o ficheiro de JavaScript -->
<script src="/projeto/administracao/js/calendario.js"></script>
    
</body>
</html>