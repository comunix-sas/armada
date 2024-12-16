@extends('layouts/layoutMaster')

@section('title', 'Adquisición - Planes de adquisición')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/js/datatable-adquisition.js'])
@endsection

@section('content')
@role('Administrador')

<h5 class="card-header">Planes de Adquisición Creados</h5>
<!-- Tabla existente -->
<div class="card">
    <div class="card-datatable table-responsive">
        <table class="dt-responsive table">
            <thead>
                <!-- Aquí se cargarán los encabezados de la tabla -->
            </thead>
        </table>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Plan de Adquisición</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Código UNSPSC</label>
                            <input type="text" class="form-control" id="codigo_unspsc_id" name="codigo_unspsc_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Estimada</label>
                            <input type="date" class="form-control" id="fecha_estimada" name="fecha_estimada">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Duración Estimada</label>
                            <input type="text" class="form-control" id="duracion_estimada" name="duracion_estimada">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Modalidad de Selección</label>
                            <select class="form-select" id="modalidad_seleccion" name="modalidad_seleccion">
                                <option value="">Seleccione...</option>
                                <option value="Licitación">Licitación Pública</option>
                                <option value="Contratación Directa">Contratación Directa</option>
                                <option value="Mínima Cuantía">Mínima Cuantía</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fuente de Recursos</label>
                            <input type="text" class="form-control" id="fuente_recursos" name="fuente_recursos">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Valor Total Estimado</label>
                            <input type="number" class="form-control" id="valor_total_estimado" name="valor_total_estimado">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor Estimado Vigencia</label>
                            <input type="number" class="form-control" id="valor_estimado_vigencia" name="valor_estimado_vigencia">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Estado Solicitud</label>
                            <select class="form-select" id="estado_solicitud" name="estado_solicitud">
                                <option value="">Seleccione...</option>
                                <option value="En Proceso">En Proceso</option>
                                <option value="Aprobado">Aprobado</option>
                                <option value="Rechazado">Rechazado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

@else
<div class="alert alert-danger" role="alert">
    No tienes permisos para acceder a esta página.
</div>
@endrole
@endsection
