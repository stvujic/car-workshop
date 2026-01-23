<h1>Workshop Bookings</h1>

@if($bookings->count() === 0)
    <p>No bookings yet.</p>
@else
    @foreach($bookings as $booking)
        <div>
            <strong>{{ $booking->workshop->name }}</strong><br>
            Customer ID: {{ $booking->user_id }}<br>
            Date: {{ $booking->date }} {{ $booking->time }}<br>
            Status: {{ $booking->status }}
        </div>
        <hr>
    @endforeach

    {{ $bookings->links() }}
@endif
