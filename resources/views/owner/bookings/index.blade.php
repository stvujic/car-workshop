<h1>My Workshop Bookings</h1>
<p class="text-muted">All bookings across your workshops</p>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($bookings->count() === 0)
    <p>No bookings yet.</p>
@else
    @foreach($bookings as $booking)
        <div>
            <strong>{{ $booking->workshop->name }}</strong><br>
            Customer ID: {{ $booking->user_id }}<br>
            Date: {{ $booking->date }} {{ $booking->time }}<br>
            Status: {{ $booking->status }}<br><br>

            {{-- STATUS ACTIONS (owner) --}}
            <div class="d-flex gap-2">
                @if($booking->status === 'pending')
                    <form method="POST" action="{{ route('owner.bookings.approve', $booking) }}">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>

                    <form method="POST" action="{{ route('owner.bookings.cancel', $booking) }}"
                          onsubmit="return confirm('Cancel this booking?')">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-outline-danger">Cancel</button>
                    </form>
                @endif

                @if($booking->status === 'approved')
                    <span class="badge bg-success">Approved</span>
                @endif

                @if($booking->status === 'canceled')
                    <span class="badge bg-danger">Canceled</span>
                @endif
            </div>
        </div>

        <hr>
    @endforeach

    {{ $bookings->links('pagination::bootstrap-5') }}
@endif
