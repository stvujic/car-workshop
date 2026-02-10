<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Car Workshop</title>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('home') }}">
            Car Service
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            {{-- LEFT LINKS --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#workshops">Workshops</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact Us</a>
                </li>

                @auth
                    {{-- CUSTOMER --}}
                    @if(auth()->user()->role === 'customer')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.index') }}">My bookings</a>
                        </li>
                    @endif

                    {{-- OWNER --}}
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

            {{-- RIGHT SIDE --}}
            <div class="d-flex gap-2 align-items-center">
                @guest
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-sm btn-primary" href="{{ route('register') }}">Register</a>
                @endguest

                @auth
                    <span class="text-muted small d-none d-lg-inline">
                        Hi, {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>


<section id="hero"
             style="
            height: 33vh;
            background-image: url('/images/hero.jpg');
            background-size: cover;
            background-position: center;
         ">
    </section>

<section id="about" style="padding: 80px 20px; min-height: 400px">
    <div class="container">
        <h2>About Us</h2>
        <p>Ovo je about us sekcija (placeholder).</p>
    </div>
</section>


    <section id="workshops" style="padding: 80px 20px;">
    <div class="container">
        <h2>Workshops</h2>

        <form method="GET" action="{{ route('home') }}" class="row g-2 align-items-end mt-2">
            <div class="col-12 col-md-4">
                <label class="form-label mb-1">City</label>
                <select name="city" class="form-select" onchange="this.form.submit()">
                    <option value="">All cities</option>

                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-auto">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </form>


    @if($workshops->count() === 0)
            <p>Trenutno nema odobrenih servisa.</p>
        @else
            <div class="row g-4 mt-3">
                @foreach($workshops as $workshop)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <a href="{{ route('workshops.show', $workshop->slug) }}"
                           class="text-decoration-none text-dark">

                            <div class="card h-100 shadow-sm">
                                {{-- Image placeholder --}}
                                <div class="bg-light" style="height: 140px;"></div>

                                <div class="card-body">
                                    <h5 class="card-title mb-1">
                                        {{ $workshop->name }}
                                    </h5>

                                    <p class="card-text text-muted mb-0">
                                        {{ $workshop->city }}
                                    </p>
                                </div>
                            </div>

                        </a>
                    </div>
                @endforeach
            </div>


            <div style="margin-top: 20px;">
                {{ $workshops->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    </section>


<section id="contact" class="py-5 bg-light">
    <div class="container" style="max-width: 700px;">
        <h2 class="mb-3 text-center">Contact Us</h2>
        <p class="text-center text-muted mb-4">
            Have a question or want to get in touch? Fill out the form below.
        </p>

        <form>
            <div class="mb-3">
                <label class="form-label">Your name</label>
                <input type="text" class="form-control" placeholder="John Doe">
            </div>

            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" placeholder="email@example.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea class="form-control" rows="4" placeholder="Your message..."></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">
                    Send message
                </button>
            </div>
        </form>
    </div>
</section>



</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</html>
