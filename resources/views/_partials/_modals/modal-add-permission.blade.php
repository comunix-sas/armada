<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Nuevo Permiso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addPermissionForm" class="row g-3">
          <div class="col-12">
            <label class="form-label" for="modalPermissionName">Nombre del Permiso</label>
            <input type="text" id="modalPermissionName" name="name" class="form-control" placeholder="Ingrese el nombre del permiso" required />
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1" id="submitPermission">Crear Permiso</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
