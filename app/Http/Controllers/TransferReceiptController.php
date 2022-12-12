<?php

namespace App\Http\Controllers;

use App\DataTables\TransferReceiptsDatatable;
use App\Models\Bank;
use App\Models\FileTitleTransfer;
use App\Models\ReceiptTemplate;
use App\Models\Unit;
use Illuminate\Http\Request;

class TransferReceiptController extends Controller
{
    public function index(TransferReceiptsDatatable $dataTable, $site_id)
    {
        //
        $data = [
            'site_id' => $site_id,
            'receipt_templates' => ReceiptTemplate::all(),
        ];
        return $dataTable->with($data)->render('app.sites.file-transfer-receipts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        //
        if (!request()->ajax()) {

            // $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->receiptInterface->model()));
            // $customFields = collect($customFields)->sortBy('order');
            // $customFields = generateCustomFields($customFields);
            $transferUnits = FileTitleTransfer::where('status',1)->get();
            $data = [
                'site_id' => decryptParams($site_id),
                'transferUnits' => $transferUnits,
                'banks' => Bank::all(),
            ];
            return view('app.sites.file-transfer-receipts.create', $data);
        } else {
            abort(403);
        }
    }
}
