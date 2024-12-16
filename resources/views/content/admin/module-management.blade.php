@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Gestión de Módulos')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/module-management.js'])
@endsection

@section('content')
@role('Administrador')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Gestión de Módulos del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                            <tr>
                                <td>
                                    <span class="{{ $module['isMainMenu'] ? 'fw-bold' : '' }}">
                                        @isset($module['icon'])
                                            <i class="{{ $module['icon'] }} me-2"></i>
                                        @endisset
                                        {{ $module['name'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $module['disabled'] ? 'danger' : 'success' }}">
                                        {{ $module['disabled'] ? 'Deshabilitado' : 'Habilitado' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input
                                            class="form-check-input toggle-module"
                                            type="checkbox"
                                            data-module="{{ $module['name'] }}"
                                            data-csrf="{{ csrf_token() }}"
                                            {{ !$module['disabled'] ? 'checked' : '' }}
                                        >
                                    </div>
                                </td>
                            </tr>
                            @if(isset($module['submenu']))
                                @foreach($module['submenu'] as $submenu)
                                <tr>
                                    <td>
                                        <div class="ps-4">
                                            <i class="ti ti-corner-down-right me-2"></i>
                                            @isset($submenu['icon'])
                                                <i class="{{ $submenu['icon'] }} me-2"></i>
                                            @endisset
                                            <span>{{ $submenu['name'] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $submenu['disabled'] ? 'danger' : 'success' }}">
                                            {{ $submenu['disabled'] ? 'Deshabilitado' : 'Habilitado' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input toggle-module"
                                                type="checkbox"
                                                data-module="{{ $submenu['name'] }}"
                                                data-csrf="{{ csrf_token() }}"
                                                {{ !$submenu['disabled'] ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
