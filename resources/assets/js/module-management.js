document.querySelectorAll('.toggle-module').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const moduleName = this.dataset.module;
        const disabled = !this.checked;

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
                    // Recargar la página para ver los cambios
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked; // Revertir el cambio en caso de error
            });
    });
});
