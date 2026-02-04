<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working Hours – {{ $workshop->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Working Hours</h1>
        <p class="text-muted mb-0">
            Workshop: <strong>{{ $workshop->name }}</strong>
        </p>
    </div>

    <a href="{{ route('owner.myshops.index') }}" class="btn btn-outline-secondary">
        ← Back to My Shops
    </a>
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

<form method="POST" action="{{ route('owner.working_hours.store', $workshop) }}">
    @csrf

    <table class="table table-bordered align-middle">
        <thead class="table-light">
        <tr>
            <th style="width: 20%">Day</th>
            <th style="width: 15%">Active</th>
            <th style="width: 25%">Start time</th>
            <th style="width: 25%">End time</th>
        </tr>
        </thead>

        <tbody>
        @foreach($workingHours as $dayNumber => $day)
            <tr>
                <td class="fw-semibold">
                    {{ $day['label'] }}
                </td>

                <td>
                    <input type="checkbox"
                           name="days[{{ $dayNumber }}][is_active]"
                           value="1"
                    {{ old("days.$dayNumber.is_active", $day['is_active']) ? 'checked' : '' }}>
                </td>

                <td>
                    <input type="time"
                           class="form-control"
                           name="days[{{ $dayNumber }}][start_time]"
                           value="{{ old("days.$dayNumber.start_time", $day['start_time'] ? substr($day['start_time'], 0, 5) : '') }}">
                </td>

                <td>
                    <input type="time"
                           class="form-control"
                           name="days[{{ $dayNumber }}][end_time]"
                           value="{{ old("days.$dayNumber.end_time", $day['end_time'] ? substr($day['end_time'], 0, 5) : '') }}">
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary px-4">
            Save Working Hours
        </button>
    </div>
</form>

</body>
</html>
