@extends('layouts/layoutMaster')

@section('title', 'Roles - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
<style>
    .table-container {
        max-height: 70vh;
        overflow: auto;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background: white;
        position: relative;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
    }

    .modern-table th,
    .modern-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        white-space: nowrap;
    }

    .modern-table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #444;
        border-bottom: 2px solid #dee2e6;
        z-index: 2;
    }

    /* Estilos para los filtros */
    .filter-input {
        width: calc(100% - 10px);
        padding: 6px;
        margin: 4px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    /* Estilos para filas */
    .modern-table tbody tr:hover {
        background-color: #f5f5f5;
    }

    /* Paginación moderna */
    .pagination-container {
        margin: 20px 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .pagination-button {
        padding: 8px 16px;
        border: none;
        background: #f8f9fa;
        color: #444;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .pagination-button:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }

    .pagination-button.active {
        background: #696cff;
        color: white;
    }

    .pagination-info {
        color: #6c757d;
        font-weight: 500;
    }

    /* Gradiente para indicar scroll */
    .table-container::after {
        content: '';
        position: sticky;
        right: 0;
        top: 0;
        height: 100%;
        width: 40px;
        background: linear-gradient(to left, white, transparent);
        pointer-events: none;
    }

    /* Estilos para el estado de carga */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Optimización de rendimiento */
    .table-container {
        contain: content;
        will-change: transform;
    }

    .modern-table tbody tr {
        contain: content;
    }
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/datatable-roles.js',
  'resources/assets/js/modal-add-role.js',
])
@endsection

@section('content')
<div class="table-container">
    <table class="modern-table">
        <thead>
            <tr>
                <th>Entidad<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>NIT Entidad<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Departamento<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Ciudad<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Orden Entidad<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Código PCI<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>ID del Proceso<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Referencia del Proceso<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>PPI<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>ID del Portafolio<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Nombre del Procedimiento<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Descripción del Procedimiento<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Fase<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Fecha de Publicación<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Precio Base<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Modalidad de Contratación<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Duración<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Unidad de Duración<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Estado del Procedimiento<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Adjudicado<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Valor Total Adjudicación<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Nombre del Adjudicador<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>Nombre del Proveedor<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
                <th>URL del Proceso<br><input type="text" class="filter-input" placeholder="Buscar..."></th>
            </tr>
        </thead>
        <tbody id="data-body">
            <!-- Los datos se cargarán aquí mediante JavaScript -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemsPerPage = 100;
    let currentPage = 1;
    let totalRecords = 0;
    let lastSearchQuery = '';
    const baseUrl = 'https://www.datos.gov.co/resource/p6dx-8zbt.json';
    const searchCache = new Map();
    let isLoading = false;

    // Función para mostrar estado de carga
    function toggleLoadingState(loading) {
        isLoading = loading;
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loading) {
            loadingIndicator.style.display = 'block';
        } else {
            loadingIndicator.style.display = 'none';
        }
    }

    // Obtener total de registros
    async function getTotalRecords(searchParams = '') {
        try {
            let url = `${baseUrl}?$select=count(*)`;
            if (searchParams) {
                url += `&$where=${searchParams}`;
            }
            const response = await fetch(url);
            const data = await response.json();
            return parseInt(data[0].count);
        } catch (error) {
            console.error('Error al obtener total de registros:', error);
            return 0;
        }
    }

    // Función optimizada para cargar datos
    async function loadDataWithOffset(offset, searchParams = '') {
        const cacheKey = `${offset}-${searchParams}`;

        if (searchCache.has(cacheKey)) {
            return searchCache.get(cacheKey);
        }

        try {
            toggleLoadingState(true);
            let url = `${baseUrl}?$limit=${itemsPerPage}&$offset=${offset}`;
            if (searchParams) {
                url += `&$where=${searchParams}`;
            }
            const response = await fetch(url);
            const data = await response.json();
            searchCache.set(cacheKey, data);
            return data;
        } catch (error) {
            console.error('Error al cargar los datos:', error);
            return [];
        } finally {
            toggleLoadingState(false);
        }
    }

    // Función optimizada para renderizar tabla
    function renderTable(data) {
        const tbody = document.getElementById('data-body');
        const fragment = document.createDocumentFragment();

        data.forEach(dato => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${dato.entidad || ''}</td>
                <td>${dato.nit_entidad || ''}</td>
                <td>${dato.departamento_entidad || ''}</td>
                <td>${dato.ciudad_entidad || ''}</td>
                <td>${dato.ordenentidad || ''}</td>
                <td>${dato.codigo_pci || ''}</td>
                <td>${dato.id_del_proceso || ''}</td>
                <td>${dato.referencia_del_proceso || ''}</td>
                <td>${dato.ppi || ''}</td>
                <td>${dato.id_del_portafolio || ''}</td>
                <td>${dato.nombre_del_procedimiento || ''}</td>
                <td>${dato.descripcion_del_procedimiento || ''}</td>
                <td>${dato.fase || ''}</td>
                <td>${dato.fecha_de_publicacion_del || ''}</td>
                <td>${dato.precio_base || ''}</td>
                <td>${dato.modalidad_de_contratacion || ''}</td>
                <td>${dato.duracion || ''}</td>
                <td>${dato.unidad_de_duracion || ''}</td>
                <td>${dato.estado_del_procedimiento || ''}</td>
                <td>${dato.adjudicado || ''}</td>
                <td>${dato.valor_total_adjudicacion || ''}</td>
                <td>${dato.nombre_del_adjudicador || ''}</td>
                <td>${dato.nombre_del_proveedor || ''}</td>
                <td><a href="${dato.urlproceso?.url || '#'}" target="_blank">Ver Proceso</a></td>
            `;
            fragment.appendChild(row);
        });

        tbody.innerHTML = '';
        tbody.appendChild(fragment);
    }

    // Función para actualizar la paginación
    async function setupPagination(total) {
        const totalPages = Math.ceil(total / itemsPerPage);
        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination-container';

        paginationContainer.innerHTML = `
            <button class="pagination-button" onclick="changePage('prev')" ${currentPage === 1 ? 'disabled' : ''}>
                &laquo;
            </button>
            <span class="pagination-info">
                Página ${currentPage} de ${totalPages}
                (Registros ${(currentPage-1)*itemsPerPage + 1} - ${Math.min(currentPage*itemsPerPage, total)}
                de ${total})
            </span>
            <button class="pagination-button" onclick="changePage('next')" ${currentPage === totalPages ? 'disabled' : ''}>
                &raquo;
            </button>
        `;

        const existingPagination = document.querySelector('.pagination-container');
        if (existingPagination) {
            existingPagination.remove();
        }
        document.querySelector('.table-container').after(paginationContainer);
    }

    // Función para cambiar de página
    window.changePage = async function(action) {
        const totalPages = Math.ceil(totalRecords / itemsPerPage);

        if (action === 'prev' && currentPage > 1) {
            currentPage--;
        } else if (action === 'next' && currentPage < totalPages) {
            currentPage++;
        }

        const offset = (currentPage - 1) * itemsPerPage;
        const data = await loadDataWithOffset(offset, lastSearchQuery);
        renderTable(data);
        setupPagination(totalRecords);
    }

    // Debounce para búsquedas
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Función optimizada de búsqueda
    const performSearch = debounce(async function(searchTerm, column) {
        if (searchTerm === lastSearchQuery) return;

        currentPage = 1;
        const searchParams = searchTerm.length > 0 ?
            `lower(${column}) like '%25${searchTerm.toLowerCase()}%25'` : '';
        lastSearchQuery = searchParams;

        try {
            totalRecords = await getTotalRecords(searchParams);
            const data = await loadDataWithOffset(0, searchParams);
            renderTable(data);
            setupPagination(totalRecords);
        } catch (error) {
            console.error('Error en la búsqueda:', error);
        }
    }, 500);

    // Manejador de filtros optimizado
    const filterInputs = document.querySelectorAll('.filter-input');
    filterInputs.forEach((input, index) => {
        const columns = ['entidad', 'nit_entidad'];
        input.addEventListener('input', function() {
            if (!isLoading) {
                performSearch(this.value, columns[index]);
            }
        });
    });

    // Limpieza de cache periódica
    setInterval(() => {
        searchCache.clear();
    }, 300000);

    // Inicialización
    async function initialize() {
        try {
            toggleLoadingState(true);
            totalRecords = await getTotalRecords();
            const initialData = await loadDataWithOffset(0);
            renderTable(initialData);
            setupPagination(totalRecords);
        } catch (error) {
            console.error('Error en la inicialización:', error);
        } finally {
            toggleLoadingState(false);
        }
    }

    // Agregar indicador de carga
    const loadingHtml = `
        <div id="loading-indicator" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', loadingHtml);

    initialize();
});
</script>
@endsection
