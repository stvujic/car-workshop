<h1>MyShops</h1>

@foreach($workshops as $workshop)
    <div>{{ $workshop->name }} - {{ $workshop->city }} ({{ $workshop->status }})</div>
@endforeach

{{ $workshops->links('pagination::bootstrap-5') }}
