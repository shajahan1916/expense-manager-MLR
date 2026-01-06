<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->where('status', 'active')
            ->where('is_deleted', 0)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials');
        }

        // Simple session for demo
        session(['user' => $user]);

        return redirect('/dashboard');
    }
}
