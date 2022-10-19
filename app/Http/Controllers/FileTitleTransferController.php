<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Unit;
use App\Models\Receipt;
use App\Models\SalesPlan;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use App\Models\FileManagement;
use App\Models\UnitStakeholder;
use App\Models\FileTitleTransfer;
use App\Models\RebateIncentiveModel;
use App\DataTables\FileTitleTransferDataTable;
use App\Http\Requests\FileTitleTransfer\storeRequest;
use App\Utils\Enums\StakeholderTypeEnum;
use App\Models\FileTitleTransferAttachment;
use App\Models\ModelTemplate;
use App\Models\Template;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Services\FileManagements\FileActions\TitleTransfer\TitleTransferInterface;
use Maatwebsite\Excel\Imports\ModelManager;
use App\Services\CustomFields\CustomFieldInterface;

class FileTitleTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $stakeholderInterface;
    private $titleTransferInterface;

    public function __construct(StakeholderInterface $stakeholderInterface, TitleTransferInterface $titleTransferInterface, CustomFieldInterface $customFieldInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
        $this->titleTransferInterface = $titleTransferInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    public function index(FileTitleTransferDataTable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'fileTemplates' => (new ModelTemplate())->Model_Templates(get_class(new FileTitleTransfer())),
        ];

        $data['unit_ids'] = (new UnitStakeholder())->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.file-managements.files.files-actions.file-title-transfer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($site_id, $unit_id, $customer_id,$file_id)
    {
        if (!request()->ajax()) {
            $unit = Unit::find(decryptParams($unit_id));
            $file = FileManagement::where('id', decryptParams($file_id))->first();
            $receipts = Receipt::where('sales_plan_id', $file->sales_plan_id)->get();
            $total_paid_amount = $receipts->sum('amount_in_numbers');
            $salesPlan = SalesPlan::find($file->sales_plan_id);
            $rebate_incentive = RebateIncentiveModel::where('unit_id', $unit->id)->where('stakeholder_id', decryptParams($customer_id))->first();
            if (isset($rebate_incentive)) {
                $rebate_total = $rebate_incentive->commision_total;
            } else {
                $rebate_total = 0;
            }

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->titleTransferInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

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
                'salesPlan'=>$salesPlan,
                'customFields' => $customFields
            ];
            unset($data['emptyRecord'][0]['stakeholder_types']);
            return view('app.sites.file-managements.files.files-actions.file-title-transfer.create', $data);
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
    public function store(storeRequest $request, $site_id)
    {
        //
        try {
            if (!request()->ajax()) {
                $data = $request->all();
                $record = $this->titleTransferInterface->store($site_id, $data);
                return redirect()->route('sites.file-managements.file-title-transfer.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.file-managements.file-title-transfer.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
        $files_labels = FileTitleTransferAttachment::where('file_title_transfer_id', decryptParams($id))->get();
        $images = [];
        $unit = Unit::find(decryptParams($unit_id));
        $file_title_transfer = FileTitleTransfer::find(decryptParams($id));
        $file = FileManagement::where('id', $file_title_transfer->file_id)->first();
        $receipts = Receipt::where('sales_plan_id', $file->sales_plan_id)->get();
        $salesPlan = SalesPlan::find($file->sales_plan_id);

        $total_paid_amount = $receipts->sum('amount_in_numbers');
        $transfer_file = (new FileTitleTransfer())->find(decryptParams($id));
        if (isset($rebate_incentive)) {
            $rebate_total = $rebate_incentive->commision_total;
        } else {
            $rebate_total = 0;
        }

        foreach ($files_labels as $key => $file) {
            $image = $file->getFirstMedia('file_title_transfer_attachments');
            $images[$key] = $image->getUrl();
        }

        $data = [
            'site_id' => decryptParams($site_id),
            'unit' => Unit::find(decryptParams($unit_id)),
            'customer' => Stakeholder::find(decryptParams($customer_id)),
            'transfer_file' => $transfer_file,
            'images' => $images,
            'labels' => $files_labels,
            'total_paid_amount' => $total_paid_amount,
            'titleTransferPerson' => Stakeholder::find($transfer_file->transfer_person_id),
            'salesPlan'=>$salesPlan,
        ];

        return view('app.sites.file-managements.files.files-actions.file-title-transfer.preview', $data);
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

    public function ApproveFileTitleTransfer($site_id, $unit_id, $customer_id, $file_title_transfer_id)
    {

        $file_title_transfer = FileTitleTransfer::find(decryptParams($file_title_transfer_id));
        $file_title_transfer->status = 1;
        $file_title_transfer->update();

        $stakeholder = Stakeholder::find($file_title_transfer->transfer_person_id);

        $file = FileManagement::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->first();
        $file->stakeholder_id = $stakeholder->id;
        $file->stakeholder_data = json_encode($stakeholder);
        $file->update();

        $salesPlan = SalesPlan::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->where('status', 1)->get();
        foreach ($salesPlan as $salesPlan) {
            $SalesPlan = SalesPlan::find($salesPlan->id);
            $SalesPlan->stakeholder_id = $stakeholder->id;
            $SalesPlan->update();
        }

        // $receipt = Receipt::where('unit_id', decryptParams($unit_id))->where('status', '!=', 3)->get();
        // foreach ($receipt as $receipt) {
        //     $Receipt = Receipt::find($receipt->id);
        //     $Receipt->name = $stakeholder->full_name;
        //     $Receipt->cnic = $stakeholder->cnic;
        //     $Receipt->phone_no = $stakeholder->contact;
        //     $Receipt->update();
        // }

        return redirect()->route('sites.file-managements.file-title-transfer.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess('File Title Transfer Approved');
    }

    public function printPage($site_id, $file_id, $template_id)
    {

        $file_refund = (new FileTitleTransfer())->find(decryptParams($file_id));

        $template = Template::find(decryptParams($template_id));

        $data = [
            'site_id' => decryptParams($site_id),
        ];

        $printFile = 'app.sites.file-managements.files.templates.' . $template->slug;

        return view($printFile, compact('data'));
    }
}
