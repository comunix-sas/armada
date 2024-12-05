<!DOCTYPE html>
<html>
<head>
    <!-- ... otros elementos ... -->
    @yield('vendor-style')
    @yield('page-style')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- ... contenido ... -->

    <!-- Scripts al final del body -->
    @yield('vendor-script')
    @yield('page-script')
</body>
</html>
