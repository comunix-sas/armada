document.addEventListener('DOMContentLoaded', function() {
    cargarTablaValidacion();

    const estadoEstudio = document.getElementById('estadoEstudio');
    const notaAdicionalContainer = document.getElementById('notaAdicionalContainer');
    const notaAdicional = document.getElementById('notaAdicional');

    estadoEstudio.addEventListener('change', function() {
        if (estadoEstudio.value === 'rechazado') {
            notaAdicionalContainer.style.display = 'block';
            notaAdicional.setAttribute('required', 'required');
        } else {
            notaAdicionalContainer.style.display = 'none';
            notaAdicional.removeAttribute('required');
        }
    });

    window.cargarDatosPlan = function(id) {
        fetch(`/precontractual/planes-validacion-id/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    actualizarFormularioConDatosDelPlan(data.data);
                }
            })
            .catch(error => {
                console.error('Error al cargar los datos del plan:', error);
                alert('Error al cargar los datos del plan');
            });
    };
});

function cargarTablaValidacion() {
    fetch('/precontractual/planes-validacion')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#validacionPlanesTable tbody');
            tbody.innerHTML = '';
            data.data.forEach(plan => {
                tbody.appendChild(crearFilaDeTabla(plan));
            });
        })
        .catch(error => {
            console.error('Error al cargar la tabla de validación:', error);
        });
}

function crearFilaDeTabla(plan) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${plan.nombrePlan}</td>
        <td><span class="badge bg-${getEstadoClass(plan.estado)}">${plan.estado}</span></td>
        <td>${plan.fechaInicio}</td>
        <td>${plan.ultimaActualizacion}</td>
        <td>
            <a href="${plan.documentoUrl}" class="btn btn-sm btn-info">
                <i class="ti ti-file-download"></i>
            </a>
        </td>
        <td>
            <button class="btn btn-sm btn-primary" onclick="cargarDatosPlan(${plan.id})">
                <i class="ti ti-eye"></i>
            </button>
        </td>
    `;
    return tr;
}

function getEstadoClass(estado) {
    const classes = {
        'pendiente': 'warning',
        'en_revision': 'info',
        'aprobado': 'success',
        'rechazado': 'danger'
    };
    return classes[estado] || 'secondary';
}

function actualizarFormularioConDatosDelPlan(plan) {
    document.getElementById('nombrePlan').value = plan.nombrePlan;
    document.getElementById('estadoEstudio').value = plan.estado;
    const docContainer = document.getElementById('documentoActual');
    docContainer.innerHTML = plan.documentoUrl ? 
        `<a href="${plan.documentoUrl}" target="_blank" class="btn btn-sm btn-outline-primary">Ver documento actual</a>` : 
        '<p class="text-muted">Sin documento</p>';
    actualizarHistorial(plan.historial);
    document.getElementById('editarPlanForm').setAttribute('action', `/precontractual/${plan.id}`);
    new bootstrap.Modal(document.getElementById('editarPlanModal')).show();
}

function actualizarHistorial(historial) {
    const historialBody = document.getElementById('historialCambios');
    historialBody.innerHTML = historial && historial.length ? 
        historial.map(h => `
            <tr>
                <td>${h.fecha_cambio || '-'}</td>
                <td>${h.estado_nuevo || '-'}</td>
                <td>${h.usuario || '-'}</td
                <td>${h.comentarios || '-'}</td>
            </tr>
        `).join('') :
        '<tr><td colspan="4" class="text-center">No hay historial disponible</td></tr>';
}
