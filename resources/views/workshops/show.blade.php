<!doctype html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$workshop->name}}</title>
    </head>

    <body>
        <h1>{{$workshop->name}}</h1>

        <p><strong>City:</strong>{{$workshop->city}}</p>
        <p><strong>Address:</strong>{{$workshop->address}}</p>
        <p><strong>Phone:</strong>{{$workshop->phone}}</p>

        @if($workshop->description)
            <p><strong>Description:</strong></p>
            <p>{{$workshop->description}}</p>
        @endif

        <p>
            <a href="{{route('home')}}">Back to Home</a>
        </p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @auth
            <hr>
            <h3>Book an appointment</h3>

            <form method="POST" action="{{ route('workshops.bookings.store', $workshop) }}" class="mt-3">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date') }}">
                    @error('date') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Time</label>
                    <input type="time" name="time" class="form-control" value="{{ old('time') }}">
                    @error('time') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Note (optional)</label>
                    <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                    @error('note') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary">Submit booking</button>
            </form>
        @else
            <hr>
            <p>
                To book an appointment, please
                <a href="{{ route('login') }}">login</a>
                or
                <a href="{{ route('register') }}">register</a>.
            </p>
        @endauth


    </body>

</html>
