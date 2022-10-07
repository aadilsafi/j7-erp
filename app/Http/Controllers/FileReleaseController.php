<?php

namespace App\Http\Controllers;

use File;
use Exception;
use App\Models\Unit;
use App\Models\Receipt;
use App\Models\SalesPlan;
use App\Models\FileResale;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use App\Models\FileManagement;
use App\Models\UnitStakeholder;
use App\Models\RebateIncentiveModel;
use App\DataTables\ViewFilesDatatable;
use App\Http\Requests\FileBuyBack\storeRequest;
use App\Http\Requests\FileResale\storeRequest as FileResaleStoreRequest;
use App\Models\FileResaleAttachment;
use App\Utils\Enums\StakeholderTypeEnum;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Services\FileManagements\FileActions\Resale\ResaleInterface;

class FileReleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $stakeholderInterface;
    private $resaleInterface;

    public function __construct(StakeholderInterface $stakeholderInterface, ResaleInterface $resaleInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
        $this->resaleInterface = $resaleInterface;
    }



    public function index(ViewFilesDatatable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        $data['unit_ids'] = (new UnitStakeholder())->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.file-managements.files.files-actions.file-resale.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($site_id, $unit_id, $customer_id)
    {
        if (!request()->ajax()) {
            $unit = Unit::find(decryptParams($unit_id));
            $receipts = Receipt::where('unit_id', decryptParams($unit_id))->where('sales_plan_id', $unit->salesPlan[0]['id'])->get();
            $total_paid_amount = $receipts->sum('amount_in_numbers');
            $rebate_incentive = RebateIncentiveModel::where('unit_id', $unit->id)->where('stakeholder_id', decryptParams($customer_id))->first();
            if (isset($rebate_incentive)) {
                $rebate_total = $rebate_incentive->commision_total;
            } else {
                $rebate_total = 0;
            }
            $data = [
                'site_id' => decryptParams($site_id),
                'unit' => Unit::find(decryptParams($unit_id)),
                'customer' => Stakeholder::find(decryptParams($customer_id)),
                'file' => FileManagement::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->first(),
                'total_paid_amount' => $total_paid_amount,
                'stakeholders' => $this->stakeholderInterface->getAllWithTree(),
                'stakeholderTypes' => StakeholderTypeEnum::array(),
                'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()],
                'rebate_incentive' => $rebate_incentive,
                'rebate_total' => $rebate_total,
            ];
            unset($data['emptyRecord'][0]['stakeholder_types']);
            return view('app.sites.file-managements.files.files-actions.file-resale.create', $data);
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
    public function store(FileResaleStoreRequest $request, $site_id)
    {
        //
        try {
            if (!request()->ajax()) {
                $data = $request->all();
                $record = $this->resaleInterface->store($site_id, $data);
                return redirect()->route('sites.file-managements.file-resale.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.file-managements.file-resale.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($site_id, $unit_id, $customer_id, $id)
    {
        $files_labels = FileResaleAttachment::where('file_resale_id', decryptParams($id))->get();
        $images = [];
        $unit = Unit::find(decryptParams($unit_id));
        if (isset($unit->salesPlan[0])) {
            $receipts = Receipt::where('unit_id', decryptParams($unit_id))->where('sales_plan_id', $unit->salesPlan[0]['id'])->get();
        } else {
            $receipts = Receipt::where('unit_id', decryptParams($unit_id))->where('sales_plan_id', $unit->CancelsalesPlan[0]['id'])->get();
        }
        $total_paid_amount = $receipts->sum('amount_in_numbers');
        $rebate_incentive = RebateIncentiveModel::where('unit_id', $unit->id)->where('stakeholder_id', decryptParams($customer_id))->first();
        $resale_file = (new FileResale())->find(decryptParams($id));
        if (isset($rebate_incentive)) {
            $rebate_total = $rebate_incentive->commision_total;
        } else {
            $rebate_total = 0;
        }

        foreach ($files_labels as $key => $file) {
            $image = $file->getFirstMedia('file_resale_attachments');
            $images[$key] = $image->getUrl();
        }

        $data = [
            'site_id' => decryptParams($site_id),
            'unit' => Unit::find(decryptParams($unit_id)),
            'customer' => Stakeholder::find(decryptParams($customer_id)),
            'resale_file' => $resale_file,
            'images' => $images,
            'labels' => $files_labels,
            'total_paid_amount' => $total_paid_amount,
            'buyer' => Stakeholder::find($resale_file->buyer_id),
            'rebate_incentive' => $rebate_incentive,
            'rebate_total' => $rebate_total,
        ];
        // dd($data);
        return view('app.sites.file-managements.files.files-actions.file-resale.preview', $data);
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

    public function ApproveFileResale($site_id, $unit_id, $customer_id, $file_refund_id)
    {

        $file_resale = FileResale::find(decryptParams($file_refund_id));
        $file_resale->status = 1;
        $file_resale->update();

        $unit = Unit::find(decryptParams($unit_id));
        $unit->status_id = 1;
        $unit->update();

        $file = FileManagement::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->first();
        $file->file_action_id = 5;
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

        return redirect()->route('sites.file-managements.file-resale.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess('File Resale Approved');
    }
}
