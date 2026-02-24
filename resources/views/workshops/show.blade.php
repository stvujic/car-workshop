<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $workshop->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #F5F7FA;
            color: #1E293B;
        }

        .navbar-custom {
            background-color: #0F172A;
        }

        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: #fff !important;
        }

        .navbar-custom .nav-link:hover {
            color: #D4AF37 !important;
        }

        .btn-gold {
            background-color: #D4AF37;
            color: #0F172A;
            border: none;
        }

        .btn-gold:hover {
            background-color: #c19d2e;
            color: #0F172A;
        }

        .btn-outline-gold {
            border: 1px solid #D4AF37;
            color: #D4AF37;
            background: transparent;
        }

        .btn-outline-gold:hover {
            background-color: #D4AF37;
            color: #0F172A;
        }

        .section-title {
            font-weight: 800;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
        }

        .card-img-placeholder {
            height: 180px;
            background: linear-gradient(135deg, #0F172A, #1E293B);
        }

        .table thead th {
            background: rgba(15, 23, 42, 0.04);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            font-weight: 700;
        }

        .footer-dark {
            background-color: #0F172A;
            color: white;
        }

        .badge-soft {
            border-radius: 10px;
            padding: 7px 10px;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.2px;
            border: 1px solid rgba(15, 23, 42, 0.12);
        }

        .badge-open {
            background: rgba(34, 197, 94, 0.14);
            color: #166534;
            border-color: rgba(34, 197, 94, 0.25);
        }

        .badge-closed {
            background: rgba(15, 23, 42, 0.06);
            color: #0F172A;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
            Car Service
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>

                @auth
                    @if(auth()->user()->role === 'customer')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.index') }}">My bookings</a>
                        </li>
                    @endif

                    @if(auth()->user()->role === 'owner')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('owner.bookings.index') }}">All bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('owner.myshops.index') }}">My shops</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <div class="d-flex gap-2 align-items-center">
                @guest
                    <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-gold btn-sm" href="{{ route('register') }}">Register</a>
                @endguest

                @auth
                    <span class="text-light small d-none d-lg-inline">
                        Hi, {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container" style="max-width: 1000px;">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h1 class="section-title mb-1">{{ $workshop->name }}</h1>
                <div class="text-muted">{{ $workshop->city }}</div>
            </div>

            <a href="{{ route('home') }}" class="btn btn-outline-gold btn-sm px-3">
                Back to Home
            </a>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger shadow-sm border-0">
                {{ session('error') }}
            </div>
        @endif

        <div class="row g-4">
            {{-- LEFT: Workshop info --}}
            <div class="col-12 col-lg-6">
                <div class="card card-custom h-100">
                    <div class="card-img-placeholder"></div>

                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Workshop details</h5>

                        <div class="mb-3">
                            <div class="text-muted small">Address</div>
                            <div class="fw-semibold">{{ $workshop->address }}</div>
                        </div>

                        <div class="mb-3">
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
                <div class="card card-custom">
                    <div class="card-body p-4">
                        @auth
                            <h5 class="fw-semibold mb-3">Book an appointment</h5>

                            <div class="alert alert-info small shadow-sm border-0 mb-3">
                                Your booking request will be <strong>pending</strong> until the workshop owner approves it.
                            </div>

                            <form method="POST" action="{{ route('workshops.bookings.store', $workshop) }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Date</label>
                                    <input type="date" name="date" id="booking-date" class="form-control shadow-sm" value="{{ old('date') }}">
                                    @error('date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Time</label>
                                    <select name="time" id="booking-time" class="form-select shadow-sm" disabled>
                                        <option value="">Select date first</option>
                                    </select>
                                    @error('time') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                                    <div id="availability-message" class="text-muted small mt-2"></div>
                                    @error('time') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Note (optional)</label>
                                    <textarea name="note" class="form-control shadow-sm" rows="3">{{ old('note') }}</textarea>
                                    @error('note') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <button class="btn btn-gold w-100" id="booking-submit" disabled>
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

                                dateInput.addEventListener('change', () => {
                                    if (!dateInput.value) {
                                        resetTimes();
                                        msg.textContent = '';
                                        return;
                                    }
                                    loadTimes(dateInput.value);
                                });

                                timeSelect.addEventListener('change', setSubmitState);

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
                            <h5 class="fw-semibold mb-2">Book an appointment</h5>
                            <p class="text-muted mb-3">
                                To book an appointment, please login or register.
                            </p>

                            <div class="d-flex gap-2">
                                <a class="btn btn-outline-gold w-50" href="{{ route('login') }}">Login</a>
                                <a class="btn btn-gold w-50" href="{{ route('register') }}">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom mt-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3">Working hours</h5>

                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 35%;">Day</th>
                            <th style="width: 20%;">Status</th>
                            <th>Hours</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($workingHoursForView as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row['label'] }}</td>

                                <td>
                                    @if($row['is_active'])
                                        <span class="badge-soft badge-open">Open</span>
                                    @else
                                        <span class="badge-soft badge-closed">Closed</span>
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
        </div>

        @if($closedDays->count())
            <div class="card card-custom mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">Closed days</h5>

                    <div class="d-flex flex-column gap-3">
                        @foreach($closedDays as $row)
                            <div class="p-3 rounded-4" style="background: rgba(15,23,42,0.04); border: 1px solid rgba(15,23,42,0.08);">
                                <div class="fw-semibold">
                                    {{ $row->reason ?? 'Closed' }}
                                </div>

                                <div class="text-muted small mt-1">
                                    @if($row->start_date->equalTo($row->end_date))
                                        {{ $row->start_date->format('d.m.Y') }}
                                    @else
                                        {{ $row->start_date->format('d.m.Y') }} – {{ $row->end_date->format('d.m.Y') }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        @endif

    </div>
</section>

<section class="py-4 footer-dark">
    <div class="container text-center">
        <div class="small opacity-75">
            © {{ date('Y') }} Car Service. All rights reserved.
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
