/**
 * Add Permission Modal JS
 */

'use strict';

document.addEventListener('DOMContentLoaded', function() {

    const addPermissionForm = document.getElementById('addPermissionForm');

    if (!addPermissionForm) {
        console.error('No se encontró el formulario');
        return;
    }

    // Remover el atributo onsubmit del formulario si existe
    addPermissionForm.removeAttribute('onsubmit');

    // Obtener el botón submit
    const submitButton = addPermissionForm.querySelector('button[type="submit"]');

    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            e.preventDefault();

            // Obtener el valor del campo
            const permissionName = document.getElementById('modalPermissionName').value.trim();

            if (!permissionName) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre del permiso es requerido',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
                return;
            }

            // Realizar la petición fetch
            fetch(`${baseUrl}api/permissions`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: permissionName,
                    guard_name: 'web'
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {

                // Cerrar modal
                const modal = document.getElementById('addPermissionModal');
                const modalInstance = bootstrap.Modal.getInstance(modal);

                // Limpiar formulario
                addPermissionForm.reset();

                // Cerrar modal
                if (modalInstance) {
                    modalInstance.hide();
                }

                // Recargar tabla
                if ($.fn.DataTable.isDataTable('.datatables-permissions')) {
                    $('.datatables-permissions').DataTable().ajax.reload();
                }

                // Mostrar mensaje de éxito con SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '¡Permiso creado exitosamente!',
                    showConfirmButton: false,
                    timer: 1500,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al crear el permiso',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            });
        });
    }
});
