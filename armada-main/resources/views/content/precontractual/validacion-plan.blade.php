@extends('layouts/layoutMaster')

@section('title', 'Validación Plan - Formulario')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('content')
    @role('Administrador')
        <h5 class="card-header">Validación de Planes</h5>

        <!-- Agregamos las pestañas -->
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pendientes" aria-controls="navs-pendientes" aria-selected="true">
                        Planes Pendientes
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-historial" aria-controls="navs-historial" aria-selected="false">
                        Historial de Validaciones
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Contenido de la pestaña Planes Pendientes -->
                <div class="tab-pane fade show active" id="navs-pendientes" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre del Plan</th>
                                                    <th>Estado</th>
                                                    <th>Fecha Creación</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($planes->where('validacion.estado', 'pendiente')->merge($planes->whereNull('validacion')) as $plan)
                                                    <tr>
                                                        <td>{{ $plan->nombrePlan }}</td>
                                                        <td>
                                                            <span class="badge bg-warning">
                                                                {{ $plan->validacion ? ucfirst($plan->validacion->estado) : 'Pendiente' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $plan->created_at->format('d/m/Y') }}</td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm" onclick="mostrarDetalles({{ $plan->id }})">
                                                                Validar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido de la pestaña Historial -->
                <div class="tab-pane fade" id="navs-historial" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre del Plan</th>
                                                    <th>Estado</th>
                                                    <th>Fecha Validación</th>
                                                    <th>Observaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($planes->whereIn('validacion.estado', ['aprobado', 'rechazado']) as $plan)
                                                    <tr>
                                                        <td>{{ $plan->nombrePlan }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $plan->validacion->estado === 'aprobado' ? 'success' : 'danger' }}">
                                                                {{ ucfirst($plan->validacion->estado) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $plan->validacion->fecha_validacion->format('d/m/Y') }}</td>
                                                        <td>{{ $plan->validacion->observaciones ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
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

        <!-- Modal de Validación -->
        <div class="modal fade" id="validacionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Validar Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="validacionForm">
                            <input type="hidden" id="planId">
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select class="form-control" id="estado" name="estado">
                                    <option value="pendiente">Pendiente</option>
                                    <option value="aprobado">Aprobado</option>
                                    <option value="rechazado">Rechazado</option>
                                </select>
                            </div>
                            <div class="mb-3" id="observacionesContainer" style="display: none;">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="guardarValidacion()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            No tienes permisos para acceder a esta página.
        </div>
    @endrole

    @section('page-script')
        <script>
            const validacionModal = new bootstrap.Modal(document.getElementById('validacionModal'));

            document.getElementById('estado').addEventListener('change', function() {
                const observacionesContainer = document.getElementById('observacionesContainer');
                observacionesContainer.style.display = this.value === 'rechazado' ? 'block' : 'none';
            });

            function mostrarDetalles(planId) {
                document.getElementById('planId').value = planId;
                validacionModal.show();
            }

            function guardarValidacion() {
                const planId = document.getElementById('planId').value;
                const estado = document.getElementById('estado').value;
                const observaciones = document.getElementById('observaciones').value;

                fetch(`/validacion-plan/${planId}/validar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ estado, observaciones })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: data.message
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al validar el plan'
                    });
                });
            }
        </script>
    @endsection
@endsection
