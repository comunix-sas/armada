/**
 * Add new role Modal JS
 */

'use strict';

document.addEventListener('DOMContentLoaded', function(e) {
    const addRoleForm = document.getElementById('addRoleForm');
    const permissionsContainer = document.getElementById('permissionsContainer');
    const selectAll = document.getElementById('selectAll');

    $('#addRoleModal').on('show.bs.modal', function() {
        loadPermissions();
        addRoleForm.reset();
        selectAll.checked = false;
    });

    function loadPermissions() {
        $.ajax({
            url: '/api/permissions',
            type: 'GET',
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('auth_token')
            },
            success: function(response) {
                permissionsContainer.innerHTML = '';
                response.data.forEach(permission => {
                    permissionsContainer.innerHTML += `
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input permission-check"
                                    type="checkbox"
                                    id="permission_${permission.id}"
                                    name="permissions[]"
                                    value="${permission.name}"
                                />
                                <label class="form-check-label" for="permission_${permission.id}">
                                    ${permission.name}
                                </label>
                            </div>
                        </div>
                    `;
                });
            },
            error: function(xhr) {
                console.error('Error cargando permisos:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los permisos'
                });
            }
        });
    }

    // Manejar "Seleccionar Todo"
    selectAll.addEventListener('change', function() {
        const permissionChecks = document.querySelectorAll('.permission-check');
        permissionChecks.forEach(check => {
            check.checked = this.checked;
        });
    });

    addRoleForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const selectedPermissions = [];
        document.querySelectorAll('.permission-check:checked').forEach(check => {
            selectedPermissions.push(check.value);
        });

        if (selectedPermissions.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe seleccionar al menos un permiso para crear un rol.'
            });
            return;
        }

        const formData = new FormData();
        formData.append('name', document.getElementById('modalRoleName').value);
        selectedPermissions.forEach(permission => {
            formData.append('permissions[]', permission);
        });

        $.ajax({
            url: '/api/roles',
            type: 'POST',
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('auth_token'),
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Rol creado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#addRoleModal').modal('hide');
                        if ($.fn.DataTable.isDataTable('.datatables-user')) {
                            $('.datatables-user').DataTable().ajax.reload(null, false);
                        }
                        addRoleForm.reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo crear el rol'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error completo:', xhr);

                let errorMessage = 'Ocurrió un error al crear el rol';
                if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.name) {
                    errorMessage = xhr.responseJSON.errors.name[0];
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });
    });
});
