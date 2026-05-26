<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Acceso') — AutoSoft</title>
    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo-wrapper" style="display:flex; flex-direction:column; align-items:center; margin-bottom:1.5rem;">
            <img src="{{ asset('assets/autosoft-logo.png') }}"
                 alt="AutoSoft"
                 style="height:80px; width:80px; object-fit:contain; border-radius:16px; background:#ffffff; padding:8px; box-shadow: 0 8px 32px rgba(0,102,255,0.25), 0 2px 8px rgba(0,0,0,0.3);">
            <div style="font-size:1.5rem; font-weight:800; color:#e2e8f0; letter-spacing:-0.03em; margin-top:0.75rem; line-height:1;">AutoSoft</div>
            <p style="color:#94a3b8; font-size:.82rem; margin-top:0.3rem; margin-bottom:0;">Sistema de Gestión de Mantenimiento</p>
        </div>
        @yield('content')
    </div>
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
