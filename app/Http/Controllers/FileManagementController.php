<?php

namespace App\Http\Controllers;

use App\DataTables\CustomersDataTable;
use App\Services\FileManagements\FileManagementInterface;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Utils\Enums\StakeholderTypeEnum;
use Illuminate\Http\Request;

class FileManagementController extends Controller
{
    private $fileManagementInterface;
    private $stakeholderInterface;

    public function __construct(FileManagementInterface $fileManagementInterface, StakeholderInterface $stakeholderInterface) {
        $this->fileManagementInterface = $fileManagementInterface;
        $this->stakeholderInterface = $stakeholderInterface;
    }

    public function customers(CustomersDataTable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'stakeholder_type' => StakeholderTypeEnum::CUSTOMER->value,
        ];

        return $dataTable->with($data)->render('app.sites.file-managements.customers', $data);
    }

    public function units(CustomersDataTable $dataTable, Request $request, $site_id, $customer_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'customer_id' => decryptParams($customer_id),
        ];

        dd($data);

        return $dataTable->with($data)->render('app.sites.file-managements.index', $data);
    }

}
