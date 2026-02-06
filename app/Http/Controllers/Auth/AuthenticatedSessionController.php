<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
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
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Proses autentikasi (Email & Password dicek di sini)
            $request->authenticate();
        } catch (ValidationException $e) {
            // Jika salah, kembali ke login dengan pesan error
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->with('error', 'Email atau password yang Anda masukkan salah.');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Notifikasi Sukses
        session()->flash('success', 'Selamat datang kembali, ' . $user->name . '!');

        // Pengecekan Role berdasarkan slug
        if ($user->roles()->where('slug', 'admin')->exists()) {
            return redirect()->intended(route('admin.dashboard'));
        } 
        
        if ($user->roles()->where('slug', 'user')->exists()) {
            return redirect()->intended(route('visitor.index')); 
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }
}