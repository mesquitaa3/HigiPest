//barra de pesquisa
$(document).ready(function() {
    $('#search-input').on('keyup', function() {
        let busca = $(this).val();
        $.ajax({
            url: 'agendamento.php', //arquivo que estamos a usar
            type: 'GET',
            data: {
                busca: busca,
                mes: $('#month-select').val(),
                zona: $('#zone-select').val()
            },
            success: function(data) {
                //atualizar a tabela com os dados
                $('tbody').html($(data).find('tbody').html());
            }
        });
    });
});

//select para mostrar por zona
$(document).ready(function() {
    $('#month-select, #zone-select').change(function() {
        updateTable();
    });

    $('#search-input').on('input', function() {
        updateTable();
    });

    function updateTable() {
        var mes = $('#month-select').val();
        var zona = $('#zone-select').val();
        var busca = $('#search-input').val();
        window.location.href = '?mes=' + mes + '&zona=' + zona + '&busca=' + busca;
    }

    $('#saveVisit').click(function() {
        //guardar nova visita
        $('#createVisitModal').modal('hide');
    });
});