<?php

namespace App\Http\Controllers;

use App\DataTables\StakeholderInvestorsDatatable;
use App\Models\AccountLedger;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\StakeholderInvestor;
use App\Models\StakeholderType;
use App\Models\Unit;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Services\StakeholderInvestorDeals\investor_deals_interface;
use App\Utils\Enums\StakeholderTypeEnum;
use DB;
use Exception;
use Illuminate\Http\Request;

class StakeholderInvestorController extends Controller
{

    private $StakeholderInvestorInterface,$financialTransactionInterface;

    public function __construct(investor_deals_interface $StakeholderInvestorInterface ,FinancialTransactionInterface $financialTransactionInterface)
    {
        $this->StakeholderInvestorInterface = $StakeholderInvestorInterface;
        $this->financialTransactionInterface = $financialTransactionInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StakeholderInvestorsDatatable $dataTable, $site_id)
    {

        $data = [
            'site_id' => $site_id,
        ];

        return $dataTable->with($data)->render('app.sites.stakeholder-investors.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {

            $data = [
                'site_id' => decryptParams($site_id),
                'investors' => StakeholderType::where('type', 'I')->where('status', 1)->with('stakeholder')->get(),
                'country' => Country::all(),
                'leadSources' => LeadSource::where('site_id',decryptParams($site_id))->get(),
                'stakeholderTypes' => StakeholderTypeEnum::array(),
                'units'=> Unit::with('status','type')->get(),
            ];

            return view('app.sites.stakeholder-investors.create', $data);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$site_id)
    {
        //
        try {
            if (!request()->ajax()) {

                $inputs = $request->all();

                $site_id = decryptParams($site_id);

                $record = $this->StakeholderInvestorInterface->store($site_id, $inputs);
                return redirect()->route('sites.investors-deals.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.investors-deals.create', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StakeholderInvestor  $stakeholderInvestor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StakeholderInvestor  $stakeholderInvestor
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StakeholderInvestor  $stakeholderInvestor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StakeholderInvestor  $stakeholderInvestor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function checkInvestor(Request $request ,$site_id)
    {

    }

    public function approveInvestor($site_id,$id)
    {
        $transaction = $this->financialTransactionInterface->makeInvestorDealReceivableTransaction($id);
        return redirect()->route('sites.investors-deals.index', ['site_id' => decryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
    }

    public function revertInvestor($site_id,$id)
    {

    }

    public function disapproveInvestor($site_id,$id)
    {

    }
}
