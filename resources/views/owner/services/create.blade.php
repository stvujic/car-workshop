<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Service</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Add Service</h1>

    <a href="{{ route('owner.services.index', $workshop) }}"
       class="btn btn-outline-secondary">
        Back to Services
    </a>
</div>

<p class="text-muted">
    Workshop: <strong>{{ $workshop->name }}</strong>
</p>

{{-- Validation errors --}}
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
      action="{{ route('owner.services.store', $workshop) }}"
      class="mt-4">

    @csrf

    <div class="mb-3">
        <label class="form-label">Service name</label>
        <input type="text"
               name="name"
               class="form-control"
               value="{{ old('name') }}"
               placeholder="e.g. Oil change">
    </div>

    <div class="mb-3">
        <label class="form-label">Duration (minutes)</label>
        <input type="number"
               name="duration_minutes"
               class="form-control"
               value="{{ old('duration_minutes') }}"
               placeholder="e.g. 60">
    </div>

    <div class="mb-3">
        <label class="form-label">Price (EUR)</label>
        <input type="number"
               step="0.01"
               name="price"
               class="form-control"
               value="{{ old('price') }}"
               placeholder="e.g. 35.00">
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            Save Service
        </button>

        <a href="{{ route('owner.services.index', $workshop) }}"
           class="btn btn-secondary ms-2">
            Cancel
        </a>
    </div>

</form>

</body>
</html>
