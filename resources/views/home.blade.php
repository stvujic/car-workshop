<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Car Workshop</title>

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

        .hero-overlay {
            background: rgba(15, 23, 42, 0.75);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 40px;
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

        .card-img-placeholder {
            height: 140px;
            background: linear-gradient(135deg, #0F172A, #1E293B);
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
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
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#workshops">Workshops</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>

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

<section id="hero" style="height: 60vh; background-image: url('/images/hero.jpg'); background-size: cover; background-position: center;">
    <div class="hero-overlay">
        <div>
            <h1 class="display-4 fw-bold mb-3">Premium Car Workshops</h1>
            <p class="lead mb-4">Find trusted mechanics in your city and book your service in seconds.</p>
            <a href="#workshops" class="btn btn-gold btn-lg px-4">Explore Workshops</a>
        </div>
    </div>
</section>

<section id="about" class="py-5">
    <div class="container text-center">
        <h2 class="section-title">About Our Platform</h2>
        <p class="mx-auto" style="max-width: 700px;">
            We connect car owners with verified and professional workshops.
            Transparent pricing, easy booking and reliable service — all in one place.
        </p>
    </div>
</section>

<section id="workshops" class="py-5">
    <div class="container">
        <h2 class="section-title text-center">Available Workshops</h2>

        <form method="GET" action="{{ route('home') }}" class="row g-3 justify-content-center mb-4">
            <div class="col-md-4">
                <select name="city" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="">All cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        @if($workshops->count() === 0)
            <p class="text-center text-muted">No approved workshops at the moment.</p>
        @else
            <div class="row g-4">
                @foreach($workshops as $workshop)
                    <div class="col-md-4 col-lg-3">
                        <a href="{{ route('workshops.show', $workshop->slug) }}" class="text-decoration-none text-dark">
                            <div class="card card-custom h-100 shadow-sm">
                                <div class="card-img-placeholder"></div>
                                <div class="card-body text-center">
                                    <h5 class="fw-semibold">{{ $workshop->name }}</h5>
                                    <p class="text-muted mb-0">{{ $workshop->city }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $workshops->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>

<section id="contact" class="py-5 footer-dark">
    <div class="container" style="max-width: 700px;">
        <h2 class="text-center mb-4">Contact Us</h2>

        <form>
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Your name">
            </div>

            <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email address">
            </div>

            <div class="mb-3">
                <textarea class="form-control" rows="4" placeholder="Your message"></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-gold px-4">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
