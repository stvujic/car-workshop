@php use App\Models\Booking; @endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Workshop Bookings</title>

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

        .badge-cancelled {
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
                            <a class="nav-link active" href="{{ route('owner.bookings.index') }}">All bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('owner.myshops.index') }}">My shops</a>
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
                <h1 class="section-title mb-1">My Workshop Bookings</h1>
                <p class="text-muted mb-0">All bookings across your workshops</p>
            </div>

            <a href="{{ route('home') }}" class="btn btn-outline-gold btn-sm px-3">
                Back to Home
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0">
                {{ session('success') }}
            </div>
        @endif

        @if($bookings->count() === 0)
            <div class="card card-custom p-4 text-center">
                <div class="fw-semibold">No bookings yet.</div>
                <div class="text-muted small mt-1">When customers book your workshops, they will appear here.</div>
            </div>
        @else
            <div class="row g-4">
                @foreach($bookings as $booking)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card card-custom h-100">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                    <div>
                                        <div class="fw-semibold fs-5 mb-1">
                                            {{ $booking->workshop->name ?? 'Workshop deleted' }}
                                        </div>

                                        <div class="text-muted small">
                                            Customer ID: {{ $booking->user_id }}
                                        </div>

                                        <div class="text-muted mt-2">
                                            {{ $booking->date }} at {{ $booking->time }}
                                        </div>
                                    </div>

                                    @if($booking->status === Booking::STATUS_PENDING)
                                        <span class="badge-soft badge-pending">Pending</span>
                                    @endif

                                    @if($booking->status === Booking::STATUS_APPROVED)
                                        <span class="badge-soft badge-approved">Approved</span>
                                    @endif

                                    @if($booking->status === Booking::STATUS_CANCELLED)
                                        <span class="badge-soft badge-cancelled">Cancelled</span>
                                    @endif
                                </div>

                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    @if($booking->workshop)
                                        <a href="{{ route('workshops.show', $booking->workshop) }}"
                                           class="btn btn-outline-gold btn-sm px-3">
                                            Open workshop
                                        </a>
                                    @endif

                                    {{-- STATUS ACTIONS --}}
                                    @if($booking->status === Booking::STATUS_PENDING)
                                        <form method="POST" action="{{ route('owner.bookings.approve', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-gold btn-sm px-3">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('owner.bookings.cancel', $booking) }}"
                                              onsubmit="return confirm('Cancel this booking?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <hr class="my-4">

                                <div class="text-muted small">
                                    Tip: Approve only bookings that fit your working hours and capacity.
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $bookings->links('pagination::bootstrap-5') }}
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
