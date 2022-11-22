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
use App\DataTables\FileResaleDataTable;
use App\Http\Requests\FileBuyBack\storeRequest;
use App\Http\Requests\FileResale\storeRequest as FileResaleStoreRequest;
use App\Models\FileResaleAttachment;
use App\Models\ModelTemplate;
use App\Models\SalesPlanInstallments;
use App\Models\Template;
use App\Utils\Enums\StakeholderTypeEnum;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Services\FileManagements\FileActions\Resale\ResaleInterface;
use App\Services\CustomFields\CustomFieldInterface;
use App\Services\FileManagements\FileActions\BuyBack\BuyBackInterface;
use DB;

class FileReleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $stakeholderInterface;
    private $resaleInterface;
    private $buyBackInterface;

    public function __construct(StakeholderInterface $stakeholderInterface, ResaleInterface $resaleInterface, CustomFieldInterface $customFieldInterface, BuyBackInterface $buyBackInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
        $this->resaleInterface = $resaleInterface;
        $this->customFieldInterface = $customFieldInterface;
        $this->buyBackInterface = $buyBackInterface;
    }

    public function index(FileResaleDataTable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'fileTemplates' => (new ModelTemplate())->Model_Templates(get_class(new FileResale())),

        ];

        $data['unit_ids'] = (new UnitStakeholder())->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.file-managements.files.files-actions.file-resale.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($site_id, $unit_id, $customer_id, $file_id)
    {
        if (!request()->ajax()) {
            $unit = Unit::find(decryptParams($unit_id));
            $file = FileManagement::where('id', decryptParams($file_id))->first();
            $receipts = Receipt::where('sales_plan_id', $file->sales_plan_id)->get();
            $total_paid_amount = $receipts->sum('amount_in_numbers');
            $salesPlan = SalesPlan::find($file->sales_plan_id);
            $rebate_incentive = RebateIncentiveModel::where('unit_id', $unit->id)->where('stakeholder_id', decryptParams($customer_id))->first();
            $paid_instalments = SalesPlanInstallments::where('sales_plan_id', $unit->salesPlan[0]->id)->where('status', 'paid')->get();
            $un_paid_instalments = SalesPlanInstallments::where('sales_plan_id', $unit->salesPlan[0]->id)->where('status', 'unpaid')->get();
            $partially_paid_instalments = SalesPlanInstallments::where('sales_plan_id', $unit->salesPlan[0]->id)->where('status', 'partially_paid')->get();

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->resaleInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            if (isset($rebate_incentive)) {
                $rebate_total = $rebate_incentive->commision_total;
            } else {
                $rebate_total = 0;
            }
            $data = [
                'site_id' => decryptParams($site_id),
                'unit' => Unit::find(decryptParams($unit_id)),
                'customer' => Stakeholder::find(decryptParams($customer_id)),
                'file' => FileManagement::where('id', decryptParams($file_id))->first(),
                'total_paid_amount' => $total_paid_amount,
                'stakeholders' => $this->stakeholderInterface->getAllWithTree(),
                'stakeholderTypes' => StakeholderTypeEnum::array(),
                'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()],
                'rebate_incentive' => $rebate_incentive,
                'rebate_total' => $rebate_total,
                'paid_instalments' => $paid_instalments,
                'un_paid_instalments' => $un_paid_instalments,
                'partially_paid_instalments' => $partially_paid_instalments,
                'salesPlan' => $salesPlan,
                'customFields' => $customFields
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
        $resale_file = (new FileResale())->find(decryptParams($id));
        $unit = Unit::find(decryptParams($unit_id));
        $file = FileManagement::where('id', $resale_file->file_id)->first();
        $receipts = Receipt::where('sales_plan_id', $file->sales_plan_id)->get();
        $salesPlan = SalesPlan::find($file->sales_plan_id);
        $total_paid_amount = $receipts->sum('amount_in_numbers');
        $rebate_incentive = RebateIncentiveModel::where('unit_id', $unit->id)->where('stakeholder_id', decryptParams($customer_id))->first();

        if (isset($rebate_incentive)) {
            $rebate_total = $rebate_incentive->commision_total;
        } else {
            $rebate_total = 0;
        }

        $paid_instalments = SalesPlanInstallments::where('sales_plan_id', $file->sales_plan_id)->where('status', 'paid')->get();
        $un_paid_instalments = SalesPlanInstallments::where('sales_plan_id', $file->sales_plan_id)->where('status', 'unpaid')->get();
        $partially_paid_instalments = SalesPlanInstallments::where('sales_plan_id', $file->sales_plan_id)->where('status', 'partially_paid')->get();

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
            'paid_instalments' => $paid_instalments,
            'un_paid_instalments' => $un_paid_instalments,
            'partially_paid_instalments' => $partially_paid_instalments,
            'salesPlan' => $salesPlan,
        ];
        // dd($data['unit']['salesPlan']);
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

    public function ApproveFileResale($site_id, $unit_id, $customer_id, $file_id)
    {
        DB::transaction(function () use ($site_id, $unit_id, $customer_id, $file_id) {

            // Account ledger transaction
            // $transaction = $this->financialTransactionInterface->makeBuyBackTransaction($site_id, $unit_id, $customer_id, $file_id);

            $file_resale = FileResale::where('file_id', decryptParams($file_id))->first();
            $file_resale->status = 1;
            $file_resale->update();

            $unit = Unit::find(decryptParams($unit_id));
            $unit->status_id = 6;
            $unit->update();

            $file =  FileManagement::find(decryptParams($file_id));
            $file->file_action_id = 5;
            $file->update();

            // $salesPlan = SalesPlan::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->where('status', 1)->get();
            // foreach ($salesPlan as $salesPlan) {
            //     $SalesPlan = SalesPlan::find($salesPlan->id);
            //     $SalesPlan->status = 3;
            //     $SalesPlan->update();
            // }

            $receipt = Receipt::where('unit_id', decryptParams($unit_id))->where('status', '!=', 3)->get();
            foreach ($receipt as $receipt) {
                $Receipt = Receipt::find($receipt->id);
                $Receipt->status = 2;
                $Receipt->update();
            }
        });
        return redirect()->route('sites.file-managements.file-resale.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess('File Resale Approved');
    }

    public function printPage($site_id, $file_id, $template_id)
    {

        $file_refund = (new FileResale())->find(decryptParams($file_id));
        // dd($file_refund ,  json_decode($file_refund['stakeholder_data']));

        $template = Template::find(decryptParams($template_id));

        $data = [
            'site_id' => decryptParams($site_id),
            'file_refund' => $file_refund,
            'template' => $template,
            'stakeholder' => json_decode($file_refund['stakeholder_data']),
            'unit' => Unit::find($file_refund['unit_id']),
            'salesPlan' => SalesPlan::where('unit_id', $file_refund['unit_id'])->first(),
        ];

        $printFile = 'app.sites.file-managements.files.templates.' . $template->slug;

        return view($printFile, compact('data'));
    }
}
