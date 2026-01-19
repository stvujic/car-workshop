<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <nav>
        <div>
            <a href="#about">About Us</a>
            <a href="#workshops">Workshops</a>
            <a href="#contact">Contact Us</a>
        </div>

        <div>
            @guest
                <a href="{{route('login')}}">Login</a>
                <a href="{{route('register')}}">Register</a>
            @endguest
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
        <h2>About Us</h2>
        <p>Ovo je about us sekcija (placeholder).</p>
    </section>

    <section id="workshops" style="padding: 80px 20px;">
        <h2>Workshops</h2>

        @if($workshops->count() === 0)
            <p>Trenutno nema odobrenih servisa.</p>
        @else
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-top: 20px;">
                @foreach($workshops as $workshop)
                    <div style="border: 1px solid #ddd; padding: 12px;">
                        <div style="height: 120px; background: #f3f3f3; margin-bottom: 10px;"></div>

                        <div style="font-weight: 600;">{{ $workshop->name }}</div>
                        <div style="color: #666;">{{ $workshop->city }}</div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 20px;">
                {{ $workshops->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>


    <section id="contact" style="padding: 80px 20px; min-height: 400px">
        <h2>Contact</h2>
        <p>Ovo je contact sekcija (placeholder).</p>
    </section>


</body>
</html>
