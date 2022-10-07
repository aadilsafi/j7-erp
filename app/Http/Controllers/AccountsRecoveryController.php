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
        $MAC = exec('getmac');

        // Storing 'getmac' value in $MAC
        $MAC = strtok($MAC, ' ');

        // Updating $MAC value using strtok function,
        // strtok is used to split the string into tokens
        // split character of strtok is defined as a space
        // because getmac returns transport name after
        // MAC address
        return "MAC address of Server is: $MAC";
        return $_SERVER;
        $site_id = decryptParams($site_id);
        return view('app.sites.accounts.recovery.sales-plan', ['site_id' => $site_id]);
    }
}
