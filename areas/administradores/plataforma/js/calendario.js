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

// Gerar visualização diária
function generateDailyCalendar() {
    const calendarBody = document.getElementById('calendar-body');
    const calendarHeaders = document.getElementById('calendar-headers');
    calendarHeaders.style.display = 'none'; // Esconder os cabeçalhos da semana
    calendarBody.innerHTML = ''; // Limpa o conteúdo anterior

    const row = document.createElement('tr');
    const cell = document.createElement('td');
    cell.colSpan = 7; // Faz a célula ocupar toda a linha
    cell.innerText = `Dia ${currentDate.getDate()}`;
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
        cell.innerText = ''; // Limpa o texto da célula

        // Adiciona o número do dia no canto superior direito
        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.innerText = date.getDate();
        dayNumber.style.cssText = 'position: absolute; top: 5px; right: 10px; font-weight: bold;';
        cell.style.cssText = 'position: relative; height: 100px;';
        cell.appendChild(dayNumber);

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
        cell.innerText = ''; // Limpa o texto da célula

        // Adiciona o número do dia no canto superior direito
        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.innerText = day;
        dayNumber.style.cssText = 'position: absolute; top: 5px; right: 10px; font-weight: bold;';
        cell.style.cssText = 'position: relative; height: 100px;';
        cell.appendChild(dayNumber);

        row.appendChild(cell);

        // Se for sábado, adicionar nova linha
        if (new Date(currentDate.getFullYear(), currentDate.getMonth(), day).getDay() === 6) {
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

document.getElementById('view-daily').addEventListener('click', () => changeView('daily'));
document.getElementById('view-weekly').addEventListener('click', () => changeView('weekly'));
document.getElementById('view-monthly').addEventListener('click', () => changeView('monthly'));

// Gerar o calendário inicial na vista diária
changeView('daily'); // Quando a página carrega, exibe a vista diária
