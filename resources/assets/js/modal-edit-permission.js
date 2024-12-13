/**
 * Edit Permission Modal JS
 */

'use strict';

// Edit permission form validation
document.addEventListener('DOMContentLoaded', function() {
    const element = document.querySelector('#editPermissionModal');
    if (element) {
        (function() {
            const editPermissionForm = document.getElementById('formEditPermission');
            const editPermissionNameInput = editPermissionForm.querySelector('input#permissionName');

            // Function to populate the form with the current permission data
            function populateEditForm(permission) {
                editPermissionNameInput.value = permission.name;
            }

            const fv = FormValidation.formValidation(editPermissionForm, {
                fields: {
                    editPermissionName: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter permission name'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        // Use this for enabling/changing valid/invalid class
                        // eleInvalidClass: '',
                        eleValidClass: '',
                        rowSelector: '.col-sm-9'
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                }
            });
        })();
    } else {
        console.error('El elemento no se encontró en el DOM');
    }
});

// Evento para abrir el modal de edición
$('#editPermissionModal').on('show.bs.modal', function(event) {
    const button = $(event.relatedTarget);
    const permissionId = button.data('id');

    if (!permissionId) {
        return;
    }

    // Establecer el ID del permiso en el campo oculto
    $('#permissionId').val(id);

    // Realizar la solicitud AJAX para obtener los datos del permiso
    $.ajax({
        url: `/api/permissions/${id}`,
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
        },
        success: function(response) {
            if (response.status === 'success') {
                populateEditForm(response.data);
            }
        },
        error: function(xhr) {
            console.error('Error fetching permission data:', xhr);
        }
    });
});