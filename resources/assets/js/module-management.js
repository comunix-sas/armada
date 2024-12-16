document.querySelectorAll('.toggle-module').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const moduleName = this.dataset.module;
        const disabled = !this.checked;
<<<<<<< HEAD
        const toggleElement = this;

        toggleElement.disabled = true;
=======
>>>>>>> 3af5bb94e25c38e132d553ce4754c1aa0976097a

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
<<<<<<< HEAD
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
=======
                    // Recargar la página para ver los cambios
                    window.location.reload();
>>>>>>> 3af5bb94e25c38e132d553ce4754c1aa0976097a
                }
            })
            .catch(error => {
                console.error('Error:', error);
<<<<<<< HEAD
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
=======
                this.checked = !this.checked; // Revertir el cambio en caso de error
>>>>>>> 3af5bb94e25c38e132d553ce4754c1aa0976097a
            });
    });
});
