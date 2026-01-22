<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">My Bookings</h1>
    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Back to Home</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($bookings->count() === 0)
    <div class="alert alert-info">You don’t have any bookings yet.</div>
@else
    <div class="list-group">
        @foreach ($bookings as $booking)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">
                        {{ $booking->workshop->name ?? 'Workshop deleted' }}
                    </div>
                    <div class="text-muted">
                        {{ $booking->date }} at {{ $booking->time }}
                        <span class="ms-2 badge text-bg-secondary">{{ $booking->status }}</span>
                    </div>
                </div>

                @if($booking->workshop)
                    <a class="btn btn-sm btn-outline-primary"
                       href="{{ route('workshops.show', $booking->workshop) }}">
                        Open workshop
                    </a>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $bookings->links('pagination::bootstrap-5') }}
    </div>
@endif

</body>
</html>
