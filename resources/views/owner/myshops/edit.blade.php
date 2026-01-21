<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Workshop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h1 class="mb-4">Edit Workshop</h1>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('owner.myshops.update', $workshop) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Workshop Name</label>
        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $workshop->name) }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">City</label>
        <input
            type="text"
            name="city"
            class="form-control"
            value="{{ old('city', $workshop->city) }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Address</label>
        <input
            type="text"
            name="address"
            class="form-control"
            value="{{ old('address', $workshop->address) }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input
            type="text"
            name="phone"
            class="form-control"
            value="{{ old('phone', $workshop->phone) }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea
            name="description"
            class="form-control"
            rows="4"
        >{{ old('description', $workshop->description) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">
        Update Workshop
    </button>

    <a href="{{ route('owner.myshops.index') }}" class="btn btn-secondary ms-2">
        Cancel
    </a>
</form>

</body>
</html>
