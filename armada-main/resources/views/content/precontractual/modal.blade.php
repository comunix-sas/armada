<!-- Modal de Edición -->
<div class="modal fade" id="editarPlanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Plan Precontractual</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editarPlanForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <!-- Información del Plan -->
          <div class="mb-3">
            <label class="form-label">Nombre del Plan</label>
            <input type="text" class="form-control" id="nombrePlan" readonly>
          </div>

          <!-- Estado del Estudio -->
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

       
          <!-- Nuevo Documento -->
          <div class="mb-3">
            <label class="form-label">Nuevo Documento (opcional)</label>
            <input type="file" class="form-control" id="estudioPrevio" name="estudioPrevio" accept=".pdf,.doc,.docx,.xlsx,.xls">
             <div class="mb-3">
            <label class="form-label">Documento Actual</label>
            <div id="documentoActual"></div>
          </div>
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

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editarPlanForm');
    const estadoSelect = document.getElementById('estadoEstudio');
    const notaContainer = document.getElementById('notaAdicionalContainer');
    const notaTextarea = document.getElementById('notaAdicional');

    // Manejar cambio de estado
    estadoSelect.addEventListener('change', function() {
        const isRechazado = this.value === 'rechazado';
        
        // Mostrar/ocultar el contenedor de nota de manera explícita
        if (isRechazado) {
            notaContainer.style.display = 'block';
            notaTextarea.setAttribute('required', 'required');
        } else {
            notaContainer.style.display = 'none';
            notaTextarea.removeAttribute('required');
            notaTextarea.value = '';
            notaTextarea.classList.remove('is-invalid');
        }
    });

    // Validar formulario antes de enviar
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isRechazado = estadoSelect.value === 'rechazado';
        let isValid = true;

        // Validar nota de rechazo
        if (isRechazado) {
            if (!notaTextarea.value.trim()) {
                notaTextarea.classList.add('is-invalid');
                isValid = false;
            } else {
                notaTextarea.classList.remove('is-invalid');
            }
        }

        if (!isValid) {
            return;
        }

        // Preparar datos para enviar
        const formData = new FormData(this);
        
        // Enviar formulario
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editarPlanModal'));
                modal.hide();
                
                if (typeof actualizarTablaPlanesValidacion === 'function') {
                    actualizarTablaPlanesValidacion();
                }
                
                alert('Plan actualizado exitosamente');
            } else {
                alert(data.message || 'Error al actualizar el plan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    });
});
</script>
