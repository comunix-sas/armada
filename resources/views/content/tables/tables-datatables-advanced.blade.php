@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Adquisición - Planes de adquisición')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
        'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
        'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
        'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
        'resources/assets/vendor/libs/select2/select2.scss',
        'resources/assets/vendor/libs/@form-validation/form-validation.scss',
        'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
    ])
@endsection

@section('vendor-script')
    @vite([
        'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
        'resources/assets/vendor/libs/select2/select2.js',
        'resources/assets/vendor/libs/@form-validation/popular.js',
        'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
        'resources/assets/vendor/libs/@form-validation/auto-focus.js',
        'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
    ])
@endsection

@section('page-script')
    @vite([
        'resources/assets/js/datatable-adquisition.js',
        'resources/assets/js/datatable-adquisition-edit.js'
    ])
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
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Plan de Adquisición</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" class="acquisition-form-validation">
                    @csrf
                    <!-- Nombre del Plan y Versión -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label" for="edit-nombre-plan">Nombre del Plan de Adquisición</label>
                            <input type="text" class="form-control" id="edit-nombre-plan" name="nombrePlan" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-version">Versión</label>
                            <input type="number" class="form-control" id="edit-version" name="version" readonly>
                        </div>
                    </div>

                    <!-- Modalidad de Pago y Presupuesto -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="edit-payment-mode">Modalidad de Pago</label>
                            <select class="form-select select2" id="edit-payment-mode" name="modalidadPago" required>
                                <option value="">Seleccione Modalidad de Pago</option>
                                <option value="1">Pago Anticipado</option>
                                <option value="2">Anticipo</option>
                                <option value="3">Pago parcial contraentrega</option>
                                <option value="4">Pago total contraentrega</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-budget">Presupuesto</label>
                            <input type="number" class="form-control" id="edit-budget" name="presupuesto" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-currency">Moneda</label>
                            <select class="form-select" id="edit-currency" name="currency" required>
                                <option value="COP">COP</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                    </div>

                    <!-- TRM, CDP, Conversión y RP -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label" for="edit-trm">TRM</label>
                            <input type="number" class="form-control" id="edit-trm" name="trm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="edit-cdp">CDP</label>
                            <input type="number" class="form-control" id="edit-cdp" name="cdp" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="edit-conversion">Conversión</label>
                            <input type="number" class="form-control" id="edit-conversion" name="conversion" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="edit-rp">RP</label>
                            <input type="number" class="form-control" id="edit-rp" name="rp" required>
                        </div>
                    </div>

                    <!-- Códigos UNSPSC -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label" for="edit-unspsc-code">Códigos UNSPSC</label>
                            <select class="form-select select2" id="edit-unspsc-code" name="codigo_unspsc_id[]" multiple required>
                            </select>
                        </div>
                    </div>

                    <!-- Modalidad, Mes y Duración -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="edit-modalidad-seleccion">Modalidad de Selección</label>
                            <select class="form-select select2" id="edit-modalidad-seleccion" name="modalidad_seleccion_id" disabled>
                                <option value="">Seleccione modalidad</option>
                                <option value="1">Licitación Pública</option>
                                <option value="2">Selección Abreviada</option>
                                <option value="3">Concurso de Méritos</option>
                                <option value="4">Contratación Directa</option>
                                <option value="5">Mínima Cuantía</option>
                                <option value="6">Asociación Público Privada</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-mes">Mes</label>
                            <select class="form-select select2" id="edit-mes" name="mes_id" required>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="edit-duracion-contrato">Duración Contrato</label>
                            <input type="number" class="form-control" id="edit-duracion-contrato" name="duracionContrato" required min="1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="edit-tipo-duracion">Tipo Duración</label>
                            <select class="form-select" id="edit-tipo-duracion" name="tipoDuracion" required>
                                <option value="1">Días</option>
                                <option value="2">Meses</option>
                                <option value="3">Años</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fuente de Recursos y Estados -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="edit-fuente-recursos">Fuente de Recursos</label>
                            <input type="text" class="form-control" id="edit-fuente-recursos" name="fuenteRecursos" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="edit-vigencia">Vigencia</label>
                            <select class="form-select" id="edit-vigencia" name="vigencia" required>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="edit-estado">Estado</label>
                            <select class="form-select" id="edit-estado" name="estado" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-ubicacion">Ubicación</label>
                            <select class="form-select select2" id="edit-ubicacion" name="ubicacion_id" required>
                            </select>
                        </div>
                    </div>

                    <!-- Datos del Responsable (Deshabilitados) -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="edit-responsible-name">Nombre del Responsable</label>
                            <input type="text" class="form-control" id="edit-responsible-name" name="nombreResponsable" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-responsible-phone">Teléfono del Responsable</label>
                            <input type="tel" class="form-control" id="edit-responsible-phone" name="telefonoResponsable" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-responsible-email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="edit-responsible-email" name="emailResponsable" disabled>
                        </div>
                    </div>

                    <!-- Notas Adicionales -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label" for="edit-additional-notes">Notas Adicionales</label>
                            <textarea class="form-control" id="edit-additional-notes" name="notasAdicionales" rows="3"></textarea>
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
