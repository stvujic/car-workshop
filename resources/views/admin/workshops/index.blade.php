<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pending Workshops</title>

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
            margin-bottom: 18px;
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

        .badge-gold {
            background: rgba(212, 175, 55, 0.16);
            color: #8a6d1d;
            border: 1px solid rgba(212, 175, 55, 0.35);
            font-weight: 600;
        }

        .soft-panel {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
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
                    @if(auth()->user()->role === 'customer')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.index') }}">My bookings</a>
                        </li>
                    @endif

                    @if(auth()->user()->role === 'owner')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('owner.bookings.index') }}">All bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('owner.myshops.index') }}">My shops</a>
                        </li>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.workshops.pending') }}">Pending workshops</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <div class="d-flex gap-2 align-items-center">
                @guest
                    <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-gold btn-sm" href="{{ route('register') }}">Register</a>
                @endguest

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
                <h1 class="section-title mb-1">Pending Workshops</h1>
                <p class="text-muted mb-0">
                    Review new workshop submissions and approve or reject them.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-gold btn-sm px-3">
                    Back to Home
                </a>
            </div>
        </div>

        {{-- Flash messages --}}
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

        @if ($workshops->count() === 0)
            <div class="soft-panel p-4 text-center">
                <div class="fw-semibold">There are no pending workshops at the moment.</div>
                <div class="text-muted small mt-1">When a new workshop is submitted, it will appear here for review.</div>
            </div>
        @else
            <div class="row g-4">
                @foreach ($workshops as $workshop)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card card-custom h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                    <div>
                                        <div class="fw-semibold fs-5 mb-1">
                                            {{ $workshop->name }}
                                        </div>

                                        <div class="text-muted">
                                            {{ $workshop->city }}
                                            <span class="mx-2">•</span>
                                            <span class="small">Owner ID: {{ $workshop->owner_id }}</span>
                                        </div>

                                        <div class="small text-muted mt-1">
                                            Created: {{ $workshop->created_at->format('d.m.Y H:i') }}
                                        </div>
                                    </div>

                                    <span class="badge badge-gold align-self-start">Pending</span>
                                </div>

                                <div class="d-flex gap-2">
                                    {{-- Approve --}}
                                    <form method="POST" action="{{ route('admin.workshops.approve', $workshop) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-gold btn-sm px-3">
                                            Approve
                                        </button>
                                    </form>

                                    {{-- Reject --}}
                                    <form method="POST" action="{{ route('admin.workshops.reject', $workshop) }}"
                                          onsubmit="return confirm('Are you sure you want to reject this workshop?');">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                            Reject
                                        </button>
                                    </form>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-muted small">
                                        Quick tip: approve only verified owners and accurate locations.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $workshops->links('pagination::bootstrap-5') }}
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
