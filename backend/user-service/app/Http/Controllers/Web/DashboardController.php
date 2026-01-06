<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        $users = User::where('is_deleted', 0)->get();

        return view('dashboard', [
            'user' => session('user'),
            'users' => $users
        ]);
    }
}
