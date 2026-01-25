<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Workshop Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h1 class="mb-1">My Workshop Bookings</h1>
<p class="text-muted mb-4">All bookings across your workshops</p>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($bookings->count() === 0)
    <div class="alert alert-info">
        No bookings yet.
    </div>
@else
    <div class="list-group">
        @foreach($bookings as $booking)
            <div class="list-group-item d-flex justify-content-between align-items-center">

                {{-- LEFT SIDE --}}
                <div>
                    <div class="fw-semibold">
                        {{ $booking->workshop->name ?? 'Workshop deleted' }}
                    </div>

                    <div class="text-muted small">
                        Customer ID: {{ $booking->user_id }}
                    </div>

                    <div class="text-muted">
                        {{ $booking->date }} at {{ $booking->time }}
                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="d-flex gap-2 align-items-center">

                    {{-- Open public workshop --}}
                    @if($booking->workshop)
                        <a href="{{ route('workshops.show', $booking->workshop) }}"
                           class="btn btn-sm btn-outline-primary">
                            Open workshop
                        </a>
                    @endif

                    {{-- STATUS ACTIONS (owner) --}}
                    @if($booking->status === 'pending')
                        <form method="POST" action="{{ route('owner.bookings.approve', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">
                                Approve
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('owner.bookings.cancel', $booking) }}"
                              onsubmit="return confirm('Cancel this booking?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Cancel
                            </button>
                        </form>
                    @endif

                    @if($booking->status === 'approved')
                        <span class="badge bg-success">
                            Approved
                        </span>
                    @endif

                    @if($booking->status === 'canceled')
                        <span class="badge bg-danger">
                            Canceled
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $bookings->links('pagination::bootstrap-5') }}
    </div>
@endif

</body>
</html>
