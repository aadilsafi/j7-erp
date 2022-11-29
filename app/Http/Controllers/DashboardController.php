<?php

namespace App\Http\Controllers;

use App\Models\SalesPlanInstallments;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('app.dashboard');
    }

    public function cacheFlush(Request $request)
    {
        cache()->flush();
        return redirect()->back()->withSuccess('Site cache refreshed.');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }

    public function dasboard_chart(Request $request)
    {
        return 'hello';
        $installment = SalesPlanInstallments::all();
        return $installment;
    }
}