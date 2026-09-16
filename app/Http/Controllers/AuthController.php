<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['is_active'] = true;

        try {
            if (! Auth::attempt($credentials, $request->boolean('remember'))) {
                return back()->withErrors(['email' => 'Invalid credentials or inactive account.'])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        } catch (Throwable $exception) {
            if ($request->header('X-Codex-Diagnostic') === 'runtime') {
                return response()->json(['type' => $exception::class, 'message' => $exception->getMessage()], 500);
            }

            throw $exception;
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
