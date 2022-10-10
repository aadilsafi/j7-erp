<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Site;
use App\Models\Unit;
use App\Models\SalesPlan;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use App\Models\UnitStakeholder;
use App\DataTables\CustomersDataTable;
use App\Utils\Enums\StakeholderTypeEnum;
use App\DataTables\CustomerUnitsDataTable;
use App\DataTables\ViewFilesDatatable;
use App\Services\FileManagements\FileManagementInterface;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Http\Requests\File\store;
use App\Models\FileManagement;

class FileManagementController extends Controller
{
    private $fileManagementInterface;
    private $stakeholderInterface;

    public function __construct(FileManagementInterface $fileManagementInterface, StakeholderInterface $stakeholderInterface)
    {
        $this->fileManagementInterface = $fileManagementInterface;
        $this->stakeholderInterface = $stakeholderInterface;
    }

    public function customers(CustomerUnitsDataTable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'stakeholder_type' => StakeholderTypeEnum::CUSTOMER->value,
        ];

        $data['unit_ids'] = (new UnitStakeholder())->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.file-managements.customers.units.units', $data);
    }

    public function units(CustomerUnitsDataTable $dataTable, Request $request, $site_id, $customer_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'customer_id' => decryptParams($customer_id),
        ];

        $data['unit_ids'] = (new UnitStakeholder())->whereStakeholderId($data['customer_id'])->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.file-managements.customers.units.units', $data);
    }

    public function index(Request $request, $site_id, $customer_id, $unit_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'customer_id' => decryptParams($customer_id),
            'unit_id' => decryptParams($unit_id),
        ];

        return redirect()->route('sites.file-managements.customers.units.files.create', [
            'site_id' => encryptParams($data['site_id']),
            'customer_id' => encryptParams($data['customer_id']),
            'unit_id' => encryptParams($data['unit_id']),
        ]);

        return view('app.sites.file-managements.customers.units.index', $data);
    }

    public function create(Request $request, $site_id, $customer_id, $unit_id)
    {

        $data = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'customer' => (new Stakeholder())->find(decryptParams($customer_id)),
            'nextOfKin' => null,
            'unit' => (new Unit())->with(['type', 'floor'])->find(decryptParams($unit_id)),
            'user' => auth()->user(),
            // 'customer_file' => FileManagement::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->first(),
        ];

        $customer_file = FileManagement::where('unit_id', decryptParams($unit_id))->where('stakeholder_id', decryptParams($customer_id))->first();


        $data['salesPlan'] = (new SalesPlan())->with([
            'additionalCosts', 'installments', 'leadSource', 'receipts'
        ])->where([
            'status' => 1,
            'unit_id' => $data['unit']->id,
        ])->first();

        $data['salesPlan']->installments = $data['salesPlan']->installments->sortBy('installment_order');


        if (isset($customer_file)) {
            $data['image'] = $customer_file->getFirstMediaUrl('application_form_photo');
        }

        if (isset($data['customer']) && $data['customer']->parent_id > 0) {
            $data['nextOfKin'] = (new Stakeholder())->find($data['customer']->parent_id);
        }
        // && $data['customer_file']['file_action_id'] == 1
        // if (isset($data['customer_file'])) {
        //     return view('app.sites.file-managements.files.viewFile', $data);
        // }
        return view('app.sites.file-managements.files.create', $data);
    }

    public function show($site_id, $customer_id, $unit_id, $file_id)
    {
        $customer_file = FileManagement::find(decryptParams($file_id));

        $data = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'customer' => (new Stakeholder())->find(decryptParams($customer_id)),
            'nextOfKin' => null,
            'unit' => (new Unit())->with(['type', 'floor'])->find(decryptParams($unit_id)),
            'user' => auth()->user(),
            'customer_file' => FileManagement::find(decryptParams($file_id)),
        ];

        if($customer_file->file_action_id == 1){

            $data['salesPlan'] = (new SalesPlan())->with([
                'additionalCosts', 'installments', 'leadSource', 'receipts'
            ])->where([
                'status' => 1,
                'unit_id' => $data['unit']->id,
            ])->first();
        }
        else
        {
            $data['salesPlan'] = (new SalesPlan())->with([
                'additionalCosts', 'installments', 'leadSource', 'receipts'
            ])->where([
                'status' => 3,
                'unit_id' => $data['unit']->id,
            ])->first();
        }


        if (isset($customer_file)) {
            $data['image'] = $customer_file->getFirstMediaUrl('application_form_photo');
        }

        if (isset($data['customer']) && $data['customer']->parent_id > 0) {
            $data['nextOfKin'] = (new Stakeholder())->find($data['customer']->parent_id);
        }

        return view('app.sites.file-managements.files.preview', $data);
    }

    public function store(store $request, $site_id, $customer_id, $unit_id)
    {
        try {

            if (!request()->ajax()) {
                $data = $request->all();
                $file = $this->fileManagementInterface->model()->where([
                    'site_id' => decryptParams($site_id),
                    'unit_id' => $data['application_form']['unit_id'],
                    'stakeholder_id' => $data['application_form']['stakeholder_id'],
                ])
                ->where('file_action_id' , 1)
                ->first();

                if (!is_null($file) && !empty($file)) {
                    return redirect()->route('sites.file-managements.view-files', ['site_id' => encryptParams(decryptParams($site_id))])->withWarning('File already created!');
                }

                $record = $this->fileManagementInterface->store($site_id, $data);
                return redirect()->route('sites.file-managements.view-files', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.file-managements.view-files', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function viewFiles(ViewFilesDatatable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
        ];
        return $dataTable->with($data)->render('app.sites.file-managements.files.view', $data);
    }
}
