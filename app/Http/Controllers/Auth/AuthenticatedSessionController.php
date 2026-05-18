<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

   public function store(LoginRequest $request): RedirectResponse
{
    $sessionCaptcha = session('captcha');
    $inputCaptcha = strtoupper(trim($request->captcha));

    if (!$sessionCaptcha || $inputCaptcha !== strtoupper($sessionCaptcha)) {
        return back()
            ->withErrors(['captcha' => 'Kode captcha salah, coba lagi.'])
            ->withInput($request->except('password', 'captcha'));
    }

    // Hapus captcha dari session setelah dipakai
    session()->forget('captcha');

    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();

    if ($user->user_type === 'admin') {
        return redirect()->intended(route('admin.dashboard'));
    } elseif ($user->user_type === 'kacab') {
    return redirect()->intended(route('kacab.dashboard'));
}

    return redirect()->intended(route('auditor.dashboard'));
}

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}