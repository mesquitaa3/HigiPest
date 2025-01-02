const currentDate = new Date();
let currentView = 'daily';

function formatMonthYear(date) {
    const months = [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", 
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    ];
    return `${months[date.getMonth()]} ${date.getFullYear()}`;
}

function updatePeriod() {
    document.getElementById('current-period').innerText = formatMonthYear(currentDate);
}

function addScheduledServices(cell, date) {
    const formattedDate = date.toISOString().split('T')[0];
    
    //filtra os serviços para o dia específico
    const servicesForDay = servicosAgendados.filter(service => 
        service.data_agendada === formattedDate
    );

    //adiciona cada serviço à célula do dia
    servicesForDay.forEach(service => {
        const serviceDiv = document.createElement('div');
        serviceDiv.className = 'scheduled-service';
        serviceDiv.innerHTML = `
            <strong>${service.nome_cliente}</strong><br>
            ${service.nome_estabelecimento}<br>
            ${service.morada_estabelecimento}<br>
            Hora: ${service.hora_servico}
        `;
        cell.appendChild(serviceDiv);
    });
}

function generateDailyCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const calendarHeaders = document.getElementById('calendar-headers');
    calendarHeaders.style.display = 'none';
    calendarBody.innerHTML = '';

    const row = document.createElement('tr');
    const cell = document.createElement('td');
    cell.colSpan = 7;
    cell.innerHTML = `<div class="day-number">${currentDate.getDate()}</div>`;
    addScheduledServices(cell, currentDate);
    row.appendChild(cell);
    calendarBody.appendChild(row);

    updatePeriod();
}

function generateWeeklyCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const calendarHeaders = document.getElementById('calendar-headers');
    calendarHeaders.style.display = '';
    calendarBody.innerHTML = '';

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

function generateMonthlyCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const calendarHeaders = document.getElementById('calendar-headers');
    calendarHeaders.style.display = '';
    calendarBody.innerHTML = '';

    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const totalDays = lastDayOfMonth.getDate();
    const startDay = firstDayOfMonth.getDay();

    let row = document.createElement('tr');

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

        if (date.getDay() === 6) {
            calendarBody.appendChild(row);
            row = document.createElement('tr');
        }
    }

    if (row.children.length > 0) {
        calendarBody.appendChild(row);
    }

    updatePeriod();
}

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

document.getElementById('prev-period').addEventListener('click', () => {
    if (currentView === 'daily') {
        currentDate.setDate(currentDate.getDate() - 1);
    } else if (currentView === 'weekly') {
        currentDate.setDate(currentDate.getDate() - 7);
    } else {
        currentDate.setMonth(currentDate.getMonth() - 1);
    }
    changeView(currentView);
});

document.getElementById('next-period').addEventListener('click', () => {
    if (currentView === 'daily') {
        currentDate.setDate(currentDate.getDate() + 1);
    } else if (currentView === 'weekly') {
        currentDate.setDate(currentDate.getDate() + 7);
    } else {
        currentDate.setMonth(currentDate.getMonth() + 1);
    }
    changeView(currentView);
});

document.getElementById('view-daily').addEventListener('click', () => changeView('daily'));
document.getElementById('view-weekly').addEventListener('click', () => changeView('weekly'));
document.getElementById('view-monthly').addEventListener('click', () => changeView('monthly'));

//calendario diario
changeView('daily');


function alterarData(dias) {
    var dataAtual = new Date(document.getElementById('data-selecionada').innerText);
    dataAtual.setDate(dataAtual.getDate() + dias);
    var novaData = dataAtual.toISOString().split('T')[0]; 
    window.location.href = window.location.pathname + '?data=' + novaData;
}