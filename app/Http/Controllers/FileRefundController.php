<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Unit;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use App\Models\FileManagement;
use App\Models\UnitStakeholder;
use App\Models\ReceiptDraftModel;
use App\DataTables\ViewFilesDatatable;
use App\Http\Requests\FileRefund\store;
use App\Models\FileRefund;
use App\Models\Receipt;
use App\Models\SalesPlan;
use App\Services\FileManagements\FileActions\Refund\RefundInterface;
use Psy\Readline\Hoa\FileRead;

class FileRefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    private $refundInterface;

    public function __construct(
        RefundInterface $refundInterface
    ) {
        $this->refundInterface = $refundInterface;
    }

    public function index(ViewFilesDatatable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        $data['unit_ids'] = (new UnitStakeholder())->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.file-managements.files.files-actions.file-refund.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id, $unit_id, $customer_id)
    {
        //
        if (!request()->ajax()) {

            $data = [
                'site_id' => decryptParams($site_id),
                'unit' => Unit::find(decryptParams($unit_id)),
                'customer' => Stakeholder::find(decryptParams($customer_id)),
                'file' => FileManagement::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->first(),
            ];
            return view('app.sites.file-managements.files.files-actions.file-refund.create', $data);
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
        try {
            if (!request()->ajax()) {
                $data = $request->all();
                $record = $this->refundInterface->store($site_id, $data);
                return redirect()->route('sites.file-managements.file-refund.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.file-managements.file-refund.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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

    public function ApproveFileRefund($site_id, $unit_id, $customer_id, $file_refund_id)
    {

        $file_refund = FileRefund::find(decryptParams($file_refund_id));
        $file_refund->status = 1;
        $file_refund->update();

        $unit = Unit::find(decryptParams($unit_id));
        $unit->status_id = 1;
        $unit->update();

        $file = FileManagement::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->first();
        $file->file_action_id = 2;
        $file->update();

        $salesPlan = SalesPlan::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->where('status', 1)->get();
        foreach ($salesPlan as $salesPlan) {
            $SalesPlan = SalesPlan::find($salesPlan->id);
            $SalesPlan->status = 3;
            $SalesPlan->update();
        }

        $receipt = Receipt::where('unit_id', decryptParams($unit_id))->where('status', '!=', 3)->get();
        foreach ($receipt as $receipt) {
            $Receipt = Receipt::find($receipt->id);
            $Receipt->status = 2;
            $Receipt->update();
        }

        return redirect()->route('sites.file-managements.file-refund.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess('File Refund Approved');
    }
}
