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
        // return $dataTable->with($data)->render('app.sites.file-managements.customers.customers', $data);

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

        ];

        $data['salesPlan'] = (new SalesPlan())->with([
            'additionalCosts', 'installments', 'leadSource', 'receipts'
        ])->where([
            'status' => 1,
            'unit_id' => $data['unit']->id,
        ])->first();
        $data['salesPlan']->installments = $data['salesPlan']->installments->sortBy('installment_order');

        if (isset($data['customer']) && $data['customer']->parent_id > 0) {
            $data['nextOfKin'] = (new Stakeholder())->find($data['customer']->parent_id);
        }


        return view('app.sites.file-managements.files.create', $data);
    }

    public function store(store $request, $site_id, $customer_id, $unit_id)
    {
        try {
            if (!request()->ajax()) {
                $data = $request->all();
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
