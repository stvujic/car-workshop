<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyShops</title>

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

        .badge-soft {
            border-radius: 10px;
            padding: 7px 10px;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.2px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-pending {
            background: rgba(212, 175, 55, 0.16);
            color: #8a6d1d;
            border-color: rgba(212, 175, 55, 0.35);
        }

        .badge-approved {
            background: rgba(34, 197, 94, 0.14);
            color: #166534;
            border-color: rgba(34, 197, 94, 0.25);
        }

        .badge-rejected {
            background: rgba(239, 68, 68, 0.12);
            color: #7f1d1d;
            border-color: rgba(239, 68, 68, 0.22);
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
                <h1 class="section-title mb-1">MyShops</h1>
                <p class="text-muted mb-0">
                    Manage your workshops, working hours, and closed days.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('owner.myshops.create') }}" class="btn btn-gold btn-sm px-3">
                    Add New Shop
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-gold btn-sm px-3">
                    Back to Home
                </a>
            </div>
        </div>

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

        @if($workshops->count() === 0)
            <div class="card card-custom p-4 text-center">
                <div class="fw-semibold">You don’t have any workshops yet.</div>
                <div class="text-muted small mt-1">Create your first shop to start accepting bookings.</div>
                <div class="mt-3">
                    <a href="{{ route('owner.myshops.create') }}" class="btn btn-gold btn-sm px-4">
                        Add New Shop
                    </a>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($workshops as $workshop)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card card-custom h-100 shadow-sm">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                    <div>
                                        <div class="fw-semibold fs-5 mb-1">
                                            {{ $workshop->name }}
                                        </div>
                                        <div class="text-muted">
                                            {{ $workshop->city }}
                                        </div>
                                    </div>

                                    @php
                                        $status = strtolower($workshop->status ?? '');
                                    @endphp

                                    @if($status === 'pending')
                                        <span class="badge-soft badge-pending">Pending</span>
                                    @elseif($status === 'approved')
                                        <span class="badge-soft badge-approved">Approved</span>
                                    @elseif($status === 'rejected')
                                        <span class="badge-soft badge-rejected">Rejected</span>
                                    @else
                                        <span class="badge-soft" style="background: rgba(15,23,42,0.06); color:#0F172A;">
                                            {{ $workshop->status }}
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('owner.working_hours.index', $workshop) }}"
                                       class="btn btn-outline-gold btn-sm px-3">
                                        Working Hours
                                    </a>

                                    <a href="{{ route('owner.closed_days.index', $workshop) }}"
                                       class="btn btn-outline-gold btn-sm px-3">
                                        Closed Days
                                    </a>

                                    <a href="{{ route('workshops.show', $workshop->slug) }}"
                                       class="btn btn-outline-secondary btn-sm px-3"
                                       target="_blank">
                                        Open
                                    </a>

                                    <a href="{{ route('owner.myshops.edit', $workshop) }}"
                                       class="btn btn-gold btn-sm px-3">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('owner.myshops.destroy', $workshop) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this workshop?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                            Delete
                                        </button>
                                    </form>
                                </div>

                                <hr class="my-4">

                                <div class="text-muted small">
                                    Tip: Keep your hours and closed days updated to avoid invalid bookings.
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
