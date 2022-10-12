<?php

namespace App\Http\Controllers;

use App\DataTables\CustomFieldsDataTable;
use App\Services\CustomFields\CustomFieldInterface;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    private $customFieldInterface;

    public function __construct(
        CustomFieldInterface $customFieldInterface
    ) {
        $this->customFieldInterface = $customFieldInterface;
    }

    public function index(Request $request, CustomFieldsDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        return $dataTable->with($data)->render('app.sites.settings.custom-fields.index', $data);
    }
}
