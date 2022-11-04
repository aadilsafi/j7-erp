<?php

namespace App\Http\Controllers;

use App\DataTables\TrialBalanceDataTable;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;

class TrialBalanceController extends Controller
{
    //

    public function index(TrialBalanceDataTable $dataTable, $site_id)
    {
        // dd($dataTable);
        // dd((new TrialBalanceDataTable)->query($site_id));
        try {
            $site = (new Site())->find(decryptParams($site_id))->with('siteConfiguration', 'statuses')->first();
            if ($site && !empty($site)) {
                $data = [
                    'site' => $site,
                ];
                return $dataTable->with($data)->render('app.sites.accounts.trial_balance.index', $data);
            }
            return redirect()->route('dashboard')->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('dashboard')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
