<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Closed Days - {{ $workshop->name }}</title>

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
                <h1 class="section-title mb-1">Closed Days</h1>
                <p class="text-muted mb-0">
                    Workshop: <strong>{{ $workshop->name }}</strong>
                </p>
            </div>

            <a href="{{ route('owner.myshops.index') }}" class="btn btn-outline-gold btn-sm px-3">
                Back
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0">
                <div class="fw-semibold mb-1">Please fix:</div>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-custom mb-4">
            <div class="card-header">Add Closed Day</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.closed_days.store', $workshop) }}" class="row g-3 align-items-end">
                    @csrf

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Reason</label>
                        <input type="text"
                               name="reason"
                               class="form-control shadow-sm"
                               value="{{ old('reason') }}"
                               placeholder="e.g. Holiday">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Start date</label>
                        <input type="date"
                               name="start_date"
                               class="form-control shadow-sm"
                               value="{{ old('start_date') }}"
                               required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">End date (optional)</label>
                        <input type="date"
                               name="end_date"
                               class="form-control shadow-sm"
                               value="{{ old('end_date') }}">
                    </div>

                    <div class="col-md-2 d-grid">
                        <button class="btn btn-gold">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header">Existing Closed Days</div>
            <div class="card-body p-0">
                @if($closedDays->count() === 0)
                    <div class="p-4 text-center">
                        <div class="fw-semibold">No closed days added yet.</div>
                        <div class="text-muted small mt-1">Add a date range to block bookings for that period.</div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                            <tr>
                                <th class="ps-4">Reason</th>
                                <th>Start</th>
                                <th>End</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($closedDays as $row)
                                <tr>
                                    <td class="ps-4">{{ $row->reason ?? '-' }}</td>
                                    <td>{{ $row->start_date->format('Y-m-d') }}</td>
                                    <td>{{ $row->end_date ? $row->end_date->format('Y-m-d') : '-' }}</td>
                                    <td class="text-end pe-4">
                                        <form method="POST"
                                              action="{{ route('owner.closed_days.destroy', [$workshop, $row]) }}"
                                              onsubmit="return confirm('Delete this closed day?');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger px-3">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
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
