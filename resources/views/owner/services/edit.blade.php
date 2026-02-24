<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Service</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #F5F7FA;
            color: #1E293B;
        }

        .navbar-custom {
            background-color: #0F172A;
        }

        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: #fff !important;
        }

        .navbar-custom .nav-link:hover {
            color: #D4AF37 !important;
        }

        .btn-gold {
            background-color: #D4AF37;
            color: #0F172A;
            border: none;
        }

        .btn-gold:hover {
            background-color: #c19d2e;
            color: #0F172A;
        }

        .btn-outline-gold {
            border: 1px solid #D4AF37;
            color: #D4AF37;
            background: transparent;
        }

        .btn-outline-gold:hover {
            background-color: #D4AF37;
            color: #0F172A;
        }

        .section-title {
            font-weight: 800;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
        }

        .card-custom .card-header {
            background: #ffffff;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            font-weight: 700;
        }

        .footer-dark {
            background-color: #0F172A;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
            Car Service
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>

                @auth
                    @if(auth()->user()->role === 'owner')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('owner.bookings.index') }}">All bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('owner.myshops.index') }}">My shops</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <div class="d-flex gap-2 align-items-center">
                @auth
                    <span class="text-light small d-none d-lg-inline">
                        Hi, {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container" style="max-width: 900px;">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h1 class="section-title mb-1">Edit Service</h1>
                <p class="text-muted mb-0">
                    Workshop: <strong>{{ $workshop->name }}</strong>
                </p>
            </div>

            <a href="{{ route('owner.services.index', $workshop) }}"
               class="btn btn-outline-gold btn-sm px-3">
                Back to Services
            </a>
        </div>

        {{-- ERROR MESSAGES --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0">
                <div class="fw-semibold mb-1">Please fix:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-custom" style="max-width: 700px;">
            <div class="card-header">Service Details</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.services.update', [$workshop, $service]) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Service name</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control shadow-sm"
                                value="{{ old('name', $service->name) }}"
                                required
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Duration (minutes)</label>
                            <input
                                type="number"
                                name="duration_minutes"
                                class="form-control shadow-sm"
                                min="1"
                                value="{{ old('duration_minutes', $service->duration_minutes) }}"
                                required
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Price (EUR)</label>
                            <input
                                type="number"
                                name="price"
                                class="form-control shadow-sm"
                                step="0.01"
                                min="0"
                                value="{{ old('price', $service->price) }}"
                                required
                            >
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                        <button class="btn btn-gold px-4">
                            Save changes
                        </button>

                        <a href="{{ route('owner.services.index', $workshop) }}"
                           class="btn btn-outline-gold px-4">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<section class="py-4 footer-dark">
    <div class="container text-center">
        <div class="small opacity-75">
            © {{ date('Y') }} Car Service. All rights reserved.
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
