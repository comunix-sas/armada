'use strict';

$(function() {
    const dataTablePermissions = $('.datatables-permissions');

    if (dataTablePermissions.length) {

        if ($.fn.DataTable.isDataTable(dataTablePermissions)) {
            dataTablePermissions.DataTable().destroy();
        }

        const dt_permission = dataTablePermissions.DataTable({
            processing: true,
            ajax: {
                url: baseUrl + 'api/permissions',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('auth_token')
                },
                dataSrc: 'data'
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
                    title: 'Nombre del permiso'
                },

                {
                    data: 'created_date',
                    title: 'Fecha de creación'
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
                [1, 'asc']
            ],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"<"me-3"f><"dt-action-buttons"B>>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            language: {
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                processing: "Procesando...",
            },
            buttons: [{
                text: '<i class="ti ti-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Agregar Permiso</span>',
                className: 'btn btn-primary btn-sm',

                action: function() {
                    const modal = new bootstrap.Modal(document.getElementById('addPermissionModal'));
                    modal.show();
                }
            }],
        });

        dataTablePermissions.on('click', 'td.dt-control', function() {
            const tr = $(this).closest('tr');
            const row = dt_permission.row(tr);

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
                    <h5>Detalles del permiso:</h5>
                    <strong>Roles:</strong> ${d.roles.join(', ')}
                </div>
            </div>`;
        }

        // Manejador de eventos para eliminar
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
                        url: `${cleanBaseUrl}/api/permissions/${id}`,
                        type: 'DELETE',
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('auth_token')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            dt_permission.ajax.reload(null, false);
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el permiso: ' + error
                            });
                        }
                    });
                }
            });
        });

        // Manejador de eventos para editar
        $(document).on('click', '.edit-permission', function() {
            const id = $(this).data('id');
            $('#editPermissionModal').modal('show');
            $('#editPermissionModal #permissionId').val(id);

            const permissionName = $(this).closest('tr').find('td:eq(1)').text();
            $('#editPermissionModal #permissionName').val(permissionName);
        });

        $('#editPermissionModal #savePermissionChanges').on('click', function() {
            const id = $('#editPermissionModal #permissionId').val();
            const name = $('#editPermissionModal #permissionName').val();

            $.ajax({
                url: `${baseUrl}api/permissions/${id}`,
                type: 'PUT',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('auth_token')
                },
                data: {
                    name: name
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Permiso editado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('.datatables-permissions').DataTable().ajax.reload();
                        $('#editPermissionModal').modal('hide');
                    } else {
                        console.error('Error al actualizar el permiso:', response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error en la solicitud:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al actualizar el permiso'
                    });
                }
            });
        });
    }
});