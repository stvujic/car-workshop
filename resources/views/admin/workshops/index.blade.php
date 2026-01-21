<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Workshops</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h1 class="mb-4">Pending Workshops</h1>

{{-- Flash messages --}}
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

@if ($workshops->count() === 0)
    <div class="alert alert-info">
        There are no pending workshops at the moment.
    </div>
@else
    <div class="list-group">
        @foreach ($workshops as $workshop)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">
                        {{ $workshop->name }}
                    </div>
                    <div class="text-muted">
                        {{ $workshop->city }} |
                        Owner ID: {{ $workshop->owner_id }}
                    </div>
                    <div class="small text-muted">
                        Created: {{ $workshop->created_at->format('d.m.Y H:i') }}
                    </div>
                </div>

                <div class="d-flex gap-2">
                    {{-- Approve --}}
                    <form method="POST" action="{{ route('admin.workshops.approve', $workshop) }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-sm btn-success">
                            Approve
                        </button>
                    </form>

                    {{-- Reject --}}
                    <form method="POST" action="{{ route('admin.workshops.reject', $workshop) }}"
                          onsubmit="return confirm('Are you sure you want to reject this workshop?');">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $workshops->links('pagination::bootstrap-5') }}
    </div>
@endif

</body>
</html>
