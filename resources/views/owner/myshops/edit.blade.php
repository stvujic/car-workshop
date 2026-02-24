<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Workshop</title>

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
                <h1 class="section-title mb-1">Edit Workshop</h1>
                <p class="text-muted mb-0">
                    Update your workshop information.
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
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-custom">
            <div class="card-header">
                Workshop Details
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('owner.myshops.update', $workshop) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Workshop Name</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control shadow-sm"
                                value="{{ old('name', $workshop->name) }}"
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input
                                type="text"
                                name="city"
                                class="form-control shadow-sm"
                                value="{{ old('city', $workshop->city) }}"
                            >
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <input
                                type="text"
                                name="address"
                                class="form-control shadow-sm"
                                value="{{ old('address', $workshop->address) }}"
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control shadow-sm"
                                value="{{ old('phone', $workshop->phone) }}"
                            >
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea
                                name="description"
                                class="form-control shadow-sm"
                                rows="4"
                            >{{ old('description', $workshop->description) }}</textarea>
                        </div>

                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                        <button type="submit" class="btn btn-gold px-4">
                            Update Workshop
                        </button>

                        <a href="{{ route('owner.myshops.index') }}" class="btn btn-outline-gold px-4">
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
