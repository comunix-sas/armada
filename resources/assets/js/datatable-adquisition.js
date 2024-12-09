'use strict';
import Swal from 'sweetalert2';

const styleSheet = document.createElement('style');
styleSheet.textContent = `
    .export-button-custom {
        font-size: 13px !important;
        padding: 4px 8px !important;
        line-height: 1 !important;
        height: 30px !important;
        min-height: 0 !important;
    }
    .export-button-custom i {
        font-size: 13px !important;
    }
    .dt-buttons {
        margin-bottom: 0 !important;
    }
`;
document.head.appendChild(styleSheet);

let adquisitionTable;

$(function () {
  const dt_responsive_table = $('.dt-responsive');

  if (dt_responsive_table.length) {
    if ($.fn.DataTable.isDataTable(dt_responsive_table)) {
      dt_responsive_table.DataTable().destroy();
    }

    const dt_adquisition = dt_responsive_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'api/adquisitions',
        type: 'GET',
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('auth_token')
        },
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
              // Si el texto es muy largo, lo cortamos y añadimos tooltip
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
          // Botón de importar
          text: '<i class="ti ti-upload me-1"></i>Importar',
          className: 'btn btn-primary me-2',
          action: function () {
            $('#import-excel').click();
          }
        },
        {
          // Botón de exportar
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
    });

    // Agregar botón de exportación con menú desplegable
    dt_adquisition.buttons().container().appendTo('.dt-action-buttons');

    dt_adquisition.buttons().container().html(`
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

    // Configurar acciones de exportación
    $('#export-csv').on('click', function () {
      dt_adquisition.button('.buttons-csv').trigger();
    });

    $('#export-excel').on('click', function () {
      dt_adquisition.button('.buttons-excel').trigger();
    });

    $('#export-pdf').on('click', function () {
      dt_adquisition.button('.buttons-pdf').trigger();
    });

    $('#export-copy').on('click', function () {
      dt_adquisition.button('.buttons-copy').trigger();
    });

    // Agregar el evento para importar Excel
    $('#import-excel').on('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const formData = new FormData();
        formData.append('file', file);

        $.ajax({
          url: `${baseUrl}api/adquisitions/import`,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('auth_token')
          },
          success: function (response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: response.message
              });
              dt_adquisition.ajax.reload();
            } else {
              // Si hay errores específicos, mostrarlos
              if (response.errors && response.errors.length > 0) {
                let errorMessage = '<ul style="text-align: left; margin-top: 10px;">';
                response.errors.forEach(error => {
                  errorMessage += `<li>${error}</li>`;
                });
                errorMessage += '</ul>';

                Swal.fire({
                  icon: 'warning',
                  title: 'Errores en la importación',
                  html: `${response.message}${errorMessage}`,
                  confirmButtonText: 'Entendido'
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: response.message
                });
              }
            }
            $('#import-excel').val(''); // Limpiar el input file
          },
          error: function (xhr) {
            let errorMessage = 'Error al procesar el archivo';
            let errorDetails = '';

            if (xhr.responseJSON) {
              errorMessage = xhr.responseJSON.message;
              if (xhr.responseJSON.errors && xhr.responseJSON.errors.length > 0) {
                errorDetails =
                  '<ul style="text-align: left; margin-top: 10px;">' +
                  xhr.responseJSON.errors.map(error => `<li>${error}</li>`).join('') +
                  '</ul>';
              }
            }

            Swal.fire({
              icon: 'error',
              title: 'Error de Validación',
              html: errorMessage + errorDetails,
              confirmButtonText: 'Entendido'
            });

            $('#import-excel').val('');
          }
        });
      }
    });

    // Inicialización del DataTable
    adquisitionTable = dt_adquisition;

    $(document).on('click', '.delete-adquisition', function () {
      const id = $(this).data('id');

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
          const cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.slice(0, -1) : baseUrl;

          $.ajax({
            url: `${cleanBaseUrl}/api/adquisitions/${id}`,
            type: 'DELETE',
            headers: {
              Authorization: 'Bearer ' + localStorage.getItem('auth_token')
            },
            success: function (response) {
              // Mostrar mensaje de éxito
              Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: response.message
              });

              adquisitionTable.ajax.reload(null, false);
            },
            error: function (xhr, status, error) {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar la adquisición: ' + error
              });
            }
          });
        }
      });
    });

    $(document).on('click', '.edit-adquisition', function () {
      const id = $(this).data('id');
      $('#editModal').modal('show');
      $('#editForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
          url: `${baseUrl}api/adquisitions/${id}`,
          type: 'PUT',
          data: formData,
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('auth_token')
          },
          success: function (response) {
            Swal.fire({
              icon: 'success',
              title: 'Actualizado',
              text: response.message
            });
            dt_adquisition.ajax.reload();
            $('#editModal').modal('hide');
          },
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'No se pudo actualizar la adquisición'
            });
          }
        });
      });
    });

    // Agregar el evento de búsqueda en tiempo real
    $('.dataTables_filter input')
      .unbind()
      .bind('keyup', function (e) {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(function () {
          dt_adquisition.search($(e.target).val()).draw();
        }, 500);
      });
  }
});
