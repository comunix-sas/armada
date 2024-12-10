@extends('layouts/layoutMaster')

@section('title', 'Permisos - Apps')

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
@vite([
    'resources/assets/js/datatable-permissions.js',
    'resources/assets/js/modal-add-permission.js'
])
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-permissions table border-top">
                </table>
            </div>
        </div>
    </div>
</div>

@include('_partials/_modals/modal-add-permission')
@endsection 
