const currentDate = new Date(); //data atual
let currentView = 'daily'; //vista para ser diaria

//funcao para formartar o mes e ano
function formatMonthYear(date) {
    const months = [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", 
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    ];
    return `${months[date.getMonth()]} ${date.getFullYear()}`;
}

//atualizar o título do período (mês/ano ou semana)
function updatePeriod() {
    document.getElementById('current-period').innerText = formatMonthYear(currentDate);
}

//funcao para adicionar servicos à celula da agenda
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
            Técnico: ${service.nome_tecnico}<br>
            Serviço: ${service.pragas_tratadas}<br>
            <button onclick="gerarRelatorio(${service.id_agendamento})">Relatório</button>
        `;
        serviceDiv.style.cssText = 'font-size: 15px; margin-bottom: 5px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;';
        cell.appendChild(serviceDiv);
    });
}

//funcao para adicionar visitas à celula da agenda
function addScheduledVisita(cell, date) {
    const formattedDate = date.toISOString().split('T')[0]; // 'YYYY-MM-DD'

    //filtrar visitas para a data atual
    const visitaForDay = visitasAgendadas.filter(visita => 
        visita.data_visita.startsWith(formattedDate)
    );

    //adicionar visitas
    visitaForDay.forEach(visita => {
        const visitaDiv = document.createElement('div');
        visitaDiv.className = 'scheduled-visita';
        visitaDiv.innerHTML = ` 
            <strong>Cliente:</strong> ${visita.nome_cliente}<br>
            <strong>Estabelecimento:</strong> ${visita.estabelecimento_contrato}<br>
            <strong>Morada:</strong> ${visita.morada_contrato}<br>
            <strong>Hora:</strong> ${visita.hora_visita}<br>
            <strong>Técnico:</strong> ${visita.nome_tecnico}<br>
            <strong>Serviço:</strong> ${visita.tipo_visita}<br>
            <strong>Observações:</strong> ${visita.observacoes}<br>
            <button onclick="gerarRelatorio(${visita.id_visita})">Relatório</button>
        `;
        visitaDiv.style.cssText = 'font-size: 15px; margin-bottom: 5px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;';
        cell.appendChild(visitaDiv);
    });
}

//combinar serviços e visitas, e ordenação por hora
const combinedServices = [
    ...servicosAgendados.map(service => ({
        ...service,
        tipo: 'servico', 
        data: service.data_agendada,
        hora: `${service.hora_servico}`
    })),
    ...visitasAgendadas.map(visita => ({
        ...visita,
        tipo: 'visita',
        data: visita.data_visita,
        hora: `${visita.hora_visita}`
    }))
];

//ordenar por hora
combinedServices.sort((a, b) => new Date(a.hora) - new Date(b.hora));

//visualizar diariamente
function generateDailyCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const calendarHeaders = document.getElementById('calendar-headers');
    calendarHeaders.style.display = 'none';
    calendarBody.innerHTML = '';

    const row = document.createElement('tr');
    const cell = document.createElement('td');
    cell.colSpan = 7;
    cell.innerHTML = `<div class="day-number">${currentDate.getDate()}</div>`;

    //filtrar e adicionar as visitas e serviços para o dia atual
    const itemsForDay = combinedServices.filter(item => 
        item.data.startsWith(currentDate.toISOString().split('T')[0])
    );

    itemsForDay.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = item.tipo === 'servico' ? 'scheduled-service' : 'scheduled-visita';
        itemDiv.innerHTML = `
            <strong>Cliente:</strong> ${item.nome_cliente}<br>
            <strong>Estabelecimento:</strong> ${item.estabelecimento_contrato}<br>
            <strong>Hora:</strong> ${item.hora}<br>
            <strong>Técnico:</strong> ${item.nome_tecnico}<br>
            <strong>Observações:</strong>${item.observacoes}<br>
            <button onclick="gerarRelatorio(${item.id_agendamento || item.id_visita})">Relatório</button>
        `;
        itemDiv.style.cssText = 'font-size: 15px; margin-bottom: 5px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;';
        cell.appendChild(itemDiv);
    });

    row.appendChild(cell);
    calendarBody.appendChild(row);

    updatePeriod();
}

//calendario semanal
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

        //filtrar e adicionar as visitas e serviços para o dia atual
        const itemsForDay = combinedServices.filter(item => 
            item.data.startsWith(date.toISOString().split('T')[0])
        );

        itemsForDay.forEach(item => {
            const itemDiv = document.createElement('div');
            itemDiv.className = item.tipo === 'servico' ? 'scheduled-service' : 'scheduled-visita';
            itemDiv.innerHTML = `
                <strong>Cliente:</strong> ${item.nome_cliente}<br>
                <strong>Estabelecimento:</strong> ${item.estabelecimento_contrato}<br>
                <strong>Hora:</strong> ${item.hora}<br>
                <strong>Técnico:</strong> ${item.nome_tecnico}<br>
                <button onclick="gerarRelatorio(${item.id_agendamento || item.id_visita})">Relatório</button>
            `;
            itemDiv.style.cssText = 'font-size: 15px; margin-bottom: 5px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;';
            cell.appendChild(itemDiv);
        });

        row.appendChild(cell);
    }

    calendarBody.appendChild(row);

    updatePeriod();
}

//calendaroi mensal
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
    
        //filtrar e adicionar as visitas e serviços para o dia atual
        const itemsForDay = combinedServices.filter(item => 
            item.data.startsWith(date.toISOString().split('T')[0])
        );

        itemsForDay.forEach(item => {
            const itemDiv = document.createElement('div');
            itemDiv.className = item.tipo === 'servico' ? 'scheduled-service' : 'scheduled-visita';
            itemDiv.innerHTML = `
                <strong>Cliente:</strong> ${item.nome_cliente}<br>
                <strong>Estabelecimento:</strong> ${item.estabelecimento_contrato}<br>
                <strong>Hora:</strong> ${item.hora}<br>
                <strong>Técnico:</strong> ${item.nome_tecnico}<br>
                <button onclick="gerarRelatorio(${item.id_agendamento || item.id_visita})">Relatório</button>
            `;
            itemDiv.style.cssText = 'font-size: 15px; margin-bottom: 5px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;';
            cell.appendChild(itemDiv);
        });

        row.appendChild(cell);

        if (date.getDay() === 6) {
            calendarBody.appendChild(row);
            row = document.createElement('tr');
        }
    }

    updatePeriod();
}

//muudar de diario, para semanal ou para mensal
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
    currentDate.setTime(new Date().getTime());
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

//calendário inicial na vista diária
changeView('daily'); //página carrega, vista diária
