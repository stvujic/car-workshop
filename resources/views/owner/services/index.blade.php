<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Services – {{ $workshop->name }}</title>

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
            transition: 0.3s ease;
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
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
    <div class="container">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h1 class="section-title mb-1">Services</h1>
                <p class="text-muted mb-0">
                    Workshop: <strong>{{ $workshop->name }}</strong>
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('owner.myshops.index') }}" class="btn btn-outline-gold btn-sm px-3">
                    Back to My Shops
                </a>

                <a href="{{ route('owner.services.create', $workshop) }}" class="btn btn-gold btn-sm px-3">
                    Add Service
                </a>
            </div>
        </div>

        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger shadow-sm border-0">
                {{ session('error') }}
            </div>
        @endif

        {{-- SERVICES LIST --}}
        @if($services->count() === 0)
            <div class="card card-custom p-4 text-center">
                <div class="fw-semibold">No services added for this workshop yet.</div>
                <div class="text-muted small mt-1">Add your first service so customers can book it.</div>
                <div class="mt-3">
                    <a href="{{ route('owner.services.create', $workshop) }}" class="btn btn-gold btn-sm px-4">
                        Add Service
                    </a>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($services as $service)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card card-custom h-100 shadow-sm">
                            <div class="card-body p-4">

                                <div class="mb-3">
                                    <div class="fw-semibold fs-5 mb-1">
                                        {{ $service->name }}
                                    </div>

                                    <div class="text-muted small">
                                        Duration: {{ $service->duration_minutes }} min
                                        <span class="mx-2">•</span>
                                        Price: €{{ number_format($service->price, 2) }}
                                    </div>
                                </div>

                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('owner.services.edit', [$workshop, $service]) }}"
                                       class="btn btn-outline-gold btn-sm px-3">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('owner.services.destroy', [$workshop, $service]) }}"
                                          onsubmit="return confirm('Delete this service?');">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-outline-danger btn-sm px-3">
                                            Delete
                                        </button>
                                    </form>
                                </div>

                                <hr class="my-4">

                                <div class="text-muted small">
                                    Tip: Keep prices and duration realistic to reduce cancellations.
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        @endif

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
