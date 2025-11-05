// Variables globales
let fechaActual = new Date();
let eventosHome = [];

// Inicializar calendario
function inicializarCalendarioHome(eventosData) {
    eventosHome = eventosData || [];
    actualizarVistaResponsive();
    renderizarCalendarioMes();
    renderizarListaEventos();
}

// Navegación del mes
function navegarMes(direccion) {
    if (direccion === 'anterior') {
        fechaActual.setMonth(fechaActual.getMonth() - 1);
    } else {
        fechaActual.setMonth(fechaActual.getMonth() + 1);
    }
    renderizarCalendarioMes();
    actualizarTituloFecha();
}

function irHoy() {
    fechaActual = new Date();
    renderizarCalendarioMes();
    actualizarTituloFecha();
}

function actualizarTituloFecha() {
    const elementoFecha = document.getElementById('fechaActual');
    if (elementoFecha) {
        elementoFecha.textContent = fechaActual.toLocaleDateString('es-ES', { 
            month: 'long', 
            year: 'numeric' 
        });
    }
}

// Renderizar calendario mensual
function renderizarCalendarioMes() {
    const grid = document.getElementById('calendarGrid');
    if (!grid) return;
    
    grid.innerHTML = '';
    
    const año = fechaActual.getFullYear();
    const mes = fechaActual.getMonth();
    
    // Primer día del mes
    const primerDia = new Date(año, mes, 1);
    // Último día del mes
    const ultimoDia = new Date(año, mes + 1, 0);
    // Día de la semana del primer día ajustado para lunes
    let primerDiaSemana = primerDia.getDay() - 1;
    if (primerDiaSemana < 0) primerDiaSemana = 6;
    
    // Días del mes anterior
    const mesAnterior = new Date(año, mes, 0);
    const diasMesAnterior = mesAnterior.getDate();
    
    // Total de celdas necesarias (siempre 42 para 6 semanas)
    const totalCeldas = 42;
    
    for (let i = 0; i < totalCeldas; i++) {
        const diaCelda = document.createElement('div');
        diaCelda.className = 'calendar-day';
        
        let fechaCelda;
        let diaNumero;
        
        if (i < primerDiaSemana) {
            // Días del mes anterior
            diaNumero = diasMesAnterior - primerDiaSemana + i + 1;
            fechaCelda = new Date(año, mes - 1, diaNumero);
            diaCelda.classList.add('other-month');
        } else if (i >= primerDiaSemana && i < primerDiaSemana + ultimoDia.getDate()) {
            // Días del mes actual
            diaNumero = i - primerDiaSemana + 1;
            fechaCelda = new Date(año, mes, diaNumero);
            
            // Marcar hoy
            const hoy = new Date();
            if (fechaCelda.toDateString() === hoy.toDateString()) {
                diaCelda.classList.add('today');
            }
        } else {
            // Días del mes siguiente
            diaNumero = i - primerDiaSemana - ultimoDia.getDate() + 1;
            fechaCelda = new Date(año, mes + 1, diaNumero);
            diaCelda.classList.add('other-month');
        }
        
        // Número del día
        const numeroElemento = document.createElement('div');
        numeroElemento.className = 'day-number';
        numeroElemento.textContent = diaNumero;
        diaCelda.appendChild(numeroElemento);
        
        // Eventos del día
        const eventosDia = eventosHome.filter(evento => {
            const fechaEvento = new Date(evento.fecha);
            return fechaEvento.toDateString() === fechaCelda.toDateString();
        });
        
        eventosDia.forEach(evento => {
            const eventoElemento = document.createElement('div');
            eventoElemento.className = 'event-item';
            eventoElemento.textContent = evento.nombre;
            eventoElemento.title = `${evento.nombre}\n${evento.descripcion || 'Sin descripción'}\n${evento.horaIni} - ${evento.horaFin}`;
            diaCelda.appendChild(eventoElemento);
        });
        
        grid.appendChild(diaCelda);
    }
    
    actualizarTituloFecha();
}

// Renderizar lista de eventos
function renderizarListaEventos() {
    const lista = document.getElementById('listaEventosHome');
    if (!lista) return;
    
    lista.innerHTML = '';
    
    const eventosProximos = eventosHome
        .filter(evento => new Date(evento.fecha) >= new Date())
        .sort((a, b) => new Date(a.fecha) - new Date(b.fecha))
        .slice(0, 5); // Mostrar solo los próximos 5 eventos
    
    if (eventosProximos.length === 0) {
        lista.innerHTML = '<div class="event-list-item"><div class="event-info">No hay eventos próximos</div></div>';
        return;
    }
    
    eventosProximos.forEach(evento => {
        const eventoElemento = document.createElement('div');
        eventoElemento.className = 'event-list-item';
        
        const fechaEvento = new Date(evento.fecha);
        const fechaFormateada = fechaEvento.toLocaleDateString('es-ES', {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
        
        eventoElemento.innerHTML = `
            <div class="event-info">
                <h4>${evento.nombre}</h4>
                <div class="event-meta">
                    ${fechaFormateada} | ${evento.horaIni} - ${evento.horaFin}
                </div>
                <p>${evento.descripcion || 'Sin descripción'}</p>
            </div>
        `;
        
        lista.appendChild(eventoElemento);
    });
}

// Responsive: cambiar entre calendario y lista
function actualizarVistaResponsive() {
    const desktopView = document.getElementById('calendarViewDesktop');
    const mobileView = document.getElementById('calendarViewMobile');
    
    if (!desktopView || !mobileView) return;
    
    if (window.innerWidth <= 768) {
        // Móvil: mostrar lista
        desktopView.style.display = 'none';
        mobileView.style.display = 'block';
    } else {
        // Desktop: mostrar calendario
        desktopView.style.display = 'block';
        mobileView.style.display = 'none';
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Los eventos se pasarán desde el PHP
    if (typeof window.eventosHomeData !== 'undefined') {
        inicializarCalendarioHome(window.eventosHomeData);
    }
});

// Actualizar vista cuando cambie el tamaño de la ventana
window.addEventListener('resize', actualizarVistaResponsive);

// Hacer funciones disponibles globalmente
window.navegarMes = navegarMes;
window.irHoy = irHoy;
window.inicializarCalendarioHome = inicializarCalendarioHome;