<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentVoucherDatatable;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use Illuminate\Http\Request;

class PaymentVocuherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentVoucherDatatable $dataTable, $site_id)
    {
        //
        $data = [
            'site_id' => $site_id,
        ];
        return $dataTable->with($data)->render('app.sites.payment-voucher.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($site_id)
    {
        //
        if (!request()->ajax()) {
            $data = [
                'site_id' => $site_id,
                'stakholders' => Stakeholder::all(),
            ];
            return view('app.sites.payment-voucher.create', $data);
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


    public function stakeholder_types($id)
    {
        $types = StakeholderType::where('stakeholder_id', $id)->where('status', true)->get();
        $stakeholder = Stakeholder::find($id);
        $options = '<option>Select Type</option>';
        $accounts = [];
        foreach ($types as $key => $type) {

            if ($type->type == 'C') {
                $options .= '<option value="C">Customer</option>';
                $accounts[] = [
                    'customer_payable_account' => $type->payable_account,
                ];
            } else
            if ($type->type == 'V') {
                $options .= '<option value="V">Vendor</option>';
                $accounts[] = [
                    'vendor_payable_account' => $type->payable_account,
                ];
            } else
            if ($type->type == 'D') {
                $options .= '<option value="D">Dealer</option>';
                $accounts[] = [
                    'dealer_payable_account' => $type->payable_account,
                ];
            }
        }
        return response()->json([
            'success' => true,
            'types' => $options,
            'stakeholder' => $stakeholder,
        ], 200);
    }

    public function getAccountsPayableData(Request $request, $site_id)
    {

        $stakeholder = Stakeholder::find($request->stakeholder_id);
        $stakeholder_type = StakeholderType::where('stakeholder_id', $request->stakeholder_id)->where('type', $request->stakeholder_type)->first();

        $ledger = AccountLedger::where('account_head_code', $stakeholder_type->payable_account)->get();
        $debit = collect($ledger)->sum('debit');
        $credit = collect($ledger)->sum('credit');

        $payable_amount = $credit - $debit;

        return response()->json([
            'success' => true,
            'site_id' => $site_id,
            'stakeholder' => $stakeholder,
            'stakeholder_type' => $stakeholder_type,
            'account_payable' => account_number_format($stakeholder_type->payable_account),
            'debit' => $debit,
            'credit' => $credit,
            'payable_amount' => number_format($payable_amount),
        ], 200);
    }
}
