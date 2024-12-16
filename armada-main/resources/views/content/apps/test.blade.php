@extends('layouts/layoutMaster')

@section('title', 'Procesos SECOP II - ARMADA')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
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
@vite(['resources/assets/js/test-seopt-datatable.js'])
@endsection

@section('content')
<!-- Modal de Carga -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary me-2" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <h5 class="mt-3">Cargando datos...</h5>
                <p class="mb-0">Por favor espere mientras se cargan los procesos de SECOP II</p>
            </div>
        </div>
    </div>
</div>

<!-- Contenido Principal -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Procesos SECOP II - ARMADA</h5>
        <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
            <div class="col-md-12">
                <input type="text" id="busqueda-general" class="form-control" placeholder="Búsqueda general...">
            </div>
        </div>
    </div>
    <div class="card-datatable table-responsive">
        <table class="datatables-secop table border-top">
            <thead>
                <tr>
                    <th>Entidad</th>
                    <th>NIT Entidad</th>
                    <th>Departamento</th>
                    <th>Ciudad</th>
                    <th>Orden Entidad</th>
                    <th>Código PCI</th>
                    <th>ID Proceso</th>
                    <th>Referencia</th>
                    <th>PPI</th>
                    <th>Nombre Procedimiento</th>
                    <th>Descripción</th>
                    <th>Fase</th>
                    <th>Fecha Publicación</th>
                    <th>Precio Base</th>
                    <th>Modalidad</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    <th>Adjudicado</th>
                    <th>Valor Adjudicación</th>
                    <th>Proveedor</th>
                    <th>NIT Proveedor</th>
                    <th>Tipo Contrato</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
