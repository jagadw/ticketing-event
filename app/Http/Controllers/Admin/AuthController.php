<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => 'Email atau password salah',
                ]);
            }

            if (($user->role ?? null) !== 'admin') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun tidak memiliki akses admin',
                ]);
            }

            if (($user->status ?? null) === 'Suspended') {
                throw ValidationException::withMessages([
                    'email' => 'Akun Anda telah disuspend',
                ]);
            }

            Auth::login($user, true);

            return redirect()->route('admin.dashboard');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            return back()->with('error', 'Login gagal: ' . Str::limit($e->getMessage(), 120))->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

