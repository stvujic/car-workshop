<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $workshop->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5" style="max-width: 900px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ $workshop->name }}</h1>
            <div class="text-muted">{{ $workshop->city }}</div>
        </div>

        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            ← Back to Home
        </a>
    </div>

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

    <div class="row g-4">
        {{-- LEFT: Workshop info --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="bg-secondary-subtle" style="height: 180px;"></div>

                <div class="card-body">
                    <h5 class="card-title mb-3">Workshop details</h5>

                    <div class="mb-2">
                        <div class="text-muted small">Address</div>
                        <div class="fw-semibold">{{ $workshop->address }}</div>
                    </div>

                    <div class="mb-2">
                        <div class="text-muted small">Phone</div>
                        <div class="fw-semibold">{{ $workshop->phone }}</div>
                    </div>

                    @if($workshop->description)
                        <hr>
                        <div class="text-muted small mb-1">Description</div>
                        <div>{{ $workshop->description }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: Booking --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    @auth
                        <h5 class="card-title mb-3">Book an appointment</h5>

                        <div class="alert alert-info small mb-3">
                            Your booking request will be <strong>pending</strong> until the workshop owner approves it.
                        </div>

                        <form method="POST" action="{{ route('workshops.bookings.store', $workshop) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" id="booking-date" class="form-control" value="{{ old('date') }}">
                                @error('date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Time</label>
                                <select name="time" id="booking-time" class="form-select" disabled>
                                    <option value="">Select date first</option>
                                </select>
                                @error('time') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                                <div id="availability-message" class="text-muted small mt-2"></div>
                                @error('time') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note (optional)</label>
                                <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                                @error('note') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <button class="btn btn-primary w-100" id="booking-submit" disabled>
                                Submit booking
                            </button>
                        </form>

                        <script>
                            const dateInput = document.getElementById('booking-date');
                            const timeSelect = document.getElementById('booking-time');
                            const submitBtn = document.getElementById('booking-submit');
                            const msg = document.getElementById('availability-message');

                            function setSubmitState() {
                                submitBtn.disabled = !(dateInput.value && timeSelect.value);
                            }

                            function resetTimes(placeholder = 'Select date first') {
                                timeSelect.innerHTML = `<option value="">${placeholder}</option>`;
                                timeSelect.value = '';
                                timeSelect.disabled = true;
                                setSubmitState();
                            }

                            async function loadTimes(date) {
                                resetTimes('Loading...');
                                msg.textContent = '';

                                try {
                                    const url = "{{ route('workshops.available-times', $workshop) }}" + "?date=" + encodeURIComponent(date);
                                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                                    const data = await res.json();

                                    timeSelect.innerHTML = `<option value="">Select time</option>`;

                                    if (data.available_times && data.available_times.length) {
                                        data.available_times.forEach(t => {
                                            const opt = document.createElement('option');
                                            opt.value = t;
                                            opt.textContent = t;
                                            timeSelect.appendChild(opt);
                                        });

                                        timeSelect.disabled = false;
                                    } else {
                                        resetTimes('No available times');
                                    }

                                    if (data.message) {
                                        msg.textContent = data.message;
                                    }

                                } catch (e) {
                                    resetTimes('Error loading times');
                                    msg.textContent = 'Could not load available times. Try again.';
                                }

                                setSubmitState();
                            }

                            // If user changes date -> fetch times
                            dateInput.addEventListener('change', () => {
                                if (!dateInput.value) {
                                    resetTimes();
                                    msg.textContent = '';
                                    return;
                                }
                                loadTimes(dateInput.value);
                            });

                            // If user selects time -> enable submit
                            timeSelect.addEventListener('change', setSubmitState);

                            // On page load: if old date exists, load times and preselect old time
                            window.addEventListener('DOMContentLoaded', () => {
                                const oldDate = dateInput.value;
                                const oldTime = "{{ old('time') }}";

                                if (oldDate) {
                                    loadTimes(oldDate).then(() => {
                                        if (oldTime) {
                                            timeSelect.value = oldTime;
                                        }
                                        setSubmitState();
                                    });
                                } else {
                                    resetTimes();
                                }
                            });
                        </script>


                    @else
                        <h5 class="card-title mb-2">Book an appointment</h5>
                        <p class="text-muted mb-3">
                            To book an appointment, please login or register.
                        </p>

                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-secondary w-50" href="{{ route('login') }}">Login</a>
                            <a class="btn btn-primary w-50" href="{{ route('register') }}">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Working hours</h5>

            <table class="table table-sm align-middle mb-0">
                <thead>
                <tr>
                    <th>Day</th>
                    <th>Status</th>
                    <th>Hours</th>
                </tr>
                </thead>
                <tbody>
                @foreach($workingHoursForView as $row)
                    <tr>
                        <td class="fw-semibold">{{ $row['label'] }}</td>

                        <td>
                            @if($row['is_active'])
                                <span class="badge text-bg-success">Open</span>
                            @else
                                <span class="badge text-bg-secondary">Closed</span>
                            @endif
                        </td>

                        <td>
                            @if($row['is_active'] && $row['start_time'] && $row['end_time'])
                                {{ $row['start_time'] }} – {{ $row['end_time'] }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($closedDays->count())
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Closed days</h5>

                <ul class="list-group list-group-flush">
                    @foreach($closedDays as $row)
                        <li class="list-group-item px-0">
                            <div class="fw-semibold">
                                {{ $row->reason ?? 'Closed' }}
                            </div>

                            <div class="text-muted small">
                                @if($row->start_date->equalTo($row->end_date))
                                    {{ $row->start_date->format('d.m.Y') }}
                                @else
                                    {{ $row->start_date->format('d.m.Y') }}
                                    –
                                    {{ $row->end_date->format('d.m.Y') }}
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

</div>

</body>
</html>
