<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Closed Days - {{ $workshop->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Closed Days</h1>

    <a href="{{ route('owner.myshops.index') }}" class="btn btn-outline-secondary btn-sm">
        Back
    </a>
</div>

<div class="text-muted mb-4">
    Workshop: <strong>{{ $workshop->name }}</strong>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Please fix:</div>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header fw-semibold">Add Closed Day</div>
    <div class="card-body">
        <form method="POST" action="{{ route('owner.closed_days.store', $workshop) }}" class="row g-2 align-items-end">
            @csrf

            <div class="col-md-4">
                <label class="form-label">Reason</label>
                <input type="text" name="reason" class="form-control" value="{{ old('reason') }}" placeholder="e.g. Holiday">
            </div>

            <div class="col-md-3">
                <label class="form-label">Start date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">End date (optional)</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>

            <div class="col-md-2 d-grid">
                <button class="btn btn-dark">Add</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header fw-semibold">Existing Closed Days</div>
    <div class="card-body p-0">
        @if($closedDays->count() === 0)
            <div class="p-3">
                <p class="mb-0">No closed days added yet.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>Reason</th>
                        <th>Start</th>
                        <th>End</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($closedDays as $row)
                        <tr>
                            <td>{{ $row->reason ?? '-' }}</td>
                            <td>{{ $row->start_date->format('Y-m-d') }}</td>
                            <td>{{ $row->end_date ? $row->end_date->format('Y-m-d') : '-' }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('owner.closed_days.destroy', [$workshop, $row]) }}"
                                      onsubmit="return confirm('Delete this closed day?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

</body>
</html>
