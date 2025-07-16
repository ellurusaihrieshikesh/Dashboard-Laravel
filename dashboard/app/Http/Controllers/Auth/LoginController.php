<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('sales.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showSwitchAccount()
    {
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();
        return view('sales.switch-account', compact('users'));
    }

    public function switchAccount(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required'
        ]);

        $user = \App\Models\User::find($request->user_id);
        
        if (!Auth::validate(['email' => $user->email, 'password' => $request->password])) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->withInput();
        }

        Auth::loginUsingId($request->user_id);
        return redirect()->route('sales.index')->with('success', 'Account switched successfully!');
    }
}
