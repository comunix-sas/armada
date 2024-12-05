<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="role-title mb-2">Agregar Nuevo Rol</h4>
          <p>Configura los permisos del rol</p>
        </div>
        
        <!-- Add role form -->
        <form id="addRoleForm" class="row g-6" onsubmit="return false">
          <div class="col-12 mb-4">
            <label class="form-label" for="modalRoleName">Nombre del Rol</label>
            <input type="text" id="modalRoleName" name="modalRoleName" class="form-control" placeholder="Ingrese el nombre del rol" />
          </div>
          
          <div class="col-12">
            <h5 class="mb-4">Permisos del Rol</h5>
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-medium">
                      <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Seleccionar todos los permisos"></i>
                      Seleccionar Todo
                    </td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="selectAll" />
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <div id="permissionsContainer" class="row g-2">
                        <!-- Los permisos se cargarán dinámicamente aquí -->
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Crear Rol</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->
