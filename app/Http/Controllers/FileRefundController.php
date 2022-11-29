<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Unit;
use App\Models\Receipt;
use App\Models\SalesPlan;
use App\Models\FileRefund;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use App\Models\FileManagement;
use Psy\Readline\Hoa\FileRead;
use App\Models\UnitStakeholder;
use App\Models\ReceiptDraftModel;
use App\Models\FileRefundAttachment;
use App\DataTables\FileRefundDataTable;
use App\Http\Requests\FileRefund\store;
use App\Models\AccountAction;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\ModelTemplate;
use App\Models\StakeholderType;
use App\Models\Template;
use App\Models\Type;
use App\Services\FileManagements\FileActions\Refund\RefundInterface;
use App\Services\CustomFields\CustomFieldInterface;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\{URL, Auth, DB, Notification};

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
        RefundInterface $refundInterface,
        CustomFieldInterface $customFieldInterface
    ) {
        $this->refundInterface = $refundInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    public function index(FileRefundDataTable $dataTable, Request $request, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'fileTemplates' => (new ModelTemplate())->Model_Templates(get_class(new FileRefund())),
        ];

        $data['unit_ids'] = (new UnitStakeholder())->whereSiteId($data['site_id'])->get()->pluck('unit_id')->toArray();

        return $dataTable->with($data)->render('app.sites.file-managements.files.files-actions.file-refund.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id, $unit_id, $customer_id, $file_id)
    {
        // dd(decryptParams($site_id),decryptParams($unit_id),decryptParams($customer_id));
        //
        if (!request()->ajax()) {
            $unit = Unit::find(decryptParams($unit_id));
            $file = FileManagement::where('id', decryptParams($file_id))->first();
            $receipts = Receipt::where('sales_plan_id', $file->sales_plan_id)->where('status', 1)->get();
            $total_paid_amount = $receipts->sum('amount_in_numbers');
            $salesPlan = SalesPlan::find($file->sales_plan_id);

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->refundInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site_id' => decryptParams($site_id),
                'unit' => Unit::find(decryptParams($unit_id)),
                'customer' => Stakeholder::find(decryptParams($customer_id)),
                'file' => FileManagement::where('id', decryptParams($file_id))->first(),
                'total_paid_amount' => $total_paid_amount,
                'salesPlan' => $salesPlan,
                'customFields' => $customFields

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
    public function show($site_id, $unit_id, $customer_id, $id)
    {
        $files_labels = FileRefundAttachment::where('file_refund_id', decryptParams($id))->get();
        $images = [];
        $file_refund = FileRefund::find(decryptParams($id));
        $unit = Unit::find(decryptParams($unit_id));
        $file = FileManagement::where('id', $file_refund->file_id)->first();
        $receipts = Receipt::where('sales_plan_id', $file->sales_plan_id)->where('status', 1)->get();
        $salesPlan = SalesPlan::find($file->sales_plan_id);
        $total_paid_amount = $receipts->sum('amount_in_numbers');
        foreach ($files_labels as $key => $file) {
            $image = $file->getFirstMedia('file_refund_attachments');
            $images[$key] = $image->getUrl();
        }

        $data = [
            'site_id' => decryptParams($site_id),
            'unit' => Unit::find(decryptParams($unit_id)),
            'customer' => Stakeholder::find(decryptParams($customer_id)),
            'refund_file' => (new FileRefund())->find(decryptParams($id)),
            'images' => $images,
            'labels' => $files_labels,
            'total_paid_amount' => $total_paid_amount,
            'salesPlan' => $salesPlan,
        ];

        return view('app.sites.file-managements.files.files-actions.file-refund.preview', $data);
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

    public function ApproveFileRefund($site_id, $unit_id, $customer_id, $file_id)
    {
        DB::transaction(function () use ($site_id, $unit_id, $customer_id, $file_id) {
            //Refund Account legders
            $stakeholderType = StakeholderType::where(['stakeholder_id' => decryptParams($customer_id), 'type' => 'C'])->first();
            // select receiveable account against unit
            foreach ($stakeholderType->receivable_account as $receivable_account) {
                if ($receivable_account['unit_id'] == (int)decryptParams($unit_id)) {
                    $unit_account_code = $receivable_account['unit_account'];
                    $customer_receivable_account_code = $receivable_account['account_code'];
                }
            }

            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }

            $customer_payable_account_code = $stakeholderType->payable_account;
            $refundAccount = AccountHead::where('name', 'Refund Account')->first();
            $refundAccountCode = $refundAccount->code;

            // if payable account code is not set
            if ($customer_payable_account_code == null) {
                $stakeholderType = StakeholderType::where(['type' => 'C'])->where('payable_account', '!=', null)->get();
                $stakeholderType = collect($stakeholderType)->last();
                if ($stakeholderType == null) {
                    $customer_payable_account_code = '20201010001003';
                } else {
                    $customer_payable_account_code = $stakeholderType->payable_account + 1;
                }

                // add payable code to stakeholder type
                $stakeholderPayable = StakeholderType::where(['stakeholder_id' => decryptParams($customer_id), 'type' => 'C'])->first();
                $stakeholderPayable->payable_account =  (string)$customer_payable_account_code;
                $stakeholderPayable->update();

                $stakeholder = Stakeholder::find(decryptParams($customer_id));
                $accountCodeData = [
                    'site_id' => 1,
                    'modelable_id' => 1,
                    'modelable_type' => 'App\Models\StakeholderType',
                    'code' => $customer_payable_account_code,
                    'name' =>  $stakeholder->full_name . ' Customer A/P',
                    'level' => 5,
                ];

                (new AccountHead())->create($accountCodeData);
            }

            $file_refund = FileRefund::where('file_id', decryptParams($file_id))->first();
            $file_refund->status = 1;
            $file_refund->update();

            $receiptDiscounted = Receipt::where('sales_plan_id', $file_refund->sales_plan_id)->where('status', 1)->get();
            $discounted_amount = collect($receiptDiscounted)->sum('discounted_amount');
            $discountedValue = (float)$discounted_amount;
            $amount = (float)$refunded_amount - (float)$discounted_amount;


            $salesPlan = SalesPlan::find($file_refund->sales_plan_id);
            // after minus payable amount from sales plan
            $refunded_amount = str_replace(',', '', $file_refund->amount_to_be_refunded);
            $payable_amount = (int)$salesPlan->total_price - (int)$refunded_amount;
            $accountActionName = AccountAction::find(5)->name;
            $ledgerData = [
                // Refund (3 entries in legder)
                // Refund account entry
                [
                    'site_id' => 1,
                    'account_head_code' => $refundAccountCode,
                    'account_action_id' => 5,
                    'credit' => 0,
                    'debit' => $salesPlan->total_price,
                    'balance' => $salesPlan->total_price,
                    'nature_of_account' => 'JRF',
                    'sales_plan_id' => $file_refund->sales_plan_id,
                    'file_refund_id' => $file_refund->id,
                    'status' => true,
                    'origin_number' => $origin_number,
                    'origin_name' => 'RF-' . $origin_number,
                    'created_date' => $file_refund->updated_at,
                ],
                // Cutomer AR entry
                [
                    'site_id' => 1,
                    'account_head_code' => $customer_receivable_account_code,
                    'account_action_id' => 5,
                    'credit' => $payable_amount,
                    'debit' => 0,
                    'balance' => $refunded_amount,
                    'nature_of_account' => 'JRF',
                    'sales_plan_id' => $file_refund->sales_plan_id,
                    'file_refund_id' => $file_refund->id,
                    'status' => true,
                    'origin_number' => $origin_number,
                    'origin_name' => 'RF-' . $origin_number,
                    'created_date' => $file_refund->updated_at,
                ],
            ];

            // if discounted acomunt available
            if (isset($discounted_amount) && $discountedValue > 0) {

                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;
                $ledgerData[] = [
                    [
                        'site_id' => 1,
                        'account_head_code' => $cashDiscountAccount,
                        'account_action_id' => 5,
                        'credit' => $discounted_amount,
                        'debit' => 0,
                        'balance' => $discounted_amount,
                        'nature_of_account' => 'JRF',
                        'sales_plan_id' => $file_refund->sales_plan_id,
                        'file_refund_id' => $file_refund->id,
                        'status' => true,
                        'origin_number' => $origin_number,
                        'origin_name' => 'RF-' . $origin_number,
                        'created_date' => $file_refund->updated_at,
                    ],
                ];
            }

            $ledgerData[] = [
                // Customer AP entry
                [
                    'site_id' => 1,
                    'account_head_code' => $customer_payable_account_code,
                    'account_action_id' => 5,
                    'credit' => $discountedValue > 0 ? $amount : $refunded_amount ,
                    'debit' => 0,
                    'balance' => $refunded_amount,
                    'nature_of_account' => 'JRF',
                    'sales_plan_id' => $file_refund->sales_plan_id,
                    'file_refund_id' => $file_refund->id,
                    'status' => true,
                    'origin_number' => $origin_number,
                    'origin_name' => 'RF-' . $origin_number,
                    'created_date' => $file_refund->updated_at,
                ],
                // Payment Voucher
                [
                    'site_id' => 1,
                    'account_head_code' => $customer_payable_account_code,
                    'account_action_id' => 4,
                    'credit' => 0,
                    'debit' => $discountedValue > 0 ? $amount : $refunded_amount ,
                    'balance' => $refunded_amount,
                    'nature_of_account' => 'JRF',
                    'sales_plan_id' => $file_refund->sales_plan_id,
                    'file_refund_id' => $file_refund->id,
                    'status' => true,
                    'origin_number' => $origin_number,
                    'origin_name' => 'RF-' . $origin_number,
                    'created_date' => $file_refund->updated_at,
                ],
                // cash at office 10209020001001
                [
                    'site_id' => 1,
                    'account_head_code' => '10209020001001',
                    'account_action_id' => 4,
                    'credit' => $discountedValue > 0 ? $amount : $refunded_amount ,
                    'debit' => 0,
                    'balance' => 0,
                    'nature_of_account' => 'JRF',
                    'sales_plan_id' => $file_refund->sales_plan_id,
                    'file_refund_id' => $file_refund->id,
                    'status' => true,
                    'origin_number' => $origin_number,
                    'origin_name' => 'RF-' . $origin_number,
                    'created_date' => $file_refund->updated_at,
                ],

            ];
            // insert all above entries
            foreach ($ledgerData as $item) {
                (new AccountLedger())->create($item);
            }

            $unit = Unit::find(decryptParams($unit_id));
            $unit->status_id = 1;
            $unit->update();

            $file = FileManagement::find(decryptParams($file_id));
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
        });
        return redirect()->route('sites.file-managements.file-refund.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess('File Refund Approved');
    }

    public function printPage($site_id, $file_id, $template_id)
    {

        $file_refund = (new FileRefund())->find(decryptParams($file_id));
        $template = Template::find(decryptParams($template_id));

        $file = FileManagement::where('id', $file_refund->file_id)->first();
        $receipts = Receipt::where('sales_plan_id', $file->sales_plan_id)->where('status', 1)->get();
        $salesPlan = SalesPlan::find($file->sales_plan_id);
        $total_paid_amount = $receipts->sum('amount_in_numbers');
        $unit_data = json_decode($file_refund->unit_data);
        $unitType = Type::find($unit_data->type_id);

        $data = [
            'unit' => $unit_data,
            'unitType' => $unitType->name,
            'customer' => json_decode($file_refund->stakeholder_data),
            'refund_file' => $file_refund,
            'total_paid_amount' => $total_paid_amount,
            'salesPlan' => $salesPlan,
        ];

        $printFile = 'app.sites.file-managements.files.templates.' . $template->slug;

        return view($printFile, $data);
    }
}
