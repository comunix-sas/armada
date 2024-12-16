@extends('layouts.layoutMaster')

@section('title', 'Planes Anuales Creados')
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/typeahead-js/typeahead.scss',
    'resources/assets/vendor/libs/tagify/tagify.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
    ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js',
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/typeahead-js/typeahead.js',
    'resources/assets/vendor/libs/tagify/tagify.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/js/form-validation-values.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
    ])
@endsection
@section('content')
<div class="container">
    <h1>Planes Anuales Creados</h1>
    <table id="planes-table" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Fecha de Creación</th>
                <!-- Añade más columnas según sea necesario -->
            </tr>
        </thead>
        <tbody>
            @foreach($planes as $plan)
            <tr>
                <td>{{ $plan->id }}</td>
                <td>{{ $plan->nombre }}</td>
                <td>{{ $plan->descripcion }}</td>
                <td>{{ $plan->created_at }}</td>
                <!-- Añade más datos según sea necesario -->
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@section('page-script')
<script>
    $(document).ready(function() {
        $('#planes-table').DataTable({
        });
    });
</script>
@endsection
