<?php

namespace App\Http\Controllers;

use App\DataTables\CustomersDataTable;
use App\DataTables\CustomerUnitsDataTable;
use App\Models\Site;
use App\Models\Stakeholder;
use App\Models\Unit;
use App\Models\UnitStakeholder;
use App\Services\FileManagements\FileManagementInterface;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Utils\Enums\StakeholderTypeEnum;
use Illuminate\Http\Request;

class FileManagementController extends Controller
{
    private $fileManagementInterface;
    private $stakeholderInterface;

    public function __construct(FileManagementInterface $fileManagementInterface, StakeholderInterface $stakeholderInterface)
    {
        $this->fileManagementInterface = $fileManagementInterface;
        $this->stakeholderInterface = $stakeholderInterface;
    }

    public function customers(CustomersDataTable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'stakeholder_type' => StakeholderTypeEnum::CUSTOMER->value,
        ];

        return $dataTable->with($data)->render('app.sites.file-managements.customers.customers', $data);
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
            'unit' => (new Unit())->with(['type', 'floor'])->find(decryptParams($unit_id)),
        ];

        // dd($data);

        return view('app.sites.file-managements.files.create', $data);
    }
}
