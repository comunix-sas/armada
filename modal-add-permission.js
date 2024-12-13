document.addEventListener('DOMContentLoaded', function() {
    const addPermissionForm = document.querySelector('#addPermissionForm');

    if (typeof FormValidation !== 'undefined') {
        const fv = FormValidation.formValidation(addPermissionForm, {
            fields: {
                modalPermissionName: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor ingrese el nombre del permiso'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.col-12'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            }
        });
    }
});
