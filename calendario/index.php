<?php
session_start();
// Incluir conexión a la base de datos
require_once '../conn.php';

// Incluir templates
require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>
<link rel="stylesheet" href="estilos/estilos.css">

<div class="main-content" id="mainContent">
    <div class="calendar-container">
        <div class="calendar-header">
            <h2>Calendario</h2>
            <div class="calendar-controls">
                <button class="btn btn-primary" onclick="mostrarModalAgregarEvento()">
                    <i class="fas fa-plus"></i> Nuevo Evento
                </button>
                <div class="view-controls">
                    <button class="btn btn-outline" onclick="cambiarVista('mes')" id="btnVistaMes">Mes</button>
                    <button class="btn btn-outline" onclick="cambiarVista('semana')" id="btnVistaSemana">Semana</button>
                    <button class="btn btn-outline" onclick="cambiarVista('lista')" id="btnVistaLista">Lista</button>
                </div>
                <div class="navigation-controls">
                    <button class="btn btn-outline" onclick="navegarFecha('anterior')">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span id="fechaActual" class="fecha-actual"></span>
                    <button class="btn btn-outline" onclick="navegarFecha('siguiente')">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button class="btn btn-outline" onclick="irHoy()">Hoy</button>
                </div>
            </div>
        </div>

        <!-- Vista Mes -->
        <div id="vistaMes" class="calendar-view">
            <div class="calendar-grid-header">
                <div class="day-header">Lun</div>
                <div class="day-header">Mar</div>
                <div class="day-header">Mié</div>
                <div class="day-header">Jue</div>
                <div class="day-header">Vie</div>
                <div class="day-header">Sáb</div>
                <div class="day-header">Dom</div>
            </div>
            <div id="calendarGrid" class="calendar-grid"></div>
        </div>

        <!-- Vista Semana -->
        <div id="vistaSemana" class="calendar-view" style="display: none;">
            <div class="week-view">
                <div class="week-header">
                    <div class="time-column-header">Hora</div>
                    <div class="week-days-header" id="weekDaysHeader">
                        <!-- Los días se insertarán aquí via JavaScript -->
                    </div>
                </div>
                <div class="week-grid-container">
                    <div class="time-column" id="timeColumn">
                        <!-- Las horas se insertarán aquí -->
                    </div>
                    <div class="days-grid" id="daysGrid">
                        <!-- Las celdas de horas por día se insertarán aquí -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Vista Lista -->
        <div id="vistaLista" class="calendar-view" style="display: none;">
            <div id="listaEventos" class="event-list"></div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar evento -->
<div id="modalEvento" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitulo">Agregar Evento</h3>
            <span class="close" onclick="cerrarModal()">&times;</span>
        </div>
        <form id="formEvento" onsubmit="guardarEvento(event)">
            <input type="hidden" id="eventoId" name="id">
            <div class="form-group">
                <label for="nombre">Nombre del Evento:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="3"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="horaIni">Hora Inicio:</label>
                    <input type="time" id="horaIni" name="horaIni" required>
                </div>
                <div class="form-group">
                    <label for="horaFin">Hora Fin:</label>
                    <input type="time" id="horaFin" name="horaFin" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de confirmación -->
<div id="modalConfirmacion" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar</h3>
            <span class="close" onclick="cerrarModalConfirmacion()">&times;</span>
        </div>
        <div class="modal-body">
            <p id="mensajeConfirmacion"></p>
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="cerrarModalConfirmacion()">Cancelar</button>
            <button type="button" class="btn btn-danger" id="btnConfirmarAccion">Confirmar</button>
        </div>
    </div>
</div>

<script src="js/calendario.js"></script>
<script>
    // Inicializar calendario cuando el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        inicializarCalendario();
    });
</script>

<?php
// Incluir footer
require_once '../templates/footer.php';
?>