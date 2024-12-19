let dataPlna = '';

document.addEventListener('DOMContentLoaded', function () {
    cargarTablaValidacion();

    const estadoEstudio = document.getElementById('estadoEstudio');
    const notaAdicionalContainer = document.getElementById('notaAdicionalContainer');
    const notaAdicional = document.getElementById('notaAdicional');

    estadoEstudio.addEventListener('change', function () {
        if (estadoEstudio.value === 'rechazado') {
            notaAdicionalContainer.style.display = 'block';
            notaAdicional.setAttribute('required', 'required');
        } else {
            notaAdicionalContainer.style.display = 'none';
            notaAdicional.removeAttribute('required');
        }
    });

    window.cargarDatosPlan = function (id) {
        fetch(`/precontractual/planes-validacion-id/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    dataPlna = data.data;  // Assign the URL to dataPlna
                    // Trigger the event to start fetching data
                    const event = new CustomEvent('dataPlnaReady');
                    document.dispatchEvent(event);
                    actualizarFormularioConDatosDelPlan(data.data);
                }
            })
            .catch(error => {
                console.error('Error al cargar los datos del plan:', error);
                alert('Error al cargar los datos del plan');
            });
    };

    // Simulate calling cargarDatosPlan to set dataPlna
      // Replace with the correct ID

    // Wait for dataPlna to be ready before making the fetch
    document.addEventListener('dataPlnaReady', function () {
        var urlD = dataPlna;
        const searchQuery = dataPlna.codigoSecop; // Supongamos que los filtros están en un objeto 'filters'
    
        fetch(urlD.ulr) // Cambié 'ulr' a 'url'
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#apiDataTable tbody');
    
                // Filtrar los datos buscando en todos los campos
                const filteredData = data.filter(item => {
                    // Convertimos el objeto 'item' a un arreglo de sus valores
                    return Object.values(item).some(value => {
                        // Verificamos si el valor contiene la búsqueda, ignorando mayúsculas y minúsculas
                        return String(value).toLowerCase().includes(searchQuery.toLowerCase());
                    });
                });
    
                // Recorrer los datos filtrados y agregar filas a la tabla
                filteredData.forEach(item => {
                    const row = document.createElement('tr');
    
                    row.innerHTML = `
                        <td>${item.entidad}</td>
                        <td>${item.nit_entidad}</td>
                        <td>${item.departamento_entidad}</td>
                        <td>${item.ciudad_entidad}</td>
                        <td>${item.ordenentidad}</td>
                        <td>${item.referencia_del_proceso}</td>
                        <td>${item.nombre_del_procedimiento}</td>
                        <td>${item.descripci_n_del_procedimiento}</td>
                        <td>${item.fase}</td>
                        <td>${item.fecha_de_publicacion}</td>
                        <td>${item.precio_base}</td>
                        <td>${item.modalidad_de_contratacion}</td>
                        <td>${item.justificacion_modalidad}</td>
                        <td>${item.duracion}</td>
                        <td>${item.unidad_de_duracion}</td>
                        <td>${item.estado_del_procedimiento}</td>
                        <td>${item.adjudicado}</td>
                        <td>${item.nombre_del_proveedor}</td>
                        <td>${item.nit_del_proveedor_adjudicado}</td>
                        <td>${item.valor_total_adjudicacion}</td>
                        <td>${item.nombre_del_adjudicador}</td>
                        <td><a href="${item.url_proceso}" target="_blank">Ver Proceso</a></td>
                    `;
    
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    });
    
    
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
