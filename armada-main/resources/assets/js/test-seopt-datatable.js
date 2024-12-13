'use strict';

$(function () {
  let dt_secop_table = $('.datatables-secop');
  let loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
  let storedData = [];  // Variable para almacenar los datos

  function loadData() {
    if (storedData.length === 0) {  // Cargar datos solo si no están ya almacenados
      $.ajax({
        url: 'secop',
        type: 'GET',
        success: function(data) {
          storedData = data;  // Almacenar los datos recibidos
          initializeDataTable();
        },
        error: function() {
          console.error('No se pudieron cargar los datos');
        }
      });
    } else {
      initializeDataTable();  // Inicializar la tabla con datos ya almacenados 
    }
  }

  function initializeDataTable() {
    if (dt_secop_table.length) {
      dt_secop_table.DataTable({
        processing: true,
        data: storedData,  // Usar los datos almacenados
        columns: [
          { data: 'fecha_adjudicacion', title: 'Fecha de Adjudicación' },
          { data: 'fase', title: 'Fase' },
          { data: 'entidad', title: 'Entidad' },
          { data: 'nit_entidad', title: 'NIT Entidad' },
          { data: 'departamento_entidad', title: 'Departamento' },
          { data: 'ciudad_entidad', title: 'Ciudad' },
          // Agrega más columnas según sea necesario
        ],
        // Otras configuraciones de DataTable...
      });
    }
  }

  loadData();  // Cargar los datos al iniciar
});