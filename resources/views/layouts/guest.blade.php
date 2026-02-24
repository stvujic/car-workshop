<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Car Service') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #F5F7FA; color: #1E293B; }

        .navbar-custom { background-color: #0F172A; }
        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand { color: #fff !important; }
        .navbar-custom .nav-link:hover { color: #D4AF37 !important; }

        .btn-gold { background-color: #D4AF37; color: #0F172A; border: none; border-radius: 10px; font-weight: 600; }
        .btn-gold:hover { background-color: #c19d2e; color: #0F172A; }

        .btn-outline-gold { border: 1px solid #D4AF37; color: #D4AF37; background: transparent; border-radius: 10px; font-weight: 600; }
        .btn-outline-gold:hover { background-color: #D4AF37; color: #0F172A; }

        .card-custom { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.04); }
        .card-img-placeholder { height: 140px; background: linear-gradient(135deg, #0F172A, #1E293B); }

        input[type="email"],
        input[type="password"],
        input[type="text"],
        input[type="date"],
        select,
        textarea {
            border-radius: 10px !important;
            border: 1px solid rgba(15, 23, 42, 0.15) !important;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
            Car Service
        </a>

        <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
            Back to Home
        </a>
    </div>
</nav>

<section class="py-5">
    <div class="container" style="max-width: 520px;">
        <div class="card card-custom">
            <div class="card-img-placeholder"></div>

            <div class="card-body p-4">
                {{ $slot }}
            </div>
        </div>

        <div class="text-center text-muted small mt-4">
            © {{ date('Y') }} Car Service. All rights reserved.
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
