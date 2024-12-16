@extends('layouts/layoutMaster')

@section('title', 'Gestión de permisos')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/toastr/toastr.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/datatable-permissions.js', 'resources/assets/js/modal-add-permission.js', 'resources/assets/js/modal-edit-permission.js'])
@endsection

@section('content')
    @role('Administrador')
        <h5 class="card-header">Creación de permisos</h5>
        <!-- Permission Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-permissions table border-top">
                </table>
            </div>
        </div>
        <!--/ Permission Table -->
        <!-- Modal -->
        @include('_partials/_modals/modal-add-permission')
        @include('_partials/_modals/modal-edit-permission')
        <!-- /Modal -->
    @else
        <div class="alert alert-danger" role="alert">
            No tienes permisos para acceder a esta página.
        </div>
    @endrole
@endsection
