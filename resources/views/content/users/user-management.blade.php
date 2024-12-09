@extends('layouts/layoutMaster')

@section('title', 'User Management - Crud App')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/js/laravel-user-management.js'])
@endsection

@section('content')

    @role('Administrador')
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Usuarios Totales</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $totalUsers }}</h4>
                                </div>
                                <small class="mb-0">Recent analytics</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-user-check ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Usuarios Duplicados</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $userDuplicates }}</h4>
                                    <p class="text-danger mb-0">(0%)</p>
                                </div>
                                <small class="mb-0">Análisis recientes</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="ti ti-users ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <h5 class="card-title mb-0">Creación de Usuarios</h5>

        <div class="card">
            <div class="card-header border-bottom">
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table">
                    <thead class="border-top">
                        <tr>
                            <th></th>
                            <th>Id</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Usuarios Totales</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new user -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Agregar Usuario</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-user pt-0" id="addNewUserForm">
                        <input type="hidden" name="id" id="user_id">
                        <div class="mb-6">
                            <label class="form-label" for="add-user-fullname">Nombre Completo</label>
                            <input type="text" class="form-control" id="add-user-fullname" placeholder="Juan Pérez"
                                name="name" aria-label="Juan Pérez" />
                        </div>
                        <div class="mb-6">
                            <label class="form-label" for="add-user-email">Correo Electrónico</label>
                            <input type="text" id="add-user-email" class="form-control" placeholder="juan.perez@ejemplo.com"
                                aria-label="juan.perez@ejemplo.com" name="email" />
                        </div>

                        <div class="mb-6">
                            <label class="form-label" for="user-role">Rol de Usuario</label>
                            <select id="user-role" name="role_id" class="select2 form-select form-select-lg"
                                data-allow-clear="true">
                                <option value="">Seleccionar Rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-auto position-absolute bottom-0 start-50 translate-middle-x pb-4 d-flex gap-4">
                            <button type="submit" class="btn btn-primary px-4">Registrar</button>
                            <button type="reset" class="btn btn-label-danger px-4"
                                data-bs-dismiss="offcanvas">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            No tienes permisos para acceder a esta página.
        </div>
    @endrole
@endsection
