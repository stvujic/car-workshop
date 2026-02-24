<x-guest-layout>
    <div class="text-center mb-4">
        <div class="fw-bold" style="color:#0F172A; font-size: 1.6rem; line-height:1.2;">
            Welcome back
        </div>
        <div class="text-muted mt-2">
            Sign in to manage bookings and workshops.
        </div>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" class="form-label fw-semibold" />
            <x-text-input
                id="email"
                class="form-control mt-1 shadow-sm"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="you@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-2">
            <x-input-label for="password" :value="__('Password')" class="form-label fw-semibold" />
            <x-text-input
                id="password"
                class="form-control mt-1 shadow-sm"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label text-muted">
                    {{ __('Remember me') }}
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-decoration-none small"
                   href="{{ route('password.request') }}"
                   style="color:#0F172A; font-weight:600;">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-gold w-100 py-2 mt-4">
            {{ __('Log in') }}
        </button>

        <div class="d-flex align-items-center gap-2 my-4">
            <div class="flex-grow-1" style="height:1px; background: rgba(15,23,42,0.12);"></div>
            <div class="text-muted small">or</div>
            <div class="flex-grow-1" style="height:1px; background: rgba(15,23,42,0.12);"></div>
        </div>

        <a href="{{ route('register') }}" class="btn btn-outline-gold w-100 py-2">
            Create an account
        </a>

        <div class="text-center text-muted small mt-4">
            By continuing, you agree to our platform rules.
        </div>
    </form>
</x-guest-layout>
