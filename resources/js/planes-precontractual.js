document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan');
    if (!planSelect) return;

    planSelect.addEventListener('change', function() {
        const selectedPlanText = this.options[this.selectedIndex].text;
        const selectedPlanId = this.value;

        if (selectedPlanText !== "Seleccione un plan") {
            const registeredPlansList = document.getElementById('registeredPlansList');
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';

            // Contenedor para el texto del plan
            const planText = document.createElement('span');
            planText.textContent = selectedPlanText;
            listItem.appendChild(planText);

            // Contenedor para los botones
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'btn-group';

            // Botón de detalles
            const detailsButton = document.createElement('button');
            detailsButton.textContent = 'Detalles';
            detailsButton.className = 'btn btn-info btn-sm me-2';
            detailsButton.addEventListener('click', function() {
                fetch(`/planes/${selectedPlanId}/detalles`)
                    .then(response => response.json())
                    .then(data => {
                        const detailsHtml = `
                            <p><strong>Nombre del Plan:</strong> ${data.nombrePlan}</p>
                            <p><strong>Descripción:</strong> ${data.descripcion}</p>
                            <p><strong>Precio:</strong> ${data.precio}</p>
                        `;
                        document.getElementById('planDetailsContent').innerHTML = detailsHtml;

                        const modal = new bootstrap.Modal(document.getElementById('planDetailsModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Botón de eliminar
            const removeButton = document.createElement('button');
            removeButton.textContent = 'Eliminar';
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.addEventListener('click', function() {
                registeredPlansList.removeChild(listItem);
            });

            // Agregar botones al contenedor
            buttonContainer.appendChild(detailsButton);
            buttonContainer.appendChild(removeButton);
            listItem.appendChild(buttonContainer);
            registeredPlansList.appendChild(listItem);
        }
    });
});
