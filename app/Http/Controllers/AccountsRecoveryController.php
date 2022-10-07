<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountsRecoveryController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('app.sites.accounts.recovery.dashboard');
    }

    public function calender(Request $request)
    {
        return view('app.sites.accounts.recovery.calender');
    }
}
