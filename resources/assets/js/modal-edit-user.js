/**
 * Editar Usuario
 */

'use strict';

// Select2 (jquery)
$(function() {
    const select2 = $('.select2');

    // Select2 para Rol
    if (select2.length) {
        select2.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Seleccionar rol',
                dropdownParent: $this.parent()
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function(e) {
    (function() {
        // variables
        const modalEditUserTaxID = document.querySelector('.modal-edit-tax-id');
        const modalEditUserPhone = document.querySelector('.phone-number-mask');

        // Prefijo para ID fiscal
        if (modalEditUserTaxID) {
            new Cleave(modalEditUserTaxID, {
                prefix: 'TIN',
                blocks: [3, 3, 3, 4],
                uppercase: true
            });
        }

        // Máscara para número de teléfono
        if (modalEditUserPhone) {
            new Cleave(modalEditUserPhone, {
                phone: true,
                phoneRegionCode: 'ES'
            });
        }

        // Validación del formulario de edición de usuario
        FormValidation.formValidation(document.getElementById('editUserForm'), {
            fields: {
                modalEditUserFirstName: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your first name'
                        },
                        regexp: {
                            regexp: /^[a-zA-Zs]+$/,
                            message: 'The first name can only consist of alphabetical'
                        }
                    }
                },
                modalEditUserLastName: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your last name'
                        },
                        regexp: {
                            regexp: /^[a-zA-Zs]+$/,
                            message: 'The last name can only consist of alphabetical'
                        }
                    }
                },
                modalEditUserName: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your username'
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: 'The name must be more than 6 and less than 30 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9 ]+$/,
                            message: 'The name can only consist of alphabetical, number and space'
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
                    rowSelector: '.col-12'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            }
        });
    })();
});
