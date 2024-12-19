<div class="modal fade" id="editarPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Plan Precontractual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editarPlanForm" enctype="multipart/form-data" action="/ruta/del/formulario">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Información del Plan -->
                    <div class="mb-3">
                        <label class="form-label">Nombre del Plan</label>
                        <input type="text" class="form-control" id="nombrePlan" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado del Estudio</label>
                        <select class="form-select" id="estadoEstudio" name="estadoEstudio" required>
                            <option value="">Seleccione un estado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                    </div>

                    <!-- Nota de Rechazo -->
                    <div class="mb-3" id="notaAdicionalContainer" style="display: none;">
                        <label class="form-label">Nota Adicional</label>
                        <textarea class="form-control" id="notaAdicional" name="nota_adicional"></textarea>
                    </div>

                    <!-- Campos Código SECOP y URL -->
                    <div class="mb-3" id="secopContainer" style="display: none;">
                        <label class="form-label">Código SECOP</label>
                        <input type="text" class="form-control" id="codigoSecop" name="codigo_secop" readonly>
                    </div>
                    <div class="mb-3" id="urlContainer" style="display: none;">
                        <label class="form-label">URL</label>
                        <input type="url" class="form-control" id="url" name="url" readonly>
                    </div>

                    <!-- Nuevo Documento -->
                    <div class="mb-3">
                        <label class="form-label">Nuevo Documento (opcional)</label>
                        <input type="file" class="form-control" id="estudioPrevio" name="estudioPrevio"
                            accept=".pdf,.doc,.docx,.xlsx,.xls">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Documento Actual</label>
                        <div id="documentoActual"></div>
                    </div>

                    <!-- Historial -->
                    <div class="mb-3">
                        <label class="form-label">Historial de Cambios</label>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Usuario</th>
                                        <th>Comentarios</th>
                                    </tr>
                                </thead>
                                <tbody id="historialCambios"></tbody>
                            </table>
                        </div>
                        
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Historial de Cambios</label>
                        <div class="table-responsive">
                            <table id="apiDataTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Entidad</th>
                                        <th>NIT Entidad</th>
                                        <th>Departamento</th>
                                        <th>Ciudad</th>
                                        <th>Orden Entidad</th>
                                        <th>Referencia del Proceso</th>
                                        <th>Nombre del Procedimiento</th>
                                        <th>Descripción del Procedimiento</th>
                                        <th>Fase</th>
                                        <th>Fecha de Publicación</th>
                                        <th>Precio Base</th>
                                        <th>Modalidad de Contratación</th>
                                        <th>Justificación Modalidad</th>
                                        <th>Duración</th>
                                        <th>Unidad de Duración</th>
                                        <th>Estado del Procedimiento</th>
                                        <th>Adjudicado</th>
                                        <th>Nombre del Proveedor</th>
                                        <th>NIT del Proveedor</th>
                                        <th>Valor Total Adjudicación</th>
                                        <th>Nombre del Adjudicador</th>
                                        <th>URL Proceso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                        
                    
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <!-- Botón de eliminar al lado izquierdo -->
                    <button type="button" class="btn btn-danger" onclick="eliminarPlan()">
                        <i class="ti ti-trash me-1"></i>Eliminar Plan
                    </button>

                    <!-- Botones de cerrar y guardar al lado derecho -->
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editarPlanForm');
    const estadoEstudio = document.getElementById('estadoEstudio');
    const codigoSecop = document.getElementById('codigoSecop');
    const urlInput = document.getElementById('url');
    let estadoOriginal = estadoEstudio.value;
    let codigoSecopOriginal = codigoSecop.value;
    let urlOriginal = urlInput.value;

    // Inicializar el modal
    const modalElement = document.getElementById('editarPlanModal');
    modalElement.addEventListener('shown.bs.modal', function() {
        // Restablecer el valor del select y ocultar el contenedor de nota
        estadoEstudio.value = estadoOriginal;
        toggleNotaAdicional(estadoEstudio.value);
        toggleSecopFields(estadoEstudio.value); // Habilitar campos SECOP si está aprobado
        toggleEstadoEstudio(estadoEstudio.value); // Desactivar si está aprobado
    });

    // Manejar cambio de estado
    estadoEstudio.addEventListener('change', function() {
        toggleNotaAdicional(this.value);
        toggleSecopFields(this.value); // Habilitar campos SECOP si está aprobado
        toggleEstadoEstudio(this.value); // Desactivar si está aprobado
    });

    // Función para mostrar/ocultar el contenedor de nota adicional
    function toggleNotaAdicional(estado) {
        const notaContainer = document.getElementById('notaAdicionalContainer');
        const notaTextarea = document.getElementById('notaAdicional');
        if (estado === 'rechazado') {
            notaContainer.style.display = 'block';
            notaTextarea.setAttribute('required', 'required');
        } else {
            notaContainer.style.display = 'none';
            notaTextarea.removeAttribute('required');
            notaTextarea.value = '';
        }
    }

    // Función para habilitar los campos de SECOP (Código y URL) cuando el estado es aprobado
    function toggleSecopFields(estado) {
        const secopContainer = document.getElementById('secopContainer');
        const urlContainer = document.getElementById('urlContainer');
        if (estado === 'aprobado') {
            secopContainer.style.display = 'block';
            urlContainer.style.display = 'block';
        } else {
            secopContainer.style.display = 'none';
            urlContainer.style.display = 'none';
            codigoSecop.value = '';
            urlInput.value = '';
        }
    }

    // Función para desactivar el campo de estado si está aprobado
    function toggleEstadoEstudio(estado) {
        if (estado === 'aprobado') {
            estadoEstudio.disabled = true;  // Desactivar si está aprobado
        } else {
            estadoEstudio.disabled = false; // Activar en otros casos
        }
    }

    // Asegúrate de llamar a toggleNotaAdicional, toggleSecopFields y toggleEstadoEstudio al cargar la página para manejar el estado inicial
    toggleNotaAdicional(estadoEstudio.value);
    toggleSecopFields(estadoEstudio.value);
    toggleEstadoEstudio(estadoEstudio.value);

    // Validar formulario antes de enviar
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        let tipoCambio = '';
        if (estadoEstudio.value !== estadoOriginal) {
            tipoCambio += 'Cambio de estado; ';
        }
        if (codigoSecop.value !== codigoSecopOriginal) {
            tipoCambio += 'Cambio de código SECOP; ';
        }
        if (urlInput.value !== urlOriginal) {
            tipoCambio += 'Cambio de URL; ';
        }

        // Validación adicional para el campo notaAdicional
        if (estadoEstudio.value === 'rechazado') {
            const notaAdicional = document.getElementById('notaAdicional').value.trim();
            if (!notaAdicional) {
                // Mostrar error usando SweetAlert2 si está disponible
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error',
                        text: 'El campo de nota adicional es requerido cuando el estado es rechazado.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                } else {
                    alert('El campo de nota adicional es requerido cuando el estado es rechazado.');
                }
                return; // Detener el envío del formulario
            }
        }

        const formData = new FormData(this);
        formData.append('tipo_cambio', tipoCambio);
        formData.append('nota_adicional', document.getElementById('notaAdicional').value); // Asegurarse de que el valor de notaAdicional esté presente en formData

        const xhr = new XMLHttpRequest();
        xhr.open('POST', this.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Mostrar mensaje de éxito usando SweetAlert2 si está disponible
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            alert(response.message);
                            location.reload();
                        }
                    } else {
                        // Mostrar mensaje de error usando SweetAlert2 si está disponible
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        } else {
                            alert(response.message);
                        }
                    }
                } else {
                    // Mostrar mensaje de error usando SweetAlert2 si está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al guardar los cambios.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        alert('Error al guardar los cambios.');
                    }
                }
            }
        };

        xhr.send(formData);
    });

    // Función para mostrar el documento actual
    function mostrarDocumentoActual(url) {
        const documentoActual = document.getElementById('documentoActual');
        if (url) {
            documentoActual.innerHTML = `<a href="${url}" target="_blank">${url}</a>`;
        } else {
            documentoActual.innerHTML = 'No hay documento disponible.';
        }
    }
});

</script>
