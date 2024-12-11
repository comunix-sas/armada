'use strict';

$(function() {
            const dtUserTable = $('.datatables-user');

            if (dtUserTable.length) {
                if ($.fn.DataTable.isDataTable(dtUserTable)) {
                    dtUserTable.DataTable().destroy();
                }

                const dtUser = dtUserTable.DataTable({
                    processing: true,
                    ajax: {
                        url: baseUrl + 'api/roles',
                        type: 'GET',
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('auth_token')
                        },
                        dataSrc: 'data',

                    },
                    columns: [{
                            className: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                            searchable: false,
                            title: ''
                        },
                        {
                            data: 'name',
                            title: 'Nombre'
                        },
                        {
                            data: 'created_date',
                            title: 'Fecha de Creación'
                        },
                        {
                            data: null,
                            orderable: false,
                            render: function(data, type, row) {
                                return `
                            <div class="d-inline-block text-nowrap">
                                <button class="btn btn-sm btn-icon edit-permission" data-id="${row.id}">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon delete-permission" data-id="${row.id}">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                            },
                            title: 'Acciones'
                        }
                    ],
                    order: [
                        [1, 'desc']
                    ],
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"<"me-3"f><"dt-action-buttons"B>>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    buttons: [{
                        text: '<i class="ti ti-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Agregar Rol</span>',
                        className: 'btn btn-primary btn-sm',
                        attr: {
                            'data-bs-toggle': 'modal',
                            'data-bs-target': '#addRoleModal'
                        }
                    }],
                    language: {
                        url: '/js/i18n/es-ES.json',
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros por página",
                        zeroRecords: "No se encontraron resultados",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        infoEmpty: "Mostrando 0 a 0 de 0 registros",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    }
                });

                dtUserTable.on('click', 'td.dt-control', function() {
                    const tr = $(this).closest('tr');
                    const row = dtUser.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                    }
                });

                function format(d) {
                    return `<div class="row">
                        <div class="col-12">
                            <h5>Permisos:</h5>
                            ${d.permissions
                              .map(permission => `<span class="badge bg-label-primary me-1">${permission}</span>`)
                              .join(' ')}
                        </div>
                    </div>`;
    }
    $(document).on('click', '.delete-permission', function() {
        const id = $(this).data('id');

        $('.modal').modal('hide');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'swal2-confirm swal2-styled swal2-default-outline',
                cancelButton: 'swal2-cancel swal2-styled swal2-default-outline',
                denyButton: 'swal2-deny swal2-styled'
            },
            buttonsStyling: true
        });

        swalWithBootstrapButtons.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            customClass: {
                popup: 'swal2-popup swal2-modal swal2-icon-warning swal2-show',
                icon: 'swal2-icon swal2-warning swal2-icon-show',
                actions: 'swal2-actions'
            },
            showCloseButton: false,
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

                $.ajax({
                    url: `${cleanBaseUrl}/api/roles/${id}`,
                    type: 'DELETE',
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('auth_token')
                    },
                    success: function(response) {
                        swalWithBootstrapButtons.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        dtUser.ajax.reload(null, false);
                    },
                    error: function(xhr, status, error) {
                        swalWithBootstrapButtons.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el rol: ' + error,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.edit-permission', function () {
      const id = $(this).data('id');
      $.ajax({
        url: baseUrl + `api/roles/${id}`,
        type: 'GET',
        success: function (response) {
          if (response.status === 'success') {
            const roleName = response.data.name;
            const permissions = response.data.permissions;

            $('#roleName').val(roleName);

            $('#permissionsModalBody').html(
              permissions
                .map(
                  permission => `
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="${permission.name}" id="perm-${permission.id}" ${permission.assigned ? 'checked' : ''}>
                  <label class="form-check-label" for="perm-${permission.id}">
                    ${permission.name}
                  </label>
                </div>
              `
                )
                .join('')
            );

            $('#roleId').val(id);

            $('#permissionsModal').modal('show');
          }
        },
        error: function (xhr) {
          console.error('Error al obtener los datos del rol:', xhr.responseText);
        }
      });
    });

    $('#permissionsModal').on('hidden.bs.modal', function () {
      $('#permissionsModalBody').html('');
      $('#editRoleForm')[0].reset();
    });

    $('#saveRoleChanges').on('click', function () {
      const id = $('#roleId').val();
      const updatedName = $('#roleName').val();
      const updatedPermissions = [];

      $('#permissionsModalBody input[type="checkbox"]').each(function () {
        if ($(this).is(':checked')) {
          updatedPermissions.push($(this).val());
        }
      });

      $.ajax({
        url: baseUrl + `api/roles/${id}`,
        type: 'PUT',
        data: {
          name: updatedName,
          permissions: updatedPermissions
        },
        success: function (response) {
          if (response.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Éxito',
              text: 'Rol actualizado correctamente',
              showConfirmButton: false,
              timer: 1500
            });
            $('#permissionsModal').modal('hide');
            dtUser.ajax.reload();
          }
        },
        error: function (xhr) {
          console.error('Error al actualizar el rol:', xhr.responseText);
        }
      });
    });

    $('#savePermissions').on('click', function () {
      const id = $('#editRoleModal #roleId').val();
      const selectedPermissions = $('#permissionsModalBody input:checked')
        .map(function () {
          return $(this).val();
        })
        .get();

      $.ajax({
        url: baseUrl + `api/roles/${id}/permissions`,
        type: 'PUT',
        data: {
          permissions: selectedPermissions
        },
        success: function (response) {
          if (response.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Éxito',
              text: 'Permisos actualizados correctamente',
              showConfirmButton: false,
              timer: 1500
            });
            $('#permissionsModal').modal('hide');
            dtUser.ajax.reload();
          }
        }
      });
    });
  }
});

$.ajaxSetup({
  headers: {
    Authorization: 'Bearer ' + localStorage.getItem('auth_token'),
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
