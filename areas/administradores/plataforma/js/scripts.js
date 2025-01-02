function limparPesquisa() {
    const searchInput = document.querySelector('input[name="search"]');
    searchInput.value = '';
    window.location.href = window.location.pathname;
}

window.onload = function() {
    document.getElementById("search_bar").value = "";
};

function preencherSelectTecnicos() {
    const technicianSelect = document.getElementById('technicianSelect');
    const tecnicosDataElement = document.getElementById('tecnicos-data');
    if (tecnicosDataElement && technicianSelect) {
        const tecnicos = JSON.parse(tecnicosDataElement.textContent);
        
        tecnicos.forEach(function(tecnico) {
            const option = document.createElement('option');
            option.value = tecnico.id_tecnico;
            option.textContent = tecnico.nome_tecnico;
            technicianSelect.appendChild(option);
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    preencherSelectTecnicos();

    const saveVisitButton = document.getElementById('saveVisit');
    if (saveVisitButton) {
        saveVisitButton.addEventListener('click', function() {
            const form = document.getElementById('createVisitForm');
            if (form) {
                form.submit();
            }
        });
    }

    const createVisitButton = document.querySelector('[data-bs-target="#createVisitModal"]');
    if (createVisitButton) {
        createVisitButton.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('createVisitModal'));
            modal.show();
        });
    }
});

document.getElementById('saveVisit').addEventListener('click', function() {
    console.log('Botão Guardar');
    document.getElementById('createVisitForm').submit();
});