<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Working Hours – {{ $workshop->name }}</title>

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

        .table thead th {
            background: rgba(15, 23, 42, 0.04);
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
    <div class="container">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h1 class="section-title mb-1">Working Hours</h1>
                <p class="text-muted mb-0">
                    Workshop: <strong>{{ $workshop->name }}</strong>
                </p>
            </div>

            <a href="{{ route('owner.myshops.index') }}" class="btn btn-outline-gold btn-sm px-3">
                Back to My Shops
            </a>
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

        <div class="card card-custom">
            <div class="card-header">Weekly Schedule</div>
            <div class="card-body p-0">
                <form method="POST" action="{{ route('owner.working_hours.store', $workshop) }}">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                            <tr>
                                <th class="ps-4" style="width: 28%">Day</th>
                                <th style="width: 14%">Active</th>
                                <th style="width: 29%">Start time</th>
                                <th class="pe-4" style="width: 29%">End time</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($workingHours as $dayNumber => $day)
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        {{ $day['label'] }}
                                    </td>

                                    <td>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   role="switch"
                                                   name="days[{{ $dayNumber }}][is_active]"
                                                   value="1"
                                                {{ old("days.$dayNumber.is_active", $day['is_active']) ? 'checked' : '' }}>
                                        </div>
                                    </td>

                                    <td>
                                        <input type="time"
                                               class="form-control shadow-sm"
                                               name="days[{{ $dayNumber }}][start_time]"
                                               value="{{ old("days.$dayNumber.start_time", $day['start_time'] ? substr($day['start_time'], 0, 5) : '') }}">
                                    </td>

                                    <td class="pe-4">
                                        <input type="time"
                                               class="form-control shadow-sm"
                                               name="days[{{ $dayNumber }}][end_time]"
                                               value="{{ old("days.$dayNumber.end_time", $day['end_time'] ? substr($day['end_time'], 0, 5) : '') }}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                        <div class="text-muted small">
                            Tip: Disable a day to prevent bookings, or set hours to control availability.
                        </div>

                        <button type="submit" class="btn btn-gold px-4">
                            Save Working Hours
                        </button>
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
