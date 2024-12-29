const currentDate = new Date(); // Data atual
let currentView = 'daily'; // Vista inicial para ser diária

// Função para formatar o mês e o ano
function formatMonthYear(date) {
    const months = [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", 
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    ];
    return `${months[date.getMonth()]} ${date.getFullYear()}`;
}

// Atualizar o título do período (mês/ano ou semana)
function updatePeriod() {
    document.getElementById('current-period').innerText = formatMonthYear(currentDate);
}

// Função para adicionar serviços agendados à célula
function addScheduledServices(cell, date) {
    const formattedDate = date.toISOString().split('T')[0];
    const servicesForDay = servicosAgendados.filter(service => 
        service.data_agendada.split(' ')[0] === formattedDate
    );

    servicesForDay.forEach(service => {
        const serviceDiv = document.createElement('div');
        serviceDiv.className = 'scheduled-service';
        serviceDiv.innerHTML = `
            <strong>Cliente: ${service.nome_cliente}</strong><br>
            Estabelecimento: ${service.estabelecimento_contrato}<br>
            Morada Estabelecimento: ${service.morada_contrato}<br>
            Hora: ${service.hora_servico}<br>
            Técnico: ${service.tecnico}<br>
            Serviço: ${service.pragas_tratadas}<br>
            <button onclick="gerarRelatorio(${service.id_agendamento})">Relatório</button>
        `;
        serviceDiv.style.cssText = 'font-size: 15px; margin-bottom: 5px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;';
        cell.appendChild(serviceDiv);
    });
}

// Gerar visualização diária
function generateDailyCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const calendarHeaders = document.getElementById('calendar-headers');
    calendarHeaders.style.display = 'none'; // Esconder os cabeçalhos da semana
    calendarBody.innerHTML = ''; // Limpa o conteúdo anterior

    const row = document.createElement('tr');
    const cell = document.createElement('td');
    cell.colSpan = 7; // Faz a célula ocupar toda a linha
    cell.innerHTML = `<div class="day-number">${currentDate.getDate()}</div>`;
    addScheduledServices(cell, currentDate);
    row.appendChild(cell);
    calendarBody.appendChild(row);

    updatePeriod();
}

// Gerar calendário semanal
function generateWeeklyCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const calendarHeaders = document.getElementById('calendar-headers');
    calendarHeaders.style.display = ''; // Mostrar cabeçalhos da semana
    calendarBody.innerHTML = ''; // Limpa o conteúdo anterior

    const firstDayOfWeek = new Date(currentDate.setDate(currentDate.getDate() - currentDate.getDay()));
    let row = document.createElement('tr');

    for (let day = 0; day < 7; day++) {
        const cell = document.createElement('td');
        const date = new Date(firstDayOfWeek);
        date.setDate(date.getDate() + day);
        
        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.innerText = date.getDate();
        cell.appendChild(dayNumber);

        addScheduledServices(cell, date);
        row.appendChild(cell);
    }
    calendarBody.appendChild(row);

    updatePeriod();
}

// Gerar calendário mensal
function generateMonthlyCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const calendarHeaders = document.getElementById('calendar-headers');
    calendarHeaders.style.display = ''; // Mostrar cabeçalhos da semana
    calendarBody.innerHTML = ''; // Limpa o conteúdo anterior

    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const totalDays = lastDayOfMonth.getDate();
    const startDay = firstDayOfMonth.getDay();

    let row = document.createElement('tr');

    // Células em branco para dias antes do primeiro dia do mês
    for (let i = 0; i < startDay; i++) {
        const cell = document.createElement('td');
        row.appendChild(cell);
    }

    for (let day = 1; day <= totalDays; day++) {
        const cell = document.createElement('td');
        const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);

        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.innerText = day;
        cell.appendChild(dayNumber);

        addScheduledServices(cell, date);
        row.appendChild(cell);

        // Se for sábado, adicionar nova linha
        if (date.getDay() === 6) {
            calendarBody.appendChild(row);
            row = document.createElement('tr');
        }
    }

    // Adicionar a última linha se necessário
    if (row.children.length > 0) {
        calendarBody.appendChild(row);
    }

    updatePeriod();
}

// Mudança de vista
function changeView(view) {
    currentView = view;
    if (view === 'daily') {
        generateDailyCalendar();
    } else if (view === 'weekly') {
        generateWeeklyCalendar();
    } else {
        generateMonthlyCalendar();
    }
}

// Eventos de clique
document.getElementById('prev-period').addEventListener('click', () => {
    if (currentView === 'daily') {
        currentDate.setDate(currentDate.getDate() - 1);
        generateDailyCalendar();
    } else if (currentView === 'weekly') {
        currentDate.setDate(currentDate.getDate() - 7);
        generateWeeklyCalendar();
    } else {
        currentDate.setMonth(currentDate.getMonth() - 1);
        generateMonthlyCalendar();
    }
});

document.getElementById('next-period').addEventListener('click', () => {
    if (currentView === 'daily') {
        currentDate.setDate(currentDate.getDate() + 1);
        generateDailyCalendar();
    } else if (currentView === 'weekly') {
        currentDate.setDate(currentDate.getDate() + 7);
        generateWeeklyCalendar();
    } else {
        currentDate.setMonth(currentDate.getMonth() + 1);
        generateMonthlyCalendar();
    }
});

document.getElementById('today-button').addEventListener('click', () => {
    currentDate.setTime(new Date().getTime()); // Reset para hoje
    if (currentView === 'daily') {
        generateDailyCalendar();
    } else if (currentView === 'weekly') {
        generateWeeklyCalendar();
    } else {
        generateMonthlyCalendar();
    }
});

document.getElementById('view-daily').addEventListener('click', () => changeView('daily'));
document.getElementById('view-weekly').addEventListener('click', () => changeView('weekly'));
document.getElementById('view-monthly').addEventListener('click', () => changeView('monthly'));

// Gerar o calendário inicial na vista diária
changeView('daily'); // Quando a página carrega, exibe a vista diária
