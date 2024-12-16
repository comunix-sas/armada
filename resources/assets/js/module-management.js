document.querySelectorAll('.toggle-module').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const moduleName = this.dataset.module;
        const disabled = !this.checked;
        const toggleElement = this;

        toggleElement.disabled = true;

        fetch('/admin/module-management/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    moduleName: moduleName,
                    disabled: disabled
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Estado del módulo actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error al actualizar el módulo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toggleElement.checked = !toggleElement.checked;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al actualizar el estado del módulo',
                    showConfirmButton: true
                });
            })
            .finally(() => {
                toggleElement.disabled = false;
            });
    });
});
