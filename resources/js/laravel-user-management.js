'use strict';

$(function() {
    var dt_user_table = $('.datatables-users'),
        select2 = $('.select2'),
        userView = baseUrl + 'app/user/view/account',
        offCanvasForm = $('#offcanvasAddUser');

    if (select2.length) {
        var $this = select2;
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select Country',
            dropdownParent: $this.parent()
        });
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (dt_user_table.length) {
        var dt_user = dt_user_table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + 'user-list'
            },
            columns: [
                { data: 'checkbox', orderable: false, searchable: false, title: '<input type="checkbox" id="select-all">' },
                { data: 'id', title: 'Id', visible: false },
                { data: 'email', title: 'Correo' },
                { data: 'name', title: 'Usuario' },
                { data: 'role', title: 'Rol Asignado' },
                { data: 'action', title: 'Acciones' }
            ],
            columnDefs: [{
                    targets: 0,
                    render: function(data, type, full, meta) {
                        return `<input type="checkbox" class="user-checkbox" value="${full.id}">`;
                    }
                },
                {
                    // Usuario
                    targets: 1,
                    render: function(data, type, full, meta) {
                        return `<span>${full.name}</span>`;
                    }
                },
                {
                    // Correo
                    targets: 2,
                    render: function(data, type, full, meta) {
                        return `<span class="user-email">${full.email}</span>`;
                    }
                },
                {
                    // Rol del usuario
                    targets: 3,
                    className: 'text-center',
                    render: function(data, type, full, meta) {
                        var $name = full['name'];

                        // For Avatar badge
                        var stateNum = Math.floor(Math.random() * 6);
                        var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                        var $state = states[stateNum],
                            $name = full['name'],
                            $initials = $name.match(/\b\w/g) || [],
                            $output;
                        $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                        $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';

                        // Creates full output for row
                        var $row_output =
                            '<div class="d-flex justify-content-start align-items-center user-name">' +
                            '<div class="avatar-wrapper">' +
                            '<div class="avatar avatar-sm me-4">' +
                            $output +
                            '</div>' +
                            '</div>' +
                            '<div class="d-flex flex-column">' +
                            '<a href="' +
                            userView +
                            '" class="text-heading text-truncate"><span class="fw-medium">' +
                            $name +
                            '</span></a>' +
                            '</div>' +
                            '</div>';
                        return $row_output;
                    }
                },

                {
                    // Rol del usuario
                    targets: 4,
                    className: 'text-center',
                    render: function(data, type, full, meta) {
                        var $role = full['role'];
                        return `<span>${$role}</span>`;
                    }
                },
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return (
                            '<div class="d-flex align-items-center gap-50">' +
                            `<button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><i class="ti ti-edit"></i></button>` +
                            `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}"><i class="ti ti-trash"></i></button>` +
                            `<button class="btn btn-sm btn-icon reset-password btn-text-warning rounded-pill waves-effect" data-id="${full['id']}" data-email="${full['email']}"><i class="ti ti-key"></i></button>` +
                            '<button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>' +
                            '<div class="dropdown-menu dropdown-menu-end m-0">' +
                            '<a href="' +
                            userView +
                            '" class="dropdown-item">View</a>' +
                            '<a href="javascript:;" class="dropdown-item">Suspend</a>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                }
            ],
            order: [
                [2, 'desc']
            ],
            dom: '<"row"' +
                '<"col-md-2"<"ms-n2"l>>' +
                '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0"fB>>' +
                '>t' +
                '<"row"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            lengthMenu: [10, 20, 50, 70, 100],
            language: {
                sLengthMenu: 'Mostrar _MENU_ registros por página',
                search: '',
                searchPlaceholder: 'Buscar Usuario',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                paginate: {
                    next: '<i class="ti ti-chevron-right ti-sm"></i>',
                    previous: '<i class="ti ti-chevron-left ti-sm"></i>'
                }
            },
            // Buttons with Dropdown
            buttons: [{
                    extend: 'collection',
                    className: 'btn btn-label-secondary dropdown-toggle mx-4 waves-effect waves-light',
                    text: '<i class="ti ti-upload me-2 ti-xs"></i>Exportar',
                    buttons: [{
                            extend: 'print',
                            title: 'Usuarios',
                            text: '<i class="ti ti-printer me-2" ></i>Imprimir',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be print
                                format: {
                                    body: function(inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function(index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            },
                            customize: function(win) {
                                //customize print view for dark
                                $(win.document.body)
                                    .css('color', config.colors.headingColor)
                                    .css('border-color', config.colors.borderColor)
                                    .css('background-color', config.colors.body);
                                $(win.document.body)
                                    .find('table')
                                    .addClass('compact')
                                    .css('color', 'inherit')
                                    .css('border-color', 'inherit')
                                    .css('background-color', 'inherit');
                            }
                        },
                        {
                            extend: 'csv',
                            title: 'Usuarios',
                            text: '<i class="ti ti-file-text me-2" ></i>Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function(inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function(index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'Usuarios',
                            text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function(inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function(index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'Usuarios',
                            text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function(inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function(index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'copy',
                            title: 'Usuarios',
                            text: '<i class="ti ti-copy me-2" ></i>Copiar',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function(inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function(index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        }
                    ]
                },
                {
                    text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Agregar Nuevo Usuario</span>',
                    className: 'add-new btn btn-primary waves-effect waves-light',
                    attr: {
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#offcanvasAddUser'
                    }
                }
            ],
            // For responsive popup
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Detalles de ' + data['name'];
                        }
                    }),
                    type: 'column',
                    renderer: function(api, rowIdx, columns) {
                        var data = $.map(columns, function(col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ?
                                '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>' :
                                '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            }
        });
    }

    // Delete Record
    $(document).on('click', '.delete-record', function() {
        var user_id = $(this).data('id'),
            dtrModal = $('.dtr-bs-modal.show');

        // hide responsive modal in small screen
        if (dtrModal.length) {
            dtrModal.modal('hide');
        }

        // sweetalert for confirmation of delete
        Swal.fire({
            title: 'Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            customClass: {
                popup: 'swal2-popup swal2-modal swal2-icon-warning swal2-show',
                icon: 'swal2-icon swal2-warning swal2-icon-show',
                actions: 'swal2-actions'
            },
            buttonsStyling: true
        }).then(function(result) {
            if (result.value) {
                // delete the data
                $.ajax({
                    type: 'DELETE',
                    url: `${baseUrl}user-list/${user_id}`,
                    success: function() {
                        dt_user.draw();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

                // success sweetalert
                Swal.fire({
                    icon: 'success',
                    title: 'Eliminado!',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'El usuario no fue eliminado!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            }
        });
    });

    // edit record
    $(document).on('click', '.edit-record', function() {
        var user_id = $(this).data('id'),
            dtrModal = $('.dtr-bs-modal.show');

        // hide responsive modal in small screen
        if (dtrModal.length) {
            dtrModal.modal('hide');
        }

        // changing the title of offcanvas
        $('#offcanvasAddUserLabel').html('Edit User');

        // get data
        $.get(`${baseUrl}user-list\/${user_id}\/edit`, function(data) {
            $('#user_id').val(data.id);
            $('#add-user-fullname').val(data.name);
            $('#add-user-email').val(data.email);
        });
    });

    // changing the title
    $('.add-new').on('click', function() {
        $('#user_id').val(''); //reseting input field
        $('#offcanvasAddUserLabel').html('Add User');
    });

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);

    // validating form and updating user's data
    const addNewUserForm = document.getElementById('addNewUserForm');

    // user form validation
    const fv = FormValidation.formValidation(addNewUserForm, {
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'Por favor ingrese el nombre completo'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Por favor ingrese su correo'
                    },
                    emailAddress: {
                        message: 'El valor no es una dirección de correo válida'
                    }
                }
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                // Use this for enabling/changing valid/invalid class
                eleValidClass: '',
                rowSelector: function(field, ele) {
                    // field is the field name & ele is the field element
                    return '.mb-6';
                }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            // Submit the form when all fields are valid
            // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        }
    }).on('core.form.valid', function() {
        // adding or updating user when form successfully validate
        $.ajax({
            data: $('#addNewUserForm').serialize(),
            url: `${baseUrl}user-list`,
            type: 'POST',
            success: function(status) {
                dt_user.draw();
                offCanvasForm.offcanvas('hide');

                // sweetalert
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: `Usuario ${status.message} exitosamente.`,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            },
            error: function(err) {
                offCanvasForm.offcanvas('hide');
                Swal.fire({
                    title: 'Entrada duplicada!',
                    text: 'El correo debe ser único.',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            }
        });
    });

    // clearing form data when offcanvas hidden
    offCanvasForm.on('hidden.bs.offcanvas', function() {
        fv.resetForm(true);
    });


    // Ejemplo de cómo mostrar una notificación de éxito al editar un usuario
    function showSuccessMessage(message) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: message,
            showConfirmButton: false,
            timer: 1500
        });
    }

    // Manejador de eventos para la edición de usuario
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        const userId = $('#user_id').val();
        const userName = $('#add-user-fullname').val();
        const userEmail = $('#add-user-email').val();
        const userRole = $('#user-role').val();

        $.ajax({
            url: `/api/users/${userId}`,
            type: 'PUT',
            data: {
                name: userName,
                email: userEmail,
                role: userRole,
                role_id: userRole

            },
            success: function(response) {
                if (response.status === 'success') {
                    showSuccessMessage('Usuario editado correctamente');
                    // Actualizar la tabla o realizar otras acciones necesarias
                } else {
                    console.error('Error al editar el usuario:', response.message);
                }
            },
            error: function(xhr) {
                console.error('Error en la solicitud:', xhr);
            }
        });
    });

    // Manejador de eventos para la eliminación de usuario
    $(document).on('click', '.delete-user', function() {
        const userId = $(this).data('id');

        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            $.ajax({
                url: `/api/users/${userId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.status === 'success') {
                        showSuccessMessage('Usuario eliminado correctamente');
                        // Actualizar la tabla o realizar otras acciones necesarias
                    } else {
                        console.error('Error al eliminar el usuario:', response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error en la solicitud:', xhr);
                }
            });
        }
    });

    // Manejador para reset de contraseña
    $(document).on('click', '.reset-password', function() {
        const userEmail = $(this).data('email');

        Swal.fire({
            title: '¿Enviar correo de restauración?',
            text: `Se enviará un correo a ${userEmail} con las instrucciones para restaurar la contraseña`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '/forgot-password',
                    type: 'POST',
                    data: {
                        email: userEmail,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Correo enviado!',
                                text: response.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON ? xhr.responseJSON.message : 'Ocurrió un error al enviar el correo',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }
                });
            }
        });
    });

    // Seleccionar todos los checkboxes
    $('#select-all').on('click', function() {
        var rows = dt_user.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    // Eliminar usuarios seleccionados
    $('#delete-selected').on('click', function() {
        var selectedIds = [];
        $('.user-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: `${baseUrl}user-list/delete-multiple`,
                        data: { ids: selectedIds },
                        success: function(response) {
                            dt_user.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado!',
                                text: 'Los usuarios seleccionados han sido eliminados.',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        },
                        error: function(error) {
                            console.error('Error al eliminar usuarios:', error);
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'No hay usuarios seleccionados',
                text: 'Por favor, selecciona al menos un usuario para eliminar.',
                icon: 'info',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        }
    });
});
