<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Lab Management')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}">
    @stack('styles')
</head>
<body>

    <div id="app" class="layout-wrapper layout-content-navbar">
        @yield('content')
    </div>

    @if(session('success') || session('error') || $errors->any())
        <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
            <div id="flashToast" class="toast align-items-center text-bg-{{ session('success') ? 'success' : 'danger' }} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') ?? session('error') ?? $errors->first() }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @include('sneat.partials.scripts')
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('flashToast');
            if (toastEl && window.bootstrap) {
                var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
                toast.show();
            }
        });
    </script>
</body>
</html>
