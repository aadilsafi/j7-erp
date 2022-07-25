<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse;

class DashboardController extends Controller
{
    public function dashboard(Request $request){
        return view('app.dashboard');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
