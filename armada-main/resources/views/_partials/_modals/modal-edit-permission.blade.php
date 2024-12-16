<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPermissionModalLabel">Editar Permiso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditPermission">
          <input type="hidden" id="permissionId">
          <div class="mb-3">
            <label for="permissionName" class="form-label">Nombre del Permiso</label>
            <input type="text" class="form-control" id="permissionName" required>
          </div>
          <!-- Puedes agregar más campos si es necesario -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="savePermissionChanges">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>
<!--/ Edit Permission Modal -->
