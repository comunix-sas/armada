'use strict';
import Swal from 'sweetalert2';

const STYLES = `
    .export-button-custom {
        font-size: 13px !important;
        padding: 4px 8px !important;
        line-height: 1 !important;
        height: 30px !important;
        min-height: 0 !important;
    }
    .export-button-custom i { font-size: 13px !important; }
    .dt-buttons { margin-bottom: 0 !important; }
`;

const API = {
  baseUrl: baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl,
  headers: () => ({
    Authorization: `Bearer ${localStorage.getItem('auth_token')}`
  })
};

const DATATABLE_CONFIG = {
  processing: true,
  serverSide: true,
  ajax: {
    url: `${API.baseUrl}/api/adquisitions`,
    type: 'GET',
    headers: API.headers(),
    dataSrc: 'data'
  },
  columns: [
    { data: '' },
    { data: 'nombrePlan', title: 'Nombre del Plan', searchable: true, orderable: true },
    { data: 'version', title: 'Versión', searchable: true, orderable: true },
    { data: 'modalidadPago', title: 'Modalidad de Pago' },
    { data: 'trm', title: 'TRM' },
    { data: 'presupuesto', title: 'Presupuesto' },
    { data: 'currency', title: 'Moneda' },
    { data: 'cdp', title: 'CDP' },
    { data: 'conversion', title: 'Conversión' },
    { data: 'rp', title: 'RP' },
    { data: 'duracionContrato', title: 'Duración del Contrato' },
    { data: 'tipoDuracion', title: 'Tipo de Duración' },
    { data: 'mes_id', title: 'Mes ID' },
    { data: 'modalidad_seleccion_id', title: 'Modalidad Selección ID' },
    { data: 'ubicacion_id', title: 'Ubicación ID' },
    {
      data: 'codigo_unspsc_id',
      title: 'Códigos UNSPSC',
      render: function (data, type, row) {
        if (type === 'display' && data) {
          if (data.length > 50) {
            return `<span title="${data}">${data.substring(0, 47)}...</span>`;
          }
          return data;
        }
        return data;
      }
    },
    { data: 'fuenteRecursos', title: 'Fuente de Recursos' },
    { data: 'vigencia', title: 'Vigencia' },
    { data: 'estado', title: 'Estado' },
    { data: 'unidadContratacion', title: 'Unidad de Contratación' },
    { data: 'nombreResponsable', title: 'Nombre del Responsable' },
    { data: 'telefonoResponsable', title: 'Teléfono del Responsable' },
    { data: 'emailResponsable', title: 'Email del Responsable' },
    { data: 'notasAdicionales', title: 'Notas Adicionales' },
    {
      data: null,
      title: 'Acciones',
      orderable: false,
      searchable: false,
      render: function (data, type, row) {
        return `
                            <button class="btn btn-sm btn-primary edit-adquisition" data-id="${row.idPlan}">Editar</button>
                            <button class="btn btn-sm btn-danger delete-adquisition" data-id="${row.idPlan}">Eliminar</button>
                        `;
      }
    }
  ],
  columnDefs: [
    {
      className: 'control',
      orderable: false,
      targets: 0,
      searchable: true,
      render: function (data, type, full, meta) {
        return '';
      }
    },
    { targets: '_all', searchable: false },
    { targets: [1, 19], searchable: true }
  ],
  order: [[0, 'asc']],
  dom:
    '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
    '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
    '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
    '>t' +
    '<"d-flex justify-content-between mx-2 row mb-1"' +
    '<"col-sm-12 col-md-6"i>' +
    '<"col-sm-12 col-md-6"p>' +
    '>',
  buttons: [
    {
      text: '<i class="ti ti-upload me-1"></i>Importar',
      className: 'btn btn-primary me-2',
      action: function () {
        $('#import-excel').click();
      }
    },
    {
      extend: 'collection',
      className: 'btn btn-primary dropdown-toggle',
      text: '<i class="ti ti-file-export me-1"></i>Exportar',
      buttons: [
        {
          extend: 'excel',
          text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
          className: 'dropdown-item',
          title: '',
          messageTop: null,
          exportOptions: {
            columns: ':not(:first-child):not(:last-child):not(.control)',
            orthogonal: 'export'
          }
        },
        {
          extend: 'pdf',
          text: '<i class="ti ti-file-pdf me-1"></i>PDF',
          className: 'dropdown-item',
          title: '',
          messageTop: null,
          exportOptions: {
            columns: ':not(:first-child):not(:last-child):not(.control)'
          }
        },
        {
          extend: 'print',
          text: '<i class="ti ti-printer me-1"></i>Imprimir',
          className: 'dropdown-item',
          title: '',
          messageTop: null,
          exportOptions: {
            columns: ':not(:first-child):not(:last-child):not(.control)'
          }
        }
      ]
    }
  ],
  responsive: {
    details: {
      display: $.fn.dataTable.Responsive.display.modal({
        header: function (row) {
          var data = row.data();
          return 'Detalles de ' + data['nombrePlan'];
        }
      }),
      type: 'column',
      renderer: function (api, rowIdx, columns) {
        var data = $.map(columns, function (col, i) {
          return col.title !== ''
            ? '<tr data-dt-row="' +
                col.rowIndex +
                '" data-dt-column="' +
                col.columnIndex +
                '">' +
                '<td>' +
                col.title +
                ':' +
                '</td> ' +
                '<td>' +
                col.data +
                '</td>' +
                '</tr>'
            : '';
        }).join('');

        return data ? $('<table class="table"/><tbody />').append(data) : false;
      }
    }
  },
  search: {
    smart: true
  },
  language: {
    search: 'Buscar:',
    searchPlaceholder: 'Buscar...',
    lengthMenu: 'Mostrar _MENU_ registros por página',
    zeroRecords: 'No se encontraron resultados',
    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty: 'Mostrando 0 a 0 de 0 registros',
    infoFiltered: '(filtrado de _MAX_ registros totales)',
    paginate: {
      first: 'Primero',
      previous: 'Anterior',
      next: 'Siguiente',
      last: 'Último'
    }
  }
};

const handleAjaxError = (error, title = 'Error') => {
  Swal.fire({
    icon: 'error',
    title,
    text: error.message || 'Ha ocurrido un error'
  });
};

const initializeDataTable = () => {
  const table = $('.dt-responsive');
  if (!table.length) return null;

  if ($.fn.DataTable.isDataTable(table)) {
    table.DataTable().destroy();
  }

  return table.DataTable(DATATABLE_CONFIG);
};

const setupExportButtons = dt => {
  const buttonContainer = dt.buttons().container();
  buttonContainer.appendTo('.dt-action-buttons');
  buttonContainer.html(`
            <div class="btn-group">
                <button class="btn btn-label-secondary dropdown-toggle waves-effect waves-light export-button-custom me-2" data-bs-toggle="dropdown">
                    <i class="ti ti-upload me-1"></i>Exportar
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item py-1" href="#" id="export-print"><i class="ti ti-printer me-1"></i>Imprimir</a></li>
                    <li><a class="dropdown-item py-1" href="#" id="export-csv"><i class="ti ti-file-text me-1"></i>CSV</a></li>
                    <li><a class="dropdown-item py-1" href="#" id="export-excel"><i class="ti ti-file-spreadsheet me-1"></i>Excel</a></li>
                    <li><a class="dropdown-item py-1" href="#" id="export-pdf"><i class="ti ti-file-code-2 me-1"></i>PDF</a></li>
                    <li><a class="dropdown-item py-1" href="#" id="export-copy"><i class="ti ti-copy me-1"></i>Copiar</a></li>
                </ul>
                <label for="import-excel" class="btn btn-label-primary waves-effect waves-light export-button-custom">
                    <i class="ti ti-download me-1"></i>Importar Excel
                </label>
                <input type="file" id="import-excel" accept=".xlsx,.xls" style="display: none;">
            </div>
        `);

  ['csv', 'excel', 'pdf', 'copy'].forEach(type => {
    $(`#export-${type}`).on('click', () => dt.button(`.buttons-${type}`).trigger());
  });
};

const handleImportExcel = dt => {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('file', file);

  $.ajax({
    url: `${API.baseUrl}/api/adquisitions/import`,
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    headers: API.headers(),
    success: response => {
      if (response.success) {
        Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: response.message
        });
        dt.ajax.reload();
      } else {
        // Manejo de errores...
      }
    },
    error: handleAjaxError,
    complete: () => $('#import-excel').val('')
  });
};

const handleDelete = (id, dt) => {
    $('.dtr-bs-modal').modal('hide');

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${API.baseUrl}/api/adquisitions/${id}`,
                type: 'DELETE',
                headers: API.headers(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: response.message
                    });
                    dt.ajax.reload(null, false);
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar la adquisición: ' + error
                    });
                }
            });
        }
    });
};

$(function () {
  const dt = initializeDataTable();
  if (!dt) return;

  setupExportButtons(dt);

  $('#import-excel').on('change', () => handleImportExcel(dt));

  $('.dataTables_filter input')
    .unbind()
    .bind('keyup', e => {
      clearTimeout(window.searchTimeout);
      window.searchTimeout = setTimeout(() => {
        dt.search($(e.target).val()).draw();
      }, 500);
    });

  $(document).on('click', '.delete-adquisition', function() {
    const id = $(this).data('id');
    handleDelete(id, dt);
  });

  $(document).on('click', '.edit-adquisition', function () {
    const id = $(this).data('id');

    // Cerrar la modal de detalles de DataTables
    $('.dtr-bs-modal').modal('hide');

    // Cerrar la modal de form validation
    $('.modal-form-validation').modal('hide');

    // Continuar con la apertura del modal de edición
    $('#editModal').modal('show');
  });

  window.adquisitionTable = dt;
});
