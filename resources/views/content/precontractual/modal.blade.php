<!-- Modal de Edición -->
<div class="modal fade" id="editarPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
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
    
                    <!-- Estado del Estudio -->


                    <!-- Nota de Rechazo -->
                    <div class="mb-3" id="notaAdicionalContainer" style="display: none;">
                        <label class="form-label">Nota Adicional</label>
                        <textarea class="form-control" id="notaAdicional" name="nota_adicional"></textarea>
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
        const documento = document.getElementById('estudioPrevio');
        let estadoOriginal = estadoEstudio.value;
        let documentoOriginal = documento.value;

        // Inicializar el modal
        const modalElement = document.getElementById('editarPlanModal');
        modalElement.addEventListener('shown.bs.modal', function() {
            // Restablecer el valor del select y ocultar el contenedor de nota
            estadoEstudio.value = estadoOriginal;
            toggleNotaAdicional(estadoEstudio.value);
        });

        // Manejar cambio de estado
        estadoEstudio.addEventListener('change', function() {
            toggleNotaAdicional(this.value);
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

        // Asegúrate de llamar a toggleNotaAdicional al cargar la página para manejar el estado inicial
        toggleNotaAdicional(estadoEstudio.value);

        // Validar formulario antes de enviar
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let tipoCambio = '';
            if (estadoEstudio.value !== estadoOriginal) {
                tipoCambio += 'Cambio de estado; ';
            }
            if (documento.value !== documentoOriginal) {
                tipoCambio += 'Cambio de documento; ';
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
            formData.append('nota_adicional', document.getElementById('notaAdicional')
            .value); // Asegurarse de que se envía el valor correcto

            fetch(this.getAttribute('action'), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cerrar el modal
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        modal.hide();

                        // Mostrar mensaje de éxito usando SweetAlert2 si está disponible
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'Plan actualizado exitosamente',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        } else {
                            alert('Plan actualizado exitosamente');
                        }
                    } else {
                        throw new Error(data.message || 'Error al actualizar el plan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Mostrar error usando SweetAlert2 si está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error',
                            text: error.message || 'Error al procesar la solicitud',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        alert(error.message || 'Error al procesar la solicitud');
                    }
                });
        });

        // Agregar esta función después del DOMContentLoaded
        function mostrarDocumentoActual(url) {
            const documentoContainer = document.getElementById('documentoActual');
            if (!url) {
                documentoContainer.innerHTML = '<p>No hay documento disponible</p>';
                return;
            }

            // Obtener la extensión del archivo
            const extension = url.split('.').pop().toLowerCase();

            if (extension === 'pdf') {
                // Para PDFs, mostrar un visor embebido
                documentoContainer.innerHTML = `
                <div class="mb-2">
                    <a href="${url}" target="_blank" class="btn btn-sm btn-primary">Ver PDF</a>
                </div>
                <iframe src="${url}" width="100%" height="300px" style="border: 1px solid #ddd;"></iframe>
            `;
            } else {
                // Para otros tipos de archivo, mostrar solo el botón de descarga
                documentoContainer.innerHTML = `
                <div class="mb-2">
                    <a href="${url}" download class="btn btn-sm btn-primary">
                        <i class="fas fa-download me-1"></i> Descargar ${extension.toUpperCase()}
                    </a>
                </div>
            `;
            }
        }

        // Función para obtener la clase de color según el estado
        function getEstadoClass(estado) {
            switch (estado) {
                case 'aprobado':
                    return 'text-success fw-bold';
                case 'rechazado':
                    return 'text-danger fw-bold';
                case 'en_revision':
                    return 'text-warning fw-bold';
                default:
                    return '';
            }
        }

        // Función para formatear el estado para mostrar
        function formatearEstado(estado) {
            return estado.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        // Actualizar la función que muestra el historial
        function mostrarHistorial(historial) {
            const tbody = document.getElementById('historialCambios');
            tbody.innerHTML = '';

            historial.forEach(registro => {
                const tr = document.createElement('tr');
                const comentarioFormateado = registro.comentarios ? registro.comentarios : '-';
                tr.innerHTML = `
                    <td>${registro.fecha_cambio}</td>
                    <td class="${getEstadoClass(registro.estado_nuevo)}">${formatearEstado(registro.estado_nuevo)}</td>
                    <td>${registro.usuario}</td>
                    <td>${comentarioFormateado}</td>
                `;
                tbody.appendChild(tr);
            });
        }
    });

    function eliminarPlan() {
        const planId = document.getElementById('editarPlanForm').getAttribute('action').split('/').pop();

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará el plan y todos sus archivos asociados. No podrás revertir esto.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/precontractual/eliminar/${planId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cerrar el modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'confirmacionEliminarModal'));
                            modal.hide();

                            // Actualizar la tabla o realizar otras acciones necesarias
                            alert('El plan ha sido eliminado correctamente.');
                        } else {
                            throw new Error(data.message || 'Error al eliminar el plan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message || 'Error al eliminar el plan');
                    });
            }
        });
    }
</script>
