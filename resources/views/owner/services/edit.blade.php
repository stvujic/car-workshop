<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<div class="mb-4">
    <h1 class="mb-1">Edit Service</h1>
    <p class="text-muted">
        Workshop: <strong>{{ $workshop->name }}</strong>
    </p>
</div>

{{-- ERROR MESSAGES --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ route('owner.services.update', [$workshop, $service]) }}"
      class="card shadow-sm p-4"
      style="max-width: 600px;"
>
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Service name</label>
        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $service->name) }}"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Duration (minutes)</label>
        <input
            type="number"
            name="duration_minutes"
            class="form-control"
            min="1"
            value="{{ old('duration_minutes', $service->duration_minutes) }}"
            required
        >
    </div>

    <div class="mb-4">
        <label class="form-label">Price (EUR)</label>
        <input
            type="number"
            name="price"
            class="form-control"
            step="0.01"
            min="0"
            value="{{ old('price', $service->price) }}"
            required
        >
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary">
            Save changes
        </button>

        <a href="{{ route('owner.services.index', $workshop) }}"
           class="btn btn-outline-secondary">
            Cancel
        </a>
    </div>
</form>

</body>
</html>
