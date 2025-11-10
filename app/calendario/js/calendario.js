let fechaActual = new Date();
let vistaActual = 'mes';
let eventos = [];
let eventoEditando = null;

function inicializarCalendario() {
    cargarEventos();
    cambiarVista('mes');
    
    // Configurar event listener único para el formulario
    const formEvento = document.getElementById('formEvento');
    formEvento.removeEventListener('submit', guardarEvento);
    formEvento.addEventListener('submit', guardarEvento);
}

function cambiarVista(vista) {
    vistaActual = vista;
    
    // Actualizar botones activos
    document.querySelectorAll('.view-controls .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById(`btnVista${vista.charAt(0).toUpperCase() + vista.slice(1)}`).classList.add('active');
    
    // Ocultar todas las vistas
    document.querySelectorAll('.calendar-view').forEach(view => {
        view.style.display = 'none';
    });
    
    // Mostrar vista seleccionada
    document.getElementById(`vista${vista.charAt(0).toUpperCase() + vista.slice(1)}`).style.display = 'block';
    
    // Cargar eventos específicos para esta vista
    cargarEventos();
}

function navegarFecha(direccion) {
    switch(vistaActual) {
        case 'mes':
            if (direccion === 'anterior') {
                fechaActual.setMonth(fechaActual.getMonth() - 1);
            } else {
                fechaActual.setMonth(fechaActual.getMonth() + 1);
            }
            break;
        case 'semana':
            // CORRECCIÓN: Navegar por semanas completas
            if (direccion === 'anterior') {
                fechaActual.setDate(fechaActual.getDate() - 7);
            } else {
                fechaActual.setDate(fechaActual.getDate() + 7);
            }
            break;
        case 'lista':
            if (direccion === 'anterior') {
                fechaActual.setDate(fechaActual.getDate() - 7);
            } else {
                fechaActual.setDate(fechaActual.getDate() + 7);
            }
            break;
    }
    
    // Cargar eventos al navegar
    cargarEventos();
}

function irHoy() {
    fechaActual = new Date();
    cargarEventos();
}

function actualizarVista() {
    switch(vistaActual) {
        case 'mes':
            renderizarVistaMes();
            break;
        case 'semana':
            renderizarVistaSemana();
            break;
        case 'lista':
            renderizarVistaLista();
            break;
    }
    actualizarTituloFecha();
}

function actualizarTituloFecha() {
    const elementoFecha = document.getElementById('fechaActual');
    
    switch(vistaActual) {
        case 'mes':
            elementoFecha.textContent = fechaActual.toLocaleDateString('es-ES', { 
                month: 'long', 
                year: 'numeric' 
            });
            break;
        case 'semana':
            // CORRECCIÓN: Usar el mismo cálculo que en renderizarVistaSemana
            const inicioSemana = new Date(fechaActual);
            let diaSemana = fechaActual.getDay();
            const diferenciaLunes = diaSemana === 0 ? -6 : 1 - diaSemana;
            inicioSemana.setDate(fechaActual.getDate() + diferenciaLunes);
            
            const finSemana = new Date(inicioSemana);
            finSemana.setDate(inicioSemana.getDate() + 6);
            
            elementoFecha.textContent = `${inicioSemana.toLocaleDateString('es-ES')} - ${finSemana.toLocaleDateString('es-ES')}`;
            break;
        case 'lista':
            elementoFecha.textContent = 'Próximos Eventos';
            break;
    }
}

function renderizarVistaMes() {
    const grid = document.getElementById('calendarGrid');
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
        const eventosDia = eventos.filter(evento => {
            const fechaEvento = parsearFecha(evento.fecha);
            return fechaEvento.toDateString() === fechaCelda.toDateString();
        });
        
        eventosDia.forEach(evento => {
            const eventoElemento = document.createElement('div');
            eventoElemento.className = 'event-item';
            
            // Formatear la hora a HH:mm
            const horaFormateada = formatearHora(evento.horaIni);
            eventoElemento.textContent = `${horaFormateada} - ${evento.nombre}`;
            eventoElemento.title = evento.descripcion;
            eventoElemento.onclick = (e) => {
                e.stopPropagation();
                mostrarDetallesEvento(evento);
            };
            diaCelda.appendChild(eventoElemento);
        });
        
        // Hacer clic en el día para agregar evento
        diaCelda.onclick = (e) => {
            if (e.target === diaCelda || e.target.className === 'day-number') {
                mostrarModalAgregarEvento(fechaCelda);
            }
        };
        
        grid.appendChild(diaCelda);
    }
}

function formatearHora(horaStr) {
    if (!horaStr) return '00:00';
    
    // Si ya está en formato HH:mm, devolverlo directamente
    if (horaStr.match(/^\d{1,2}:\d{2}$/)) {
        const [horas, minutos] = horaStr.split(':');
        return `${horas.padStart(2, '0')}:${minutos.padStart(2, '0')}`;
    }
    
    // Si es un objeto Date
    if (horaStr instanceof Date) {
        return horaStr.toTimeString().slice(0, 5);
    }
    
    // Para otros formatos, intentar parsear
    try {
        const fecha = new Date(`2000-01-01T${horaStr}`);
        if (!isNaN(fecha.getTime())) {
            return fecha.toTimeString().slice(0, 5);
        }
    } catch (e) {
        console.error('Error formateando hora:', e);
    }
    
    return horaStr; // Devolver original si no se puede formatear
}

function renderizarVistaSemana() {
    const weekDaysHeader = document.getElementById('weekDaysHeader');
    const timeColumn = document.getElementById('timeColumn');
    const daysGrid = document.getElementById('daysGrid');
    
    // Limpiar contenido
    weekDaysHeader.innerHTML = '';
    timeColumn.innerHTML = '';
    daysGrid.innerHTML = '';
    
    // CORRECCIÓN: Calcular correctamente el inicio de la semana (lunes)
    const inicioSemana = new Date(fechaActual);
    // Ajustar para que la semana empiece en lunes (0=domingo, 1=lunes, etc.)
    let diaSemana = fechaActual.getDay();
    // Si es domingo (0), retroceder 6 días para llegar al lunes anterior
    // Si es otro día, retroceder (diaSemana - 1) días para llegar al lunes
    const diferenciaLunes = diaSemana === 0 ? -6 : 1 - diaSemana;
    inicioSemana.setDate(fechaActual.getDate() + diferenciaLunes);
    
    console.log('Fecha actual:', fechaActual);
    console.log('Día de la semana:', diaSemana);
    console.log('Inicio semana (lunes):', inicioSemana);
    
    // Encabezados de días
    const diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    
    for (let i = 0; i < 7; i++) {
        const dia = new Date(inicioSemana);
        dia.setDate(inicioSemana.getDate() + i);
        
        const diaElemento = document.createElement('div');
        diaElemento.className = 'week-day-header';
        
        // Marcar si es hoy
        const hoy = new Date();
        const esHoy = dia.toDateString() === hoy.toDateString();
        
        diaElemento.innerHTML = `
            <div>${diasSemana[i]}</div>
            <div class="${esHoy ? 'hoy' : ''}">${dia.getDate()}</div>
        `;
        diaElemento.dataset.dia = i;
        weekDaysHeader.appendChild(diaElemento);
    }
    
    // Columna de horas
    const totalHoras = 18; // de 7 a 24 = 18 horas
    const alturaTotalDisponible = 1020;
    const alturaHoraSlot = alturaTotalDisponible / totalHoras;
    
    for (let hora = 7; hora <= 24; hora++) {
        const timeSlot = document.createElement('div');
        timeSlot.className = 'time-slot';
        timeSlot.textContent = `${hora.toString().padStart(2, '0')}:00`;
        timeSlot.dataset.hora = hora;
        timeSlot.style.height = `${alturaHoraSlot}px`;
        timeColumn.appendChild(timeSlot);
    }
    
    // Grid de días y horas
    for (let dia = 0; dia < 7; dia++) {
        const dayColumn = document.createElement('div');
        dayColumn.className = 'day-column';
        dayColumn.dataset.dia = dia;
        
        for (let hora = 7; hora <= 24; hora++) {
            const hourSlot = document.createElement('div');
            hourSlot.className = 'hour-slot';
            hourSlot.dataset.dia = dia;
            hourSlot.dataset.hora = hora;
            hourSlot.style.height = `${alturaHoraSlot}px`;
            hourSlot.onclick = () => {
                const fechaSeleccionada = new Date(inicioSemana);
                fechaSeleccionada.setDate(inicioSemana.getDate() + dia);
                fechaSeleccionada.setHours(hora, 0, 0, 0);
                mostrarModalAgregarEvento(fechaSeleccionada);
            };
            dayColumn.appendChild(hourSlot);
        }
        
        daysGrid.appendChild(dayColumn);
    }
    
    // Colocar eventos - CORRECCIÓN en el filtrado
    const eventosSemana = eventos.filter(evento => {
        const fechaEvento = parsearFecha(evento.fecha);
        const finSemana = new Date(inicioSemana);
        finSemana.setDate(inicioSemana.getDate() + 7);
        
        console.log('Evento:', evento.nombre, 'Fecha:', fechaEvento);
        console.log('Rango semana:', inicioSemana, 'a', finSemana);
        
        return fechaEvento >= inicioSemana && fechaEvento < finSemana;
    });
    
    console.log('Eventos de la semana:', eventosSemana);
    
    eventosSemana.forEach(evento => {
        const fechaEvento = parsearFecha(evento.fecha);
        
        // CORRECCIÓN: Calcular correctamente el día de la semana (0-6 donde 0=lunes)
        const diaSemanaEvento = (fechaEvento.getDay() + 6) % 7; // Convertir: 0=dom -> 6=lun, 1=lun -> 0=lun, etc.
        
        console.log('Posicionando evento:', evento.nombre, 'Día:', diaSemanaEvento, 'Fecha:', fechaEvento);
        
        const [horaIniStr, minutoIniStr] = evento.horaIni.split(':');
        const [horaFinStr, minutoFinStr] = evento.horaFin.split(':');
        
        const horaIni = parseInt(horaIniStr);
        const minutoIni = parseInt(minutoIniStr);
        const horaFin = parseInt(horaFinStr);
        const minutoFin = parseInt(minutoFinStr);
        
        // Cálculo de posición y altura
        const horaInicioDia = 7;
        const minutosDesdeInicio = (horaIni * 60 + minutoIni) - (horaInicioDia * 60);
        const duracionMinutos = (horaFin * 60 + minutoFin) - (horaIni * 60 + minutoIni);
        
        const posicionTop = (minutosDesdeInicio / 60) * alturaHoraSlot;
        const altura = (duracionMinutos / 60) * alturaHoraSlot;
        
        const eventoElemento = document.createElement('div');
        const horaFormateada = formatearHora(evento.horaIni);
        eventoElemento.className = 'week-event';
        eventoElemento.innerHTML = `
            <strong>${horaFormateada}</strong>
            <div>${evento.nombre}</div>
        `;
        eventoElemento.title = `${evento.nombre}\n${evento.descripcion || 'Sin descripción'}\n${evento.horaIni} - ${evento.horaFin}`;
        
        // Posicionamiento CORREGIDO
        eventoElemento.style.top = `${posicionTop}px`;
        eventoElemento.style.height = `${altura}px`;
        eventoElemento.style.left = `calc(${diaSemanaEvento * (100 / 7)}% + 2px)`;
        eventoElemento.style.width = `calc(${100 / 7}% - 4px)`;
        
        eventoElemento.onclick = (e) => {
            e.stopPropagation();
            mostrarDetallesEvento(evento);
        };
        
        daysGrid.appendChild(eventoElemento);
    });
}

function renderizarVistaLista() {
    const lista = document.getElementById('listaEventos');
    lista.innerHTML = '';
    
    const eventosOrdenados = eventos
        .filter(evento => {
            const fechaEvento = parsearFecha(evento.fecha);
            return fechaEvento >= new Date();
        })
        .sort((a, b) => {
            const fechaA = parsearFecha(a.fecha);
            const fechaB = parsearFecha(b.fecha);
            return fechaA - fechaB;
        });
    
    if (eventosOrdenados.length === 0) {
        lista.innerHTML = '<div class="event-list-item"><div class="event-info">No hay eventos próximos</div></div>';
        return;
    }
    
    eventosOrdenados.forEach(evento => {
        const eventoElemento = document.createElement('div');
        eventoElemento.className = 'event-list-item';
        
        const fechaEvento = parsearFecha(evento.fecha);
        const fechaFormateada = fechaEvento.toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const horaFormIni = formatearHora(evento.horaIni);
        const horaFormFin = formatearHora(evento.horaFin);
        
        eventoElemento.innerHTML = `
            <div class="event-info">
                <h4>${evento.nombre}</h4>
                <div class="event-meta">
                    ${fechaFormateada} | ${horaFormIni} - ${horaFormFin}
                </div>
                <p>${evento.descripcion || 'Sin descripción'}</p>
            </div>
            <div class="event-actions">
                <button class="asistencia-btn" onclick="toggleAsistencia(${evento.id})">
                    ${evento.asistiendo ? 'No asistiré' : 'Asistiré'}
                </button>
                <button class="btn btn-outline" onclick="editarEvento(${evento.id})">Editar</button>
                <button class="btn btn-danger" onclick="eliminarEvento(${evento.id})">Eliminar</button>
            </div>
        `;
        
        if (evento.asistiendo) {
            eventoElemento.querySelector('.asistencia-btn').classList.add('asistiendo');
        }
        
        lista.appendChild(eventoElemento);
    });
}

function mostrarModalAgregarEvento(fecha = null) {
    eventoEditando = null;
    document.getElementById('modalTitulo').textContent = 'Agregar Evento';
    document.getElementById('formEvento').reset();
    document.getElementById('eventoId').value = '';
    
    // Restaurar botones por defecto
    document.querySelector('.form-actions').innerHTML = `
        <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    `;
    
    if (fecha) {
        // CORRECCIÓN: Usar formato YYYY-MM-DD directamente sin conversión UTC
        const año = fecha.getFullYear();
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const dia = String(fecha.getDate()).padStart(2, '0');
        const fechaISO = `${año}-${mes}-${dia}`;
        
        document.getElementById('fecha').value = fechaISO;
        
        if (vistaActual === 'semana') {
            const hora = fecha.getHours().toString().padStart(2, '0');
            const minutos = fecha.getMinutes().toString().padStart(2, '0');
            document.getElementById('horaIni').value = `${hora}:${minutos}`;
            
            // Hora fin por defecto: 1 hora después
            const horaFin = new Date(fecha);
            horaFin.setHours(fecha.getHours() + 1);
            document.getElementById('horaFin').value = 
                `${horaFin.getHours().toString().padStart(2, '0')}:${horaFin.getMinutes().toString().padStart(2, '0')}`;
        }
    } else {
        document.getElementById('fecha').value = '';
    }
    
    // Habilitar todos los campos
    document.querySelectorAll('#formEvento input, #formEvento textarea').forEach(input => {
        input.disabled = false;
    });
    
    document.getElementById('modalEvento').style.display = 'block';
}

function mostrarDetallesEvento(evento) {
    eventoEditando = evento;
    document.getElementById('modalTitulo').textContent = 'Detalles del Evento';
    document.getElementById('eventoId').value = evento.id;
    document.getElementById('nombre').value = evento.nombre;
    document.getElementById('descripcion').value = evento.descripcion;
    
    // CORRECCIÓN: Mostrar la fecha directamente sin conversión UTC
    document.getElementById('fecha').value = evento.fecha;
    
    document.getElementById('horaIni').value = evento.horaIni;
    document.getElementById('horaFin').value = evento.horaFin;
    
    // Deshabilitar campos para solo lectura
    document.querySelectorAll('#formEvento input, #formEvento textarea').forEach(input => {
        input.disabled = true;
    });
    
    document.querySelector('.form-actions').innerHTML = `
        <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
        <button type="button" class="btn btn-outline" onclick="habilitarEdicion()">Editar</button>
        <button type="button" class="btn btn-danger" onclick="eliminarEvento(${evento.id})">Eliminar</button>
    `;
    
    document.getElementById('modalEvento').style.display = 'block';
}

function habilitarEdicion() {
    document.querySelectorAll('#formEvento input, #formEvento textarea').forEach(input => {
        input.disabled = false;
    });
    
    document.querySelector('.form-actions').innerHTML = `
        <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    `;
    
    document.getElementById('modalTitulo').textContent = 'Editar Evento';
}

function guardarEvento(event) {
    event.preventDefault();
    
    // Prevenir múltiples envíos
    const submitButton = event.target.querySelector('button[type="submit"]');
    if (submitButton.disabled) return;
    
    submitButton.disabled = true;
    submitButton.textContent = 'Guardando...';
    
    const formData = new FormData(event.target);
    const datos = {
        id: formData.get('id') || null,
        nombre: formData.get('nombre'),
        descripcion: formData.get('descripcion'),
        fecha: formData.get('fecha'),
        horaIni: formData.get('horaIni'),
        horaFin: formData.get('horaFin')
    };
    
    // Validaciones básicas
    if (!datos.nombre || !datos.fecha || !datos.horaIni || !datos.horaFin) {
        alert('Por favor completa todos los campos obligatorios');
        submitButton.disabled = false;
        submitButton.textContent = 'Guardar';
        return;
    }
    
    // Validar que hora fin sea mayor que hora inicio
    if (datos.horaIni >= datos.horaFin) {
        alert('La hora de fin debe ser mayor que la hora de inicio');
        submitButton.disabled = false;
        submitButton.textContent = 'Guardar';
        return;
    }
    
    // Determinar la URL correcta
    const url = datos.id ? 'cl/editar_evento.php' : 'cl/agregar_evento.php';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(resultado => {
        if (resultado.success) {
            cerrarModal();
            // Recargar eventos desde el servidor
            cargarEventos();
        } else {
            alert('Error: ' + resultado.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el evento');
    })
    .finally(() => {
        // Rehabilitar el botón
        submitButton.disabled = false;
        submitButton.textContent = 'Guardar';
    });
}

function editarEvento(id) {
    const evento = eventos.find(e => e.id === id);
    if (evento) {
        eventoEditando = evento;
        document.getElementById('modalTitulo').textContent = 'Editar Evento';
        document.getElementById('eventoId').value = evento.id;
        document.getElementById('nombre').value = evento.nombre;
        document.getElementById('descripcion').value = evento.descripcion;
        document.getElementById('fecha').value = evento.fecha;
        document.getElementById('horaIni').value = evento.horaIni;
        document.getElementById('horaFin').value = evento.horaFin;
        
        document.querySelectorAll('#formEvento input, #formEvento textarea').forEach(input => {
            input.disabled = false;
        });
        
        document.querySelector('.form-actions').innerHTML = `
            <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
        `;
        
        document.getElementById('modalEvento').style.display = 'block';
    }
}

function eliminarEvento(id) {
    document.getElementById('mensajeConfirmacion').textContent = '¿Estás seguro de que quieres eliminar este evento?';
    document.getElementById('btnConfirmarAccion').onclick = () => confirmarEliminarEvento(id);
    document.getElementById('modalConfirmacion').style.display = 'block';
}

function confirmarEliminarEvento(id) {
    const button = document.getElementById('btnConfirmarAccion');
    button.disabled = true;
    button.textContent = 'Eliminando...';
    
    fetch('cl/eliminar_evento.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(resultado => {
        if (resultado.success) {
            cerrarModalConfirmacion();
            cargarEventos();
        } else {
            alert('Error: ' + resultado.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el evento');
    })
    .finally(() => {
        button.disabled = false;
        button.textContent = 'Confirmar';
    });
}

function toggleAsistencia(idEvento) {
    const button = event.target;
    const originalText = button.textContent;
    
    button.disabled = true;
    button.textContent = 'Actualizando...';
    
    fetch('cl/asistir_evento.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ idEvento: idEvento })
    })
    .then(response => response.json())
    .then(resultado => {
        if (resultado.success) {
            cargarEventos();
        } else {
            alert('Error: ' + resultado.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar asistencia');
    })
    .finally(() => {
        button.disabled = false;
        button.textContent = originalText;
    });
}

function cargarEventos() {
    let url = 'cl/obtener_eventos.php';
    
    // Agregar parámetros según la vista actual para optimizar
    const params = new URLSearchParams();
    
    if (vistaActual === 'mes') {
        const inicioMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1);
        const finMes = new Date(fechaActual.getFullYear(), fechaActual.getMonth() + 1, 0);
        
        params.append('fecha_inicio', formatoFechaInput(inicioMes));
        params.append('fecha_fin', formatoFechaInput(finMes));
    }
    else if (vistaActual === 'semana') {
        const inicioSemana = new Date(fechaActual);
        let diaSemana = fechaActual.getDay();
        if (diaSemana === 0) diaSemana = 7;
        inicioSemana.setDate(fechaActual.getDate() - (diaSemana - 1));
        const finSemana = new Date(inicioSemana);
        finSemana.setDate(inicioSemana.getDate() + 6);
        
        params.append('fecha_inicio', formatoFechaInput(inicioSemana));
        params.append('fecha_fin', formatoFechaInput(finSemana));
    }
    else if (vistaActual === 'lista') {
        const hoy = new Date();
        params.append('fecha_inicio', formatoFechaInput(hoy));
        // Los próximos 30 días para la vista lista
        const finPeriodo = new Date(hoy);
        finPeriodo.setDate(hoy.getDate() + 30);
        params.append('fecha_fin', formatoFechaInput(finPeriodo));
    }
    
    url += '?' + params.toString();
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            eventos = Array.isArray(data) ? data : [];
            actualizarVista();
        })
        .catch(error => {
            console.error('Error al cargar eventos:', error);
            eventos = [];
            actualizarVista();
        });
}

function cerrarModal() {
    document.getElementById('modalEvento').style.display = 'none';
    eventoEditando = null;
    
    // Restaurar estado del formulario
    document.querySelectorAll('#formEvento input, #formEvento textarea').forEach(input => {
        input.disabled = false;
    });
}

function cerrarModalConfirmacion() {
    document.getElementById('modalConfirmacion').style.display = 'none';
    document.getElementById('modalEvento').style.display = 'none';
}

// FUNCIONES UTILITARIAS PARA MANEJAR FECHAS

// Función para parsear fechas evitando problemas de zona horaria
function parsearFecha(fechaStr) {
    if (!fechaStr) return new Date();
    
    // Si la fecha ya está en formato YYYY-MM-DD, crear fecha local
    const partes = fechaStr.split('-');
    if (partes.length === 3) {
        return new Date(partes[0], partes[1] - 1, partes[2]);
    }
    
    // Para otros formatos, usar el constructor de Date
    return new Date(fechaStr);
}

// Función para formatear fecha en formato input (YYYY-MM-DD)
function formatoFechaInput(fecha) {
    const año = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const dia = String(fecha.getDate()).padStart(2, '0');
    return `${año}-${mes}-${dia}`;
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modalEvento');
    const modalConfirm = document.getElementById('modalConfirmacion');
    
    if (event.target === modal) {
        cerrarModal();
    }
    if (event.target === modalConfirm) {
        cerrarModalConfirmacion();
    }
}

// Inicializar cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    inicializarCalendario();
    
    // Prevenir múltiples submissions
    let formSubmitting = false;
    const formEvento = document.getElementById('formEvento');
    
    formEvento.addEventListener('submit', function(e) {
        if (formSubmitting) {
            e.preventDefault();
            return;
        }
        
        formSubmitting = true;
        setTimeout(() => {
            formSubmitting = false;
        }, 2000);
    });
    
    // Tecla Escape para cerrar modales
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal();
            cerrarModalConfirmacion();
        }
    });
});

// Exportar funciones globales
window.inicializarCalendario = inicializarCalendario;
window.cambiarVista = cambiarVista;
window.navegarFecha = navegarFecha;
window.irHoy = irHoy;
window.mostrarModalAgregarEvento = mostrarModalAgregarEvento;
window.mostrarDetallesEvento = mostrarDetallesEvento;
window.habilitarEdicion = habilitarEdicion;
window.guardarEvento = guardarEvento;
window.editarEvento = editarEvento;
window.eliminarEvento = eliminarEvento;
window.confirmarEliminarEvento = confirmarEliminarEvento;
window.toggleAsistencia = toggleAsistencia;
window.cerrarModal = cerrarModal;
window.cerrarModalConfirmacion = cerrarModalConfirmacion;