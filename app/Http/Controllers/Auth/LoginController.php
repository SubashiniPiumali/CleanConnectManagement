<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            
        if ($user->role === 'user' && is_null($user->gender)) {
            return redirect()->route('profile'); // redirect to profile profile page
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Login successful.');
        }

           // redirect normal users
        return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logout successful.');
    }

    
}