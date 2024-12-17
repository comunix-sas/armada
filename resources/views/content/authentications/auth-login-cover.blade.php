@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login Cover - Pages')

{{-- Archivos de estilos necesarios --}}
@section('vendor-style')
@vite([
'resources/css/app.scss', {{-- SCSS principal --}}
'resources/assets/vendor/libs/@form-validation/form-validation.scss' {{-- Validación de formularios --}}
])
@endsection

{{-- Archivos de estilo específicos de la página --}}
@section('page-style')
@vite(['resources/css/app.scss'])
@endsection

{{-- Archivos de scripts de librerías externas --}}
@section('vendor-script')
@vite([
'resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

{{-- Scripts específicos de la página --}}
@section('page-script')
@vite(['resources/assets/js/pages-auth.js'])
@endsection

{{-- Contenido principal --}}
@section('content')
<div class="authentication-wrapper authentication-cover">
    <!-- Logo -->
    <a href="{{url('/')}}" class="app-brand auth-cover-brand">
        <img src="{{ asset('assets/img/illustrations/logo-gesco.png') }}" alt="logo" style="height: 40px;">
        <span class="app-brand-text demo text-heading fw-bold"></span>
    </a>
    <!-- /Logo -->
    <div class="authentication-inner row m-0">
        <!-- Left Image -->
        <div class="d-none d-lg-flex col-lg-8 p-0">
            <div
                class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center position-relative">
                <img src="{{ asset('assets/img/illustrations/login-armada.png') }}" alt="Login Armada"
                    style="width: 100%; height: 100vh; object-fit: cover;">
                <!-- Nuevo logo -->
                <img src="{{ asset('assets/img/illustrations/logo-armada.png') }}" alt="logo"
                    class="position-absolute top-0 end-0 m-4" style="max-width: 150px;">
            </div>
        </div>
        <!-- Login Form -->
        <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-8 p-6">
            <div class="w-px-400 mx-auto mt-2 pt-2">
                <h2 class="mb-1 text-center">Bienvenidos a</h2>
                <div class="text-center">
                    <img src="{{ asset('assets/img/illustrations/gesco.png') }}" alt="logo" class="mb-4"
                        style="max-width: 200px;">
                </div>
                <p class="mb-6" style="line-height: 1.6; text-align: justify;">
                    Plataforma de Gestión Contractual de la Armada Nacional de Colombia, optimiza y
                    asegura la transparencia en la administración de contratos mediante procesos
                    automatizados, control centralizado y máxima seguridad.
                </p>
                <form id="formAuthentication" action="{{ url('login') }}" method="POST">
                    @csrf
                    @if($errors->has('auth'))
                    <div class="alert alert-danger mb-3">
                        {{ $errors->first('auth') }}
                    </div>
                    @endif
                    <div class="mb-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="text" class="form-control" id="email" name="email-username"
                            placeholder="Ingresa tu correo electrónico" value="{{ old('email-username') }}" autofocus>
                    </div>
                    <div class="mb-6 form-password-toggle">
                        <label class="form-label" for="password">Contraseña</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                        </div>
                    </div>
                    <button class="btn btn-primary d-grid w-100">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection