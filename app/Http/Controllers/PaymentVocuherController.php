<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentVoucherDatatable;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Bank;
use App\Models\PaymentVocuher;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Services\PaymentVoucher\paymentInterface;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentVoucher\store;

class PaymentVocuherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $PaymentVoucherInterface, $financialTransactionInterface;

    public function __construct(paymentInterface $PaymentVoucherInterface, FinancialTransactionInterface $financialTransactionInterface)
    {
        $this->PaymentVoucherInterface = $PaymentVoucherInterface;
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

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
                'banks' => Bank::all(),
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
    public function store(store $request, $site_id)
    {
        //
        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                $site_id = decryptParams($site_id);
                $record = $this->PaymentVoucherInterface->store($site_id, $inputs);
                return redirect()->route('sites.payment-voucher.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.payment-voucher.create', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
        }
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
        $options = '<option value="">Select Type</option>';
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


        if ($request->stakeholder_type == 'C') {
            $payment_voucher = PaymentVocuher::where('customer_id', $request->stakeholder_id)->where('status', 0)->get();
            if (count($payment_voucher) > 0) {
                $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
                $debit = (float)$debit + (float)$debit_value;
            }

            // // For cheque inactive
            // $payment_voucher = PaymentVocuher::where('customer_id', $request->stakeholder_id)->where('status', 1)->where('cheque_status', 0)->get();

            // if (count($payment_voucher) > 0) {
            //     $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            //     $debit = (float)$debit + (float)$debit_value;
            //     // dd($debit,$payment_voucher);
            // }
        }

        if ($request->stakeholder_type == 'D') {
            $payment_voucher = PaymentVocuher::where('dealer_id', $request->stakeholder_id)->where('status', 0)->get();
            if (count($payment_voucher) > 0) {
                $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
                $debit = (float)$debit + (float)$debit_value;
            }

            // // For cheque inactive
            // $payment_voucher = PaymentVocuher::where('dealer_id', $request->stakeholder_id)->where('status', 1)->where('cheque_status', 0)->get();

            // if (count($payment_voucher) > 0) {
            //     $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            //     $debit = (float)$debit + (float)$debit_value;
            // }
        }

        if ($request->stakeholder_type == 'V') {
            $payment_voucher = PaymentVocuher::where('vendor_id', $request->stakeholder_id)->where('status', 0)->get();
            if (count($payment_voucher) > 0) {
                $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
                $debit = (float)$debit + (float)$debit_value;
            }

            // // For cheque inactive
            // $payment_voucher = PaymentVocuher::where('vendor_id', $request->stakeholder_id)->where('status', 1)->where('cheque_status', 0)->get();

            // if (count($payment_voucher) > 0) {
            //     $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            //     $debit = (float)$debit + (float)$debit_value;
            // }
        }

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

    public function approvePaymentVoucher($site_id, $id)
    {

        DB::transaction(function () use ($site_id, $id) {

            $payment_voucher = PaymentVocuher::find(decryptParams($id));

            if ($payment_voucher->stakeholder_type == 'C') {
                $stakeholder_id = $payment_voucher->customer_id;
            }
            if ($payment_voucher->stakeholder_type == 'D') {
                $stakeholder_id = $payment_voucher->dealer_id;
            }
            if ($payment_voucher->stakeholder_type == 'V') {
                $stakeholder_id = $payment_voucher->vendor_id;
            }

            $transaction = $this->financialTransactionInterface->makePaymentVoucherTransaction($payment_voucher, $stakeholder_id);
            $payment_voucher->status = 1;
            $payment_voucher->update();
        });
        return redirect()->route('sites.payment-voucher.index', ['site_id' => decryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
    }

    public function activeCheque($site_id, $id)
    {
        DB::transaction(function () use ($site_id, $id) {
            $payment_voucher = PaymentVocuher::find(decryptParams($id));
            if ($payment_voucher->status == 1) {
                $transaction = $this->financialTransactionInterface->makePaymentVoucherChequeActiveTransaction($payment_voucher);
                $payment_voucher->cheque_status = 1;
                $payment_voucher->update();
            }
        });
        return redirect()->route('sites.payment-voucher.index', ['site_id' => decryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
    }
}
