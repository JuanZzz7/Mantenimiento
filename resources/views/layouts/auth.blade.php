<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Acceso') — CMMS Industrial</title>
    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo"><i class="bi bi-gear-wide-connected"></i></div>
        <h4 class="text-center fw-700 mb-1" style="color:#e2e8f0;">CMMS Industrial</h4>
        <p class="text-center mb-4" style="color:#94a3b8; font-size:.85rem;">Sistema de Gestión de Mantenimiento</p>
        @yield('content')
    </div>
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
