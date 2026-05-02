<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'My-Kuku-Soko')</title>

        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- AdminLTE -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

        <style>
            :root {
                --primary-green: #2e7d32;
                --light-green: #66bb6a;
                --dark-green: #1b5e20;
            }

            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                font-family: 'Source Sans Pro', sans-serif;
                min-height: 100vh;
            }

            .auth-card {
                background: white;
                border-radius: 15px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 500px;
                overflow: hidden;
            }

            .auth-header {
                background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
                color: white;
                padding: 30px 20px;
                text-align: center;
            }

            .auth-body {
                padding: 30px;
            }

            .btn-success {
                background-color: var(--primary-green);
                border-color: var(--primary-green);
            }
        </style>
    </head>

    <body class="d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        @stack('scripts')
    </body>
</html>
