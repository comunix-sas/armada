@extends('layouts/layoutMaster')

@section('title', 'Pre-Contractual - Formulario')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/typeahead-js/typeahead.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['resources/js/planes-precontractual.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const estadoEstudio = document.getElementById('estadoEstudio');
            const notaAdicionalContainer = document.getElementById('notaAdicionalContainer');
            const notaAdicional = document.getElementById('notaAdicional');

            estadoEstudio.addEventListener('change', function() {
                if (estadoEstudio.value === 'rechazado') {
                    notaAdicionalContainer.style.display = 'block';
                    notaAdicional.setAttribute('required', 'required');
                } else {
                    notaAdicionalContainer.style.display = 'none';
                    notaAdicional.removeAttribute('required');
                }
            });

            // Cargar tabla de validación
            function cargarTablaValidacion() {
                fetch('/precontractual/planes-validacion')
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.querySelector('#validacionPlanesTable tbody');
                        tbody.innerHTML = '';

                        data.data.forEach(plan => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${plan.nombrePlan}</td>
                                <td><span class="badge bg-${getEstadoClass(plan.estado)}">${plan.estado}</span></td>
                                <td>${plan.fechaInicio}</td>
                                <td>${plan.ultimaActualizacion}</td>
                                <td>
                                    <a href="/storage/${plan.documentoPath}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="ti ti-file-download"></i>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="verDetalles(${plan.id})">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    });
            }

            function getEstadoClass(estado) {
                const classes = {
                    'pendiente': 'warning',
                    'en_revision': 'info',
                    'aprobado': 'success',
                    'rechazado': 'danger'
                };
                return classes[estado] || 'secondary';
            }
            cargarTablaValidacion();
        });
    </script>
@endsection

@section('content')
    @role('Administrador')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Gestión Pre-Contractual</h5>
            </div>

            <!-- Navegación de pestañas -->
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item">
                    <button
                        class="nav-link active"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-registro"
                        role="tab"
                        aria-selected="true">
                        <i class='ti ti-file-plus me-1'></i>
                        Registro de Planes
                    </button>
                </li>
                <li class="nav-item">
                    <button
                        class="nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-seguimiento"
                        role="tab"
                        aria-selected="false">
                        <i class='ti ti-timeline me-1'></i>
                        Validación de Planes
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">
                <!-- Pestaña de Registro -->
                <div class="tab-pane fade show active" id="tab-registro" role="tabpanel">
                    <div class="row p-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form id="precontractualForm" class="precontractual-form-validation"
                                        action="{{ route('precontractual.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="plan">Seleccionar Plan</label>
                                                    <select class="form-control" id="plan" name="plan">
                                                        <option value="" disabled selected>Seleccione un plan</option>
                                                        @foreach ($planes as $plan)
                                                            <option value="{{ $plan->idPlan }}">{{ $plan->nombrePlan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2">
                                            <h6>Planes Registrados</h6>
                                            <ul id="registeredPlansList" class="list-group"></ul>
                                        </div>

                                        <div class="mt-4">
                                            <h6>Estudio Previo</h6>
                                            <div class="mb-3">
                                                <label class="form-label">Documento Estudio Previo</label>
                                                <input type="file" class="form-control" id="estudioPrevio" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Estado</label>
                                                <select class="form-control" id="estadoEstudio">
                                                    <option value="pendiente">Pendiente</option>
     
                                                </select>
                                            </div>
                                            <div class="mb-3" id="notaAdicionalContainer" style="display: none;">
                                                <label class="form-label">Nota Adicional</label>
                                                <textarea class="form-control" id="notaAdicional" name="nota_adicional"></textarea>
                                            </div>
                                        </div>

                                        <div class="mt-4 text-end">
                                            <button type="submit" class="btn btn-primary">Registrar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña de Seguimiento -->
                <div class="tab-pane fade" id="tab-seguimiento" role="tabpanel">
                    <div class="row p-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="validacionPlanesTable">
                                            <thead>
                                                <tr>
                                                    <th>Plan</th>
                                                    <th>Estado</th>
                                                    <th>Fecha Inicio</th>
                                                    <th>Última Actualización</th>
                                                    <th>Documento</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Se llenará dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mantener los modales existentes -->
        <!-- ... código de los modales ... -->

    @else
        <div class="alert alert-danger" role="alert">
            No tienes permisos para acceder a esta página.
        </div>
    @endrole
@endsection
