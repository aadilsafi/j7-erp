<?php

namespace App\Http\Controllers;

use App\Models\SalesPlanInstallments;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $installment = SalesPlanInstallments::when(($request->months_id == 'months12'), function ($query) {
            $query->whereMonth('created_at', '>=', Carbon::now()->subMonth(12));
            return $query;
        })
            ->when(($request->months_id == 'months6'), function ($query) {
                $query->whereMonth('created_at', '>=', Carbon::now()->subMonth(6));
                return $query;
            })
            ->when(($request->months_id == 'months1'), function ($query) {
                $query->whereMonth('created_at', '>=', Carbon::now()->subMonth());
                return $query;
            })
            ->when(($request->months_id == 'months3'), function ($query) {
                $query->whereMonth('created_at', '>=', Carbon::now()->subMonth(3));
                return $query;
            })->get();

        $amount = $installment->pluck('amount')->sum();
        $paid_amount = $installment->pluck('paid_amount')->sum();
        $remaining_amount = $installment->pluck('remaining_amount')->sum();

        $data = [
            'amount' => $amount,
            'paid_amount' => $paid_amount,
            'remaining_amount' => $remaining_amount
        ];

        return [
            'status' => true,
            'message' => ' filter by date mouth or year',
            'data' => $data
        ];
    }
}