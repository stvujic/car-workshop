<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services – {{ $workshop->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Services</h1>
        <p class="text-muted mb-0">
            Workshop: <strong>{{ $workshop->name }}</strong>
        </p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('owner.myshops.index') }}" class="btn btn-outline-secondary">
            Back to My Shops
        </a>

        <a href="{{ route('owner.services.create', $workshop) }}" class="btn btn-primary">
            Add Service
        </a>
    </div>
</div>

{{-- FLASH MESSAGES --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

{{-- SERVICES LIST --}}
@if($services->count() === 0)
    <div class="alert alert-info">
        No services added for this workshop yet.
    </div>
@else
    <div class="list-group">
        @foreach($services as $service)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">
                        {{ $service->name }}
                    </div>

                    <div class="text-muted small">
                        Duration: {{ $service->duration_minutes }} min |
                        Price: €{{ number_format($service->price, 2) }}
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap justify-content-end">
                    <a href="{{ route('owner.services.edit', [$workshop, $service]) }}"
                       class="btn btn-sm btn-outline-primary">
                        Edit
                    </a>

                    <form method="POST"
                          action="{{ route('owner.services.destroy', [$workshop, $service]) }}"
                          onsubmit="return confirm('Delete this service?');">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-outline-danger">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $services->links('pagination::bootstrap-5') }}
    </div>
@endif

</body>
</html>
