<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = trim((string)($request->input('username') ?: $request->input('email') ?: ''));

        if (empty($identifier)) {
            throw ValidationException::withMessages([
                'username' => 'Silakan masukkan username atau email.',
            ]);
        }

        $password = (string) $request->input('password');
        $remember = $request->boolean('remember');

        // Rate limiting key
        $throttleKey = Str::transliterate(Str::lower($identifier).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Try username first, then email, or vice versa
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $authenticated = false;

        if ($isEmail) {
            $authenticated = Auth::attempt(['email' => $identifier, 'password' => $password], $remember)
                || Auth::attempt(['username' => $identifier, 'password' => $password], $remember);
        } else {
            $authenticated = Auth::attempt(['username' => $identifier, 'password' => $password], $remember)
                || Auth::attempt(['email' => $identifier, 'password' => $password], $remember);
        }

        if (! $authenticated) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'username' => 'Kredensial yang Anda masukkan salah. Silakan periksa username/email dan password.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
