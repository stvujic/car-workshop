<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyShops</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h1 class="mb-4">MyShops</h1>

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

<div class="mb-3">
    <a href="{{ route('owner.myshops.create') }}" class="btn btn-primary">
        Add New Shop
    </a>
</div>

@if($workshops->count() === 0)
    <p>You don’t have any workshops yet.</p>
@else
    <div class="list-group">
        @foreach($workshops as $workshop)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">
                        {{ $workshop->name }}
                    </div>
                    <div class="text-muted">
                        {{ $workshop->city }} ({{ $workshop->status }})
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('owner.myshops.edit', $workshop) }}" class="btn btn-sm btn-outline-primary">
                        Edit
                    </a>
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
