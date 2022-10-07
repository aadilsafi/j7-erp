<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountsRecoveryController extends Controller
{
    public function dashboard(Request $request, $site_id)
    {
        return view('app.sites.accounts.recovery.dashboard');
    }

    public function calender(Request $request, $site_id)
    {
        return view('app.sites.accounts.recovery.calender');
    }

    public function salesPlan(Request $request, $site_id)
    {
        return $_SERVER;
        $site_id = decryptParams($site_id);
        return view('app.sites.accounts.recovery.sales-plan', ['site_id' => $site_id]);
    }
}
