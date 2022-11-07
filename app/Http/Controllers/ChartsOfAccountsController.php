<?php

namespace App\Http\Controllers;

use App\DataTables\ChartOfAccountsDataTable;
use App\Models\AccountHead;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;

class ChartsOfAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ChartOfAccountsDataTable $dataTable, $site_id)
    {  
        $account_of_heads = AccountHead::all();
        $account_of_heads_codes = $account_of_heads->pluck('code')->toArray();
        
        try {
            $site = (new Site())->find(decryptParams($site_id))->with('siteConfiguration', 'statuses')->first();
            if ($site && !empty($site)) {
                $data = [
                    'site' => $site,
                    'account_of_heads' => $account_of_heads,
                    'account_of_heads_codes' => $account_of_heads_codes
                ];
                return $dataTable->with($data)->render('app.sites.accounts.chart_of_accounts.index', $data);
            }
            return redirect()->route('dashboard')->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('dashboard')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
