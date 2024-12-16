@extends('layouts/layoutMaster')

@section('title', 'Adquisiciones - Formulario')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/typeahead-js/typeahead.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/js/form-validation-values.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite([ 'resources/assets/js/datatable-adquisition.js'])
@endsection

@section('content')
@role('Administrador')

    <h5 class="card-header">Plan Anual de Adquisiciones Jolan</h5>
    <div class="row mb-6">
        <div class="col-md mb-6 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <form class="acquisition-form-validation" action="{{ route('PlanAdquisicion.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- Nombre del Plan -->
                        <div class="row mb-6">
                            <div class="col-12">
                                <div class="mb-6">
                                    <label class="form-label" for="nombre-plan"><strong>Nombre del Plan de Adquisición</strong></label>
                                    <input type="text" class="form-control" id="nombre-plan" name="nombrePlan"
                                        placeholder="Ingrese el nombre del plan" required>
                                </div>
                            </div>
                        </div>

                        <!-- Versión -->
                        <div class="row mb-6">
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="version"><strong>Versión</strong></label>
                                    <input type="number" class="form-control" id="version" name="version" value="1"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="payment-mode"><strong>Modalidad de Pago</strong></label>
                                    <select class="form-select select2" id="payment-mode" name="modalidadPago" required>
                                        <option value="">Seleccione Modalidad de Pago</option>
                                        <option value="1">Pago Anticipado</option>
                                        <option value="2">Anticipo</option>
                                        <option value="3">Pago parcial contraentrega</option>
                                        <option value="4">Pago total contraentrega</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="budget"><strong>Presupuesto</strong></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="budget" name="presupuesto"
                                            min="0" placeholder="Monto" required>
                                        <select class="form-select" id="currency" name="currency" required
                                            style="max-width: 100px;">
                                            <option value="COP">COP</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TRM, CDP y RP -->
                        <div class="row mb-6">
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="trm"><strong>TRM Proyectado</strong></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="trm" name="trm"
                                            min="0" step="0.01" placeholder="TRM" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="cdp"><strong>CDP</strong></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="cdp" name="cdp"
                                            min="0" placeholder="Número CDP" required>
                                        <input type="text" class="form-control" id="cdp-date" readonly
                                            style="max-width: 120px;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="conversion"><strong>Conversión a COP</strong></label>
                                    <input type="text" class="form-control" id="conversion" name="conversion"
                                        placeholder="Conversión" readonly pattern="^[A-Za-z0-9]+$" required>
                                </div>
                            </div>
                        </div>
                        <!-- Código UNSPSC, Fecha de inicio y Modalidad de selección en tres columnas -->

                        <div class="row mb-6">
                            <div class="col-md-4">

                                <div class="mb-6">
                                    <label class="form-label" for="unspsc-code"><strong>Código UNSPSC</strong></label>
                                    <select class="form-select select2" id="unspsc-code" name="codigo_unspsc_id[]"
                                        multiple="multiple" required>
                                        <option value="">Seleccione Código</option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="rp"><strong>RP</strong></label>
                                    <input type="text" class="form-control" id="rp" name="rp"
                                        placeholder="Ingrese código RP" pattern="^[A-Za-z0-9]+$" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="selection-mode"><strong>Modalidad de Selección</strong></label>
                                    <select class="form-select select2" id="selection-mode" name="modalidad_seleccion_id"
                                        required>
                                        <option value="">Seleccione Modalidad</option>
                                        @foreach ($modalidades as $modalidad)
                                            <option value="{{ $modalidad->id }}">{{ $modalidad->codigo }} -
                                                {{ $modalidad->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Duración del contrato, Fuente de los recursos y Vigencias futuras en cuatro columnas -->
                        <div class="row mb-6">
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="start-date"><strong>Fecha de Inicio (Mes)</strong></label>
                                    <select class="form-select" id="start-date" name="mes_id" required>
                                        <option value="">Seleccione Mes</option>
                                        @foreach ($meses as $mes)
                                            <option value="{{ $mes->id }}">{{ $mes->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="contract-duration"><strong>Duración del Contrato</strong></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="contract-duration"
                                            name="duracionContrato" min="1" placeholder="Duración" required />
                                        <select class="form-select" id="duration-type" name="tipoDuracion">
                                            <option value="1">Días</option>
                                            <option value="2" selected>Meses</option>
                                            <option value="3">Años</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="resource-source"><strong>Fuente de los Recursos</strong></label>
                                    <select class="form-select" id="resource-source" name="fuenteRecursos" required>
                                        <option value="Recursos propios">Recursos propios</option>
                                        <option value="Presupuesto nacional">Presupuesto de entidad nacional</option>
                                        <option value="Regalías">Regalías</option>
                                        <option value="Recursos de crédito">Recursos de crédito</option>
                                        <option value="SGP">SGP</option>
                                        <option value="No Aplica">No Aplica</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="future-validity"><strong>¿Se requieren vigencias futuras?</strong></label>
                                    <select class="form-select" id="future-validity" name="vigencia" required>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Estado de solicitud de vigencias futuras, Unidad de contratación y Ubicación en tres columnas -->
                        <div class="row mb-6">
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="future-status"><strong>Estado de Solicitud</strong></label>
                                    <select class="form-select" id="future-status" name="estado" required>
                                        <option value="1">Aprobada</option>
                                        <option value="0">Pendiente</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="contract-unit"><strong>Unidad de Contratación (Referencia)</strong></label>
                                    <select class="form-select select2" id="contract-unit" name="unidadContratacion"
                                        required>
                                        <option value="0">Pendiente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="location"><strong>Ubicación</strong></label>
                                    <select class="form-select select2" id="location" name="ubicacion_id" required>
                                        <option value="">Seleccione Ubicación</option>
                                        @foreach ($ubicaciones as $ubicacion)
                                            <option value="{{ $ubicacion->id }}">{{ $ubicacion->codigo }} -
                                                {{ $ubicacion->ubicacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Datos del responsable en tres columnas -->
                        <div class="row mb-6">
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="responsible-name"><strong>Nombre del Responsable</strong></label>
                                    <input type="text" class="form-control" id="responsible-name"
                                        name="nombreResponsable" placeholder="Nombre completo" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="responsible-phone"><strong>Teléfono del Responsable</strong></label>
                                    <input type="tel" class="form-control" id="responsible-phone"
                                        name="telefonoResponsable" placeholder="Teléfono" pattern="[0-9]+" required />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-6">
                                    <label class="form-label" for="responsible-email"><strong>Correo Electrónico</strong></label>
                                    <input type="email" class="form-control" id="responsible-email"
                                        name="emailResponsable" placeholder="Correo electrónico" required />
                                </div>
                            </div>
                        </div>

                        <!-- Documentos Adjuntos -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Documentos Adjuntos</strong></label>
                                    <div class="file-upload-wrapper">
                                        <div class="file-upload-area border rounded p-2 mb-3">
                                            <div class="text-center py-2">
                                                <i class="bx bx-cloud-upload"></i>
                                                <span class="ms-2">Arrastre los archivos aquí o</span>
                                                <button type="button" class="btn btn-primary btn-sm browse-files ms-2">
                                                    <i class="bx bx-folder-open me-1"></i>Buscar Archivos
                                                </button>
                                                <small class="text-muted ms-2">(Máx: 10MB)</small>
                                            </div>
                                            <input type="file" class="file-upload-input" id="document-upload" multiple
                                                style="display: none;">
                                        </div>

                                        <div class="file-upload-list">
                                            <ul class="list-group" id="file-list">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notas Adicionales con menos espacio superior -->
                        <div class="row mb-6">
                            <div class="col-12">
                                <div class="mb-6">
                                    <label class="form-label" for="additional-notes"><strong>Notas Adicionales</strong></label>
                                    <textarea class="form-control" id="additional-notes" name="notasAdicionales" rows="3"
                                        placeholder="Ingrese cualquier información adicional relevante"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Botón de envío -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </div>
                    </form>
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
