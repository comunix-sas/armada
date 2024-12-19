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
          { data: 'entidad', title: 'Entidad' },
          { data: 'nit_entidad', title: 'NIT Entidad' },
          { data: 'departamento_entidad', title: 'Departamento' },
          { data: 'ciudad_entidad', title: 'Ciudad' },
          { data: 'ordenentidad', title: 'Orden Entidad' },
          { data: 'codigo_pci', title: 'Código PCI' },
          { data: 'id_del_proceso', title: 'ID Proceso' },
          { data: 'referencia_del_proceso', title: 'Referencia' },
          { data: 'ppi', title: 'PPI' },
          { data: 'nombre_del_procedimiento', title: 'Nombre Procedimiento' },
          { data: 'descripci_n_del_procedimiento', title: 'Descripción' },
          { data: 'fase', title: 'Fase' },
          { 
            data: 'fecha_de_publicacion_del',
            title: 'Fecha Publicación',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '';
            }
          },
          { 
            data: 'precio_base',
            title: 'Precio Base',
            render: function(data) {
              return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP'
              }).format(data);
            }
          },
          { data: 'modalidad_de_contratacion', title: 'Modalidad' },
          { data: 'duracion', title: 'Duración' },
          { data: 'estado_del_procedimiento', title: 'Estado' },
          { data: 'adjudicado', title: 'Adjudicado' },
          { 
            data: 'valor_total_adjudicacion',
            title: 'Valor Adjudicación',
            render: function(data) {
              return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP'
              }).format(data);
            }
          },
          { data: 'nombre_del_proveedor', title: 'Proveedor' },
          { data: 'nit_del_proveedor_adjudicado', title: 'NIT Proveedor' },
          { data: 'tipo_de_contrato', title: 'Tipo Contrato' },
          { 
            data: 'urlproceso.url', 
            title: 'Acciones',
            render: function(data) {
              return `<a href="${data}" target="_blank" class="btn btn-sm btn-primary">Ver</a>`;
            }
          }
        ]
      });
    }
  }

  loadData();
});
