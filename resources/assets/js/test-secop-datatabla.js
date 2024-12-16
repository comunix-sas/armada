'use strict';

$(function () {
  let dt_secop_table = $('.datatables-secop');
  let loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
  let storedData = [];

  function loadData() {
    if (storedData.length === 0) {
      loadingModal.show(); // Mostrar modal de carga
      
      $.ajax({
        url: 'secop',
        type: 'GET',
        success: function(response) {
          storedData = response.data; // Acceder al array 'data' de la respuesta
          initializeDataTable();
          loadingModal.hide(); // Ocultar modal de carga
        },
        error: function(xhr, status, error) {
          console.error('Error al cargar los datos:', error);
          loadingModal.hide();
        }
      });
    } else {
      initializeDataTable();
    }
  }

  function initializeDataTable() {
    if (dt_secop_table.length) {
      const table = dt_secop_table.DataTable({
        destroy: true, // Destruir tabla existente si hay una
        processing: true,
        data: storedData,
        columns: [
          { 
            data: 'fecha_adjudicacion',
            title: 'Fecha de Adjudicación',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '';
            }
          },
          { data: 'fase', title: 'Fase' },
          { data: 'entidad', title: 'Entidad' },
          { data: 'nit_entidad', title: 'NIT Entidad' },
          { data: 'departamento_entidad', title: 'Departamento' },
          { data: 'ciudad_entidad', title: 'Ciudad' },
          { data: 'nombre_del_procedimiento', title: 'Nombre del Procedimiento' },
          { data: 'valor_total_adjudicacion', 
            title: 'Valor Total',
            render: function(data) {
              return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP'
              }).format(data);
            }
          },
          { data: 'nombre_del_proveedor', title: 'Proveedor' },
          { 
            data: 'urlproceso',
            title: 'Ver Proceso',
            render: function(data) {
              return `<a href="${data.url}" target="_blank" class="btn btn-sm btn-primary">Ver</a>`;
            }
          }
        ],
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        responsive: true,
        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        buttons: [
          {
            extend: 'collection',
            className: 'btn btn-label-primary dropdown-toggle me-2',
            text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Exportar</span>',
            buttons: [
              {
                extend: 'excel',
                text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                className: 'dropdown-item'
              },
              {
                extend: 'pdf',
                text: '<i class="ti ti-file-description me-1"></i>PDF',
                className: 'dropdown-item'
              },
              {
                extend: 'print',
                text: '<i class="ti ti-printer me-1"></i>Imprimir',
                className: 'dropdown-item'
              }
            ]
          }
        ]
      });
    }
  }

  loadData();
});