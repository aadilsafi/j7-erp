<?php

namespace App\Http\Controllers;

use App\DataTables\InvestorDealsReceiptsDatatable;
use App\Models\Bank;
use App\Models\FileTitleTransfer;
use App\Models\InvsetorDealsReceipt;
use App\Models\StakeholderInvestor;
use App\Models\UnitStakeholder;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Services\InvestorDealsReceipt\DealReceiptInterface;
use Exception;
use Illuminate\Http\Request;

class InvsetorDealsReceiptController extends Controller
{
    private $DealReceiptInterface,$financialTransactionInterface;

    public function __construct(DealReceiptInterface $DealReceiptInterface ,FinancialTransactionInterface $financialTransactionInterface)
    {
        $this->DealReceiptInterface = $DealReceiptInterface;
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InvestorDealsReceiptsDatatable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'banks' => Bank::all(),
        ];
        $data['unit_ids'] = (new UnitStakeholder())->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.investor-deal-receipts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {

            $investor_deals = StakeholderInvestor::where(['status'=>'approved', 'deal_status'=>'open' ,'paid_status'=>0])->get();

            $data = [
                'site_id' => decryptParams($site_id),
                'banks' => Bank::all(),
                'investor_deals'=>$investor_deals,
            ];
            return view('app.sites.investor-deal-receipts.create', $data);
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
        // try {
            if (!request()->ajax()) {

                $inputs = $request->all();

                $site_id = decryptParams($site_id);

                $record = $this->DealReceiptInterface->store($site_id, $inputs);
                return redirect()->route('sites.investor-deals-receipts.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        // } catch (Exception $ex) {
        //     return redirect()->route('sites.investor-deals-receipts.create', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvsetorDealsReceipt  $invsetorDealsReceipt
     * @return \Illuminate\Http\Response
     */
    public function show(InvsetorDealsReceipt $invsetorDealsReceipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvsetorDealsReceipt  $invsetorDealsReceipt
     * @return \Illuminate\Http\Response
     */
    public function edit(InvsetorDealsReceipt $invsetorDealsReceipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvsetorDealsReceipt  $invsetorDealsReceipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvsetorDealsReceipt $invsetorDealsReceipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvsetorDealsReceipt  $invsetorDealsReceipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvsetorDealsReceipt $invsetorDealsReceipt)
    {
        //
    }

    public function getInvestorDealsData(Request $request)
    {

        $deals = StakeholderInvestor::find($request->deal_id);
        return response()->json([
            'success' => true,
            'receivable_amount' => $deals->total_received_amount,
        ], 200);

    }

    public function makeActiveSelected(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->financialTransactionInterface->makeInvestorReceiptActive($site_id, $request->chkRole);

                    if ($record) {
                        return redirect()->route('sites.investor-deals-receipts.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
                    } else {
                        return redirect()->route('sites.investor-deals-receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.investor-deals-receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
