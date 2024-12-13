document.addEventListener('DOMContentLoaded', function () {
  const planSelect = document.getElementById('plan');
  if (!planSelect) return;

  function isPlanAlreadyAdded(selectedPlanText) {
    const items = document.querySelectorAll('#registeredPlansList li span');
    const existingPlans = Array.from(items).map(item => item.textContent);
    return existingPlans.includes(selectedPlanText);
  }

  planSelect.addEventListener('change', function () {
    if (this.selectedIndex > 0) {
      const selectedPlanText = this.options[this.selectedIndex].text;
      const selectedPlanId = this.options[this.selectedIndex].value;
      console.log('Plan seleccionado:', selectedPlanText, 'ID:', selectedPlanId);

      if (isPlanAlreadyAdded(selectedPlanText)) {
        const alertHtml = `
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            El plan "${selectedPlanText}" ya ha sido agregado
          </div>
        `;
        const alertElement = document.createElement('div');
        alertElement.innerHTML = alertHtml;
        planSelect.parentNode.insertBefore(alertElement, planSelect.nextSibling);

        setTimeout(() => {
          alertElement.remove();
        }, 3000);
      } else {
        const registeredPlansList = document.getElementById('registeredPlansList');
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        listItem.dataset.planId = selectedPlanId;
        console.log('Guardando plan ID:', selectedPlanId);

        const planText = document.createElement('span');
        planText.textContent = selectedPlanText;
        listItem.appendChild(planText);

        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'btn-group';

        const removeButton = document.createElement('button');
        removeButton.textContent = 'Eliminar';
        removeButton.className = 'btn btn-danger btn-sm';
        removeButton.addEventListener('click', function () {
          registeredPlansList.removeChild(listItem);
        });

        buttonContainer.appendChild(removeButton);
        listItem.appendChild(buttonContainer);
        registeredPlansList.appendChild(listItem);
      }

      this.selectedIndex = 0;
    }
  });

  const form = document.getElementById('precontractualForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const planesRegistrados = document.querySelectorAll('#registeredPlansList li');
      console.log('Planes encontrados:', planesRegistrados.length);

      if (planesRegistrados.length === 0) {
        Swal.fire({
          title: 'Error',
          text: 'Debe agregar al menos un plan a la lista',
          icon: 'error'
        });
        return;
      }

      const estudioPrevio = document.getElementById('estudioPrevio').files[0];
      if (!estudioPrevio) {
        Swal.fire({
          title: 'Error',
          text: 'Debe seleccionar un documento de estudio previo',
          icon: 'error'
        });
        return;
      }

      const formData = new FormData();
      formData.append('estudioPrevio', estudioPrevio);
      formData.append('estadoEstudio', document.getElementById('estadoEstudio').value);

      planesRegistrados.forEach(li => {
        const planId = li.dataset.planId;
        console.log('Agregando plan ID:', planId);
        if (planId) {
          formData.append('planes[]', planId);
        }
      });

      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }

      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch('/precontractual', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      })
      .then(response => {
        console.log('Status:', response.status);
        return response.json().then(data => {
          console.log('Respuesta del servidor:', data);
          if (!response.ok) {
            throw new Error(data.message || 'Error en la respuesta del servidor');
          }
          return data;
        });
      })
      .then(data => {
        if (data.success) {
          Swal.fire({
            title: 'Éxito',
            text: data.message,
            icon: 'success'
          }).then(() => {
            window.location.reload();
          });
        }
      })
      .catch(error => {
        console.error('Error completo:', error);
        Swal.fire({
          title: 'Error',
          text: error.message || 'Ocurrió un error al procesar la solicitud',
          icon: 'error'
        });
      });
    });
  }
});

function obtenerPlanesRegistrados() {
  const listaPlanes = document.getElementById('registeredPlansList');
  const planes = Array.from(listaPlanes.querySelectorAll('li')).map(li => {
    const planId = li.dataset.planId;
    console.log('Plan encontrado:', planId);
    return planId;
  });
  console.log('Total planes:', planes);
  return planes;
}
