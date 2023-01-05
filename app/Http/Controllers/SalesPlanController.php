<?php

namespace App\Http\Controllers;

use App\DataTables\ImportSalesPlanDataTable;
use App\DataTables\SalesPlanDataTable;
use App\Exceptions\GeneralException;
use App\Imports\SalesPlanImport;
use App\Models\{Country, SalesPlan, Floor, LeadSource, Site, Stakeholder, TempSalePlan, Unit, User};
use Illuminate\Http\Request;
use App\Models\SalesPlanTemplate;
use App\Services\{
    SalesPlan\Interface\SalesPlanInterface,
    Stakeholder\Interface\StakeholderInterface,
};
use Carbon\{Carbon, CarbonPeriod};
use App\Services\LeadSource\LeadSourceInterface;
use App\Services\CustomFields\CustomFieldInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Notifications\ApprovedSalesPlanNotification;
use App\Services\AdditionalCosts\AdditionalCostInterface;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Utils\Enums\NatureOfAccountsEnum;
use App\Utils\Enums\StakeholderTypeEnum;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Redirect;
use Str;
use Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesPlanController extends Controller
{
    private $salesPlanInterface, $additionalCostInterface, $stakeholderInterface, $leadSourceInterface, $financialTransactionInterface,$customFieldInterface;

    public function __construct(
        SalesPlanInterface $salesPlanInterface,
        AdditionalCostInterface $additionalCostInterface,
        StakeholderInterface $stakeholderInterface,
        LeadSourceInterface $leadSourceInterface,
        CustomFieldInterface $customFieldInterface,
        FinancialTransactionInterface $financialTransactionInterface
    ) {
        $this->salesPlanInterface = $salesPlanInterface;
        $this->additionalCostInterface = $additionalCostInterface;
        $this->stakeholderInterface = $stakeholderInterface;
        $this->leadSourceInterface = $leadSourceInterface;
        $this->customFieldInterface = $customFieldInterface;
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SalesPlanDataTable $dataTable, $site_id, $floor_id, $unit_id)
    {
        $data = [
            'site' => decryptParams($site_id),
            'floor' => decryptParams($floor_id),
            'unit' => decryptParams($unit_id) > 0 ? (new Unit())->find(decryptParams($unit_id)) : [],
            'salesPlanTemplates' => (new SalesPlanTemplate())->all(),
        ];
        return $dataTable->with($data)->render('app.sites.SalesPlan.index', $data);
    }

    public function inLeftbar(Request $request, SalesPlanDataTable $dataTable, $site_id)
    {
        $data = [
            'site' => decryptParams($site_id),
            'salesPlanTemplates' => (new SalesPlanTemplate())->all(),
        ];
        return $dataTable->with($data)->render('app.sites.SalesPlan.index', $data);
    }

    public function generateSalesPlan(Request $request, $site_id, $stakeholder_id, $crm_lead)
    {
        $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->salesPlanInterface->model()));
        $customFields = collect($customFields)->sortBy('order');
        $customFields = generateCustomFields($customFields);

        $crm_lead = Stakeholder::where('crm_id', decryptParams($crm_lead))->first();

        $data = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'unit' => (new Unit())->with('status', 'type')->where('has_sub_units', false)->where('status_id', 1)->orWhere('status_id', 6)->get(),
            'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
            'stakeholders' => $this->stakeholderInterface->getByAllWith(decryptParams($site_id), [
                'stakeholder_types',
            ]),
            'stakeholderTypes' => StakeholderTypeEnum::values(),
            'leadSources' => $this->leadSourceInterface->getByAll(decryptParams($site_id)),
            'user' => auth()->user(),
            'country' => Country::all(),
            'customFields' => $customFields,
            'crm_lead' => $crm_lead,

        ];
        return view('app.sites.floors.units.sales-plan.create', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id, $floor_id = null, $unit_id = null)
    {
        if (!request()->ajax()) {

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->salesPlanInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site' => (new Site())->find(decryptParams($site_id)),
                // 'floor' => (new Floor())->find(decryptParams($floor_id)),
                'unit' => (new Unit())->with('status', 'type')->where('has_sub_units', false)->where('status_id', 1)->orWhere('status_id', 6)->get(),
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'stakeholders' => $this->stakeholderInterface->getByAllWith(decryptParams($site_id), [
                    'stakeholder_types',
                ]),
                'stakeholderTypes' => StakeholderTypeEnum::values(),
                'leadSources' => $this->leadSourceInterface->getByAll(decryptParams($site_id)),
                'user' => auth()->user(),
                'country' => Country::all(),
                'customFields' => $customFields

            ];

            return view('app.sites.floors.units.sales-plan.create', $data);
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
    public function store(Request $request, $site_id, $floor_id = null, $unit_id = null)
    {

        try {
            $validator = Validator::make($request->all(), [
                'stackholder.cnic' => 'unique:backlisted_stakeholders,cnic'
            ], [
                'stackholder.cnic' => 'This CNIC is BlackListed.'
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator);
            }
            $inputs = $request->input();
            $floor_id = encryptParams($inputs['floor_id']);
            $unit_id = encryptParams($inputs['unit_id']);

            $record = $this->salesPlanInterface->store(decryptParams($site_id), $inputs);

            return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(0), 'unit_id' => encryptParams(0)])->withSuccess('Sales Plan Saved!');
        } catch (GeneralException $ex) {
            Log::error($ex->getLine() . " Message => " . $ex->getMessage());
            return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(decryptParams($floor_id)), 'unit_id' => encryptParams(decryptParams($unit_id))])->withDanger($ex->getMessage());
        } catch (Exception $ex) {
            Log::error($ex->getLine() . " Message => " . $ex->getMessage());
            return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(decryptParams($floor_id)), 'unit_id' => encryptParams(decryptParams($unit_id))])->withDanger($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($site_id, $floor_id = null, $unit_id = null, $id)
    {
        //
        $salePlan = SalesPlan::find(decryptParams($id));
        $installments = $salePlan->installments;
        $qrCodeimg =  asset('app-assets') . '/pdf/sales-plans/qrcodes/' . $salePlan->unit->id . '-' . $salePlan->id . '-' .  $salePlan->stakeholder->id . '.png';

        $data = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'salePlan' => $salePlan,
            'additional_costs' => $salePlan->additionalCosts,
            'installments' => $installments,
            'qrCodeimg' => $qrCodeimg,
        ];
        return view('app.sites.floors.units.sales-plan.payment-plan-preview', $data);
    }

    public function initialPreview($site_id, $floor_id = null, $unit_id = null, $id)
    {
        //
        $salePlan = SalesPlan::find(decryptParams($id));
        $installments = $salePlan->installments;
        $qrCodeName = 'Investment-Plan-' . $salePlan->unit->id . '-' . $salePlan->id . '-' .  $salePlan->stakeholder->id . '.png';

        $qrCodeimg =  asset('app-assets') . '/pdf/sales-plans/qrcodes/' . $qrCodeName;

        $data = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'salePlan' => $salePlan,
            'additional_costs' => $salePlan->additionalCosts,
            'installments' => $installments,
            'qrCodeimg' => $qrCodeimg,
            'preview' => 'initial',
        ];
        return view('app.sites.floors.units.sales-plan.investment-plan-preview', $data);
    }

    public function updatedPreview($site_id, $floor_id = null, $unit_id = null, $id)
    {
        //
        $salePlan = SalesPlan::find(decryptParams($id));
        $installments = $salePlan->installments;
        $qrCodeName = 'Payment-Plan-' .  $salePlan->unit->id . '-' . $salePlan->id . '-' .  $salePlan->stakeholder->id . '.png';

        $qrCodeimg =  asset('app-assets') . '/pdf/sales-plans/qrcodes/' .  $qrCodeName;

        $data = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'salePlan' => $salePlan,
            'additional_costs' => $salePlan->additionalCosts,
            'installments' => $installments,
            'qrCodeimg' => $qrCodeimg,
            'preview' => 'updated',
            'salesPlanTemplates' => (new SalesPlanTemplate())->all(),
        ];
        return view('app.sites.floors.units.sales-plan.payment-plan-preview', $data);
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

    public function getUnitDetails(Request $request)
    {
        $unit = Unit::with('type')->find($request->unit_id);
        return apiSuccessResponse([$unit, $unit->floor]);
    }

    public function printPage($site_id, $floor_id, $unit_id, $sales_plan_id, $tempalte_id)
    {
        //
        $salesPlan = SalesPlan::find(decryptParams($sales_plan_id));

        $template = SalesPlanTemplate::find(decryptParams($tempalte_id));

        $role = $salesPlan->user->roles->pluck('name');
        if ($template->id == 1) {
            $qrCodeName = 'Investment-Plan-' . $salesPlan->unit->id . '-' . $salesPlan->id . '-' .  $salesPlan->stakeholder->id . '.png';
        } else {
            $qrCodeName = 'Payment-Plan-' .  $salesPlan->unit->id . '-' . $salesPlan->id . '-' .  $salesPlan->stakeholder->id . '.png';
        }
        $qrCodeimg =  asset('app-assets') . '/pdf/sales-plans/qrcodes/' . $qrCodeName;
        $data = [
            'unit_no' => $salesPlan->unit->floor_unit_number,
            'floor_short_label' => $salesPlan->unit->floor->short_label,
            'category' => $salesPlan->unit->type->name,
            'size' => $salesPlan->unit->gross_area,
            'client_name' => $salesPlan->stakeholder->full_name,
            'rate' => $salesPlan->unit_price,
            'down_payment_percentage' => $salesPlan->down_payment_percentage,
            'down_payment_total' =>  $salesPlan->down_payment_total,
            'discount_percentage' => $salesPlan->discount_percentage,
            'discount_total' =>  $salesPlan->discount_total,
            'total' => $salesPlan->total_price,
            'sales_person_name' => $salesPlan->user->name,
            'sales_person_contact' => $salesPlan->stakeholder->contact,
            'sales_person_status' => $role[0],
            'sales_person_phone_no' => $salesPlan->user->phone_no,
            'sales_person_sales_type' => $salesPlan->sales_type,
            'indirect_source' => $salesPlan->indirect_source,
            'instalments' => collect($salesPlan->installments)->sortBy('installment_order'),
            'additional_costs' => $salesPlan->additionalCosts,
            'validity' =>  $salesPlan->validity,
            'contact' => $salesPlan->stakeholder->contact,
            'amount' => $salesPlan->total_price,
            'remaining_installments' => $salesPlan->installments->where('remaining_amount', '>', 0)->count(),
            'remaing_amount' => $salesPlan->installments->sum('remaining_amount'),
            'paid_amount' => $salesPlan->installments->sum('paid_amount'),
            'qrCodeimg' => $qrCodeimg,
        ];

        actionLog(get_class($salesPlan), auth()->user(), $template, 'print', [
            'attributes' => $salesPlan->toArray()
        ]);

        return view('app.sites.floors.units.sales-plan.sales-plan-templates.' . $template->slug, compact('data'));
    }

    public function ajaxGenerateInstallments(Request $request, $site_id, $floor_id = null, $unit_id = null)
    {
        $inputs = $request->input();

        $installments = $this->salesPlanInterface->generateInstallments($site_id, $floor_id, $unit_id, $inputs);

        // dd($installments);
        if (is_a($installments, 'Exception')) {
            return apiErrorResponse('invalid_amout');
        }

        return apiSuccessResponse($installments);
    }

    public function checkStakeholder(Request $request, $site_id, $floor_id, $unit_id)
    {
        $salesPlan = SalesPlan::find($request->salesPlanID);
        $unit_id =  $salesPlan->unit->id;
        $stakeholder = Stakeholder::find($salesPlan->stakeholder_id);

        $residential_address_type = $stakeholder->residential_address_type;
        $residential_country_id = $stakeholder->residential_country_id;
        $residential_state_id = $stakeholder->residential_state_id;
        $residential_city_id = $stakeholder->residential_city_id;
        $residential_postal_code = $stakeholder->residential_postal_code;
        $residential_address = $stakeholder->residential_address;

        $mailing_address_type = $stakeholder->mailing_address_type;
        $mailing_country_id = $stakeholder->mailing_country_id;
        $mailing_state_id = $stakeholder->mailing_state_id;
        $mailing_city_id = $stakeholder->mailing_city_id;
        $mailing_postal_code = $stakeholder->mailing_postal_code;
        $mailing_address = $stakeholder->mailing_address;


        if ($stakeholder->stakeholder_as == 'i') {

            $full_name = $stakeholder->full_name;
            $father_name = $stakeholder->father_name;
            $occupation = $stakeholder->occupation;
            $cnic = $stakeholder->cnic;
            $mobile_contact = $stakeholder->mobile_contact;
            $date_of_birth = $stakeholder->date_of_birth;
            $nationality = $stakeholder->nationality;

            if (
                isset($full_name) &&
                isset($father_name) &&
                isset($occupation) &&
                isset($cnic) &&
                isset($mobile_contact) &&
                isset($date_of_birth) &&
                isset($nationality) &&

                isset($residential_address_type) &&
                isset($residential_country_id) &&
                isset($residential_state_id) &&
                isset($residential_city_id) &&
                isset($residential_postal_code) &&
                isset($residential_address)

            ) {
                return response()->json([
                    'success' => true,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Please Fill Stakeholder All Required Fields First!!!",
                    'type' => "Individual",
                    'url' =>  route('sites.stakeholders.edit', ['site_id' => encryptParams(1), 'id' => encryptParams($stakeholder->id)]),
                ], 200);
            }
        } elseif ($stakeholder->stakeholder_as == 'c') {

            $company_name = $stakeholder->full_name;
            $ntn = $stakeholder->ntn;
            $reg_no = $stakeholder->cnic;
            $strn = $stakeholder->strn;
            $mobile_contact = $stakeholder->office_contact;
            $industry = $stakeholder->industry;

            if (
                isset($company_name) &&
                isset($ntn) &&
                isset($reg_no) &&
                isset($strn) &&
                isset($mobile_contact) &&
                isset($industry) &&

                isset($residential_address_type) &&
                isset($residential_country_id) &&
                isset($residential_state_id) &&
                isset($residential_city_id) &&
                isset($residential_postal_code) &&
                isset($residential_address) &&

                isset($mailing_address_type) &&
                isset($mailing_country_id) &&
                isset($mailing_state_id) &&
                isset($mailing_city_id) &&
                isset($mailing_postal_code) &&
                isset($mailing_address)

            ) {
                return response()->json([
                    'success' => true,
                ], 200);
            } else {

                return response()->json([
                    'success' => false,
                    'message' => "Please Fill Stakeholder All Required Fields First!!!",
                    'type' => "Company",
                    'url' =>  route('sites.stakeholders.edit', ['site_id' => encryptParams(1), 'id' => encryptParams($stakeholder->id)]),
                ], 200);
            }
        }
    }

    public function approveSalesPlan(Request $request, $site_id, $floor_id, $unit_id)
    {
        $salesPlan = SalesPlan::find($request->salesPlanID);
        $unit_id =  $salesPlan->unit->id;

        $payment_plan = SalesPlan::where('payment_plan_serial_id', '!=', null)->get();
        $payment_plan = collect($payment_plan)->last();

        if (isset($payment_plan)) {
            if ($payment_plan->payment_plan_serial_id == null) {
                $payment_serial_number = 001;
                $payment_serial_number =  sprintf('%03d', $payment_serial_number);
            } else {
                $payment_serial_number = substr($payment_plan->payment_plan_serial_id, 3);
                $payment_serial_number = (int)$payment_serial_number + 1;
                $payment_serial_number =  sprintf('%03d', $payment_serial_number);
            }
        } else {
            $payment_serial_number = 001;
            $payment_serial_number =  sprintf('%03d', $payment_serial_number);
        }



        $salesPlan = (new SalesPlan())->where('status', '!=', 3)->where('unit_id', $unit_id)->get();
        foreach ($salesPlan as $salesPlan) {
            $salePlan = SalesPlan::find($salesPlan->id);
            if ($salePlan->status == 1) {
                $transaction = $this->financialTransactionInterface->makeDisapproveSalesPlanTransaction($salesPlan->id);
            }
            $salePlan->status = 2;
            // $salePlan->approved_date = $request->approve_date . date(' H:i:s');
            $salePlan->update();
        }
        $salesPlan = (new SalesPlan())->where('id', $request->salesPlanID)->update([
            'status' => 1,
            'approved_date' => $request->approve_date . date(' H:i:s'),
            'payment_plan_serial_id' => 'PP-' . $payment_serial_number,
        ]);

        $this->salesPlanInterface->generatePDF((new SalesPlan())->find($request->salesPlanID), 'payment_plan');

        $salesPlan = SalesPlan::with('stakeholder', 'stakeholder.stakeholderAsCustomer')->find($request->salesPlanID);

        $user = User::find($salesPlan->user_id);

        $transaction = $this->financialTransactionInterface->makeSalesPlanTransaction($salesPlan->id);

        if (is_a($transaction, 'Exception') && is_a($transaction, 'GeneralException')) {
            return apiErrorResponse('invalid_amout');
        }

        $currentURL = URL::current();
        $notificaionData = [
            'title' => 'Sales Plan Approved Notification',
            'description' => Auth::User()->name . ' approved generated sales plan.',
            'message' => 'xyz message',
            'url' => str_replace('/approve-sales-plan', '', $currentURL),
        ];

        Notification::send($user, new ApprovedSalesPlanNotification($notificaionData));

        return response()->json([
            'success' => true,
            'message' => "Sales Plan Approved Sucessfully",
        ], 200);
    }

    public function disApproveSalesPlan(Request $request)
    {
        $salesPlan = SalesPlan::find($request->salesPlanID);
        $user = User::find($salesPlan->user_id);

        if ($salesPlan->status == 1) {

            $transaction = $this->financialTransactionInterface->makeDisapproveSalesPlanTransaction($request->salesPlanID);
            $salesPlan->unit->status_id = 1;
            $salesPlan->unit->save();

            $salesPlan->status = 2;
            $salesPlan->save();
        } else {
            $salesPlan->status = 2;
            $salesPlan->save();
        }

        $currentURL = URL::current();
        $notificaionData = [
            'title' => 'Sales Plan Disapproved Notification',
            'description' => Auth::User()->name . ' disapproved generated sales plan.',
            'message' => 'xyz message',
            'url' => str_replace('/disapprove-sales-plan', '', $currentURL),
        ];

        Notification::send($user, new ApprovedSalesPlanNotification($notificaionData));

        return response()->json([
            'success' => true,
            'message' => "Sales Plan disapproved Sucessfully",
        ], 200);
    }

    public function getInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempData = (new TempSalePlan())->find((int)$request->get('id'));

            switch ($field) {
                case 'unit_short_label':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:App\Models\Unit,floor_unit_number',
                        ], [
                            'value' => 'Unit Does not Exists.'
                        ]);
                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempData->unit_short_label = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'stakeholder_cnic':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:App\Models\Stakeholder,cnic',
                        ], [
                            'value' => 'Stakeholder Does not Exists.'
                        ]);
                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $tempData->stakeholder_cnic = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'unit_price':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|gt:0',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempData->unit_price = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'total_price':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|gt:0',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempData->total_price = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'discount_percentage':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->discount_percentage = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;


                case 'discount_total':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->discount_total = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'down_payment_percentage':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->down_payment_percentage =  $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }
                    break;

                case 'down_payment_total':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->down_payment_total =  $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }
                    break;

                case 'lead_source':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->lead_source = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'validity':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->validity = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'status':
                    $tempData->status = $request->get('value');
                    $tempData->save();

                    $values = ['pending' => 'Pending', 'approved' => 'Approved', 'disapproved' => 'Disapproved', 'cancelled' => 'Cancelled'];
                    $response =  view(
                        'app.components.input-select-fields',
                        [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'values' => $values,
                            'selectedValue' => $tempData->status
                        ]
                    )->render();

                    break;
                case 'comment':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->comment = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'approved_date':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->approved_date = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                default:
                    $response = view('app.components.text-number-field', [
                        'field' => $field,
                        'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                        'value' => $request->get('value')
                    ])->render();
                    break;
            }
            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }

    public function ImportPreview(Request $request, $site_id)
    {

        try {
            $model = new TempSalePlan();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);

                // $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error

                TempSalePlan::query()->truncate();
                $import = new SalesPlanImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.floors.SalesPlanImport.storePreview', ['site_id' => $site_id]);
            } else {
                return Redirect::back()->withDanger('Select File to Import');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            if (count($e->failures()) > 0) {
                $data = [
                    'site_id' => decryptParams($site_id),
                    'errorData' => $e->failures()
                ];
                return Redirect::back()->with(['data' => $e->failures()]);
            }
        }
    }

    public function storePreview(Request $request, $site_id)
    {
        $model = new TempSalePlan();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.No Record Found'));
        } else {
            $required = [
                'unit_short_label',
                'stakeholder_cnic',
                'unit_price',
                'total_price',
            ];
            $dataTable = new ImportSalesPlanDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
                'required_fields' => $required,

            ];
            return $dataTable->with($data)->render('app.sites.floors.units.sales-plan.import.importSalesPlanPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {

        $validator = \Validator::make($request->all(), [
            'fields.*' => 'required',
        ], [
            'fields.*.required' => 'Must Select all Fields',
            'fields.*.distinct' => 'Field can not be duplicated',

        ]);

        $status  = [
            0 => 'pending',
            1 => 'approved',
            2 => 'disapproved',
            3 => 'cancelled',
        ];

        $validator->validate();

        $model = new TempSalePlan();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }

            // $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['user_id'] = Auth::user()->id;
            $data[$key]['comments'] = $data[$key]['comment'];

            $data[$key]['status'] = array_search($data[$key]['status'], $status);

            $unit = Unit::where('floor_unit_number', $data[$key]['unit_short_label'])->first();
            $data[$key]['unit_id'] = $unit->id;

            $stakeholder = Stakeholder::where('cnic', $data[$key]['stakeholder_cnic'])->first();
            $data[$key]['stakeholder_id'] = $stakeholder->id;
            $data[$key]['stakeholder_data'] = json_encode($stakeholder);

            $leadSource = LeadSource::where('name', Str::title($data[$key]['lead_source']))->first();
            if ($leadSource) {
                $data[$key]['lead_source_id'] = $leadSource->id;
            } else {
                $leadSource = LeadSource::create([
                    'site_id' => decryptParams($site_id),
                    'name' => $data[$key]['lead_source']
                ]);
                $data[$key]['lead_source_id'] = $leadSource->id;
            }
            $data[$key]['is_imported'] = true;

            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();

            unset($data[$key]['stakeholder_cnic']);
            unset($data[$key]['unit_short_label']);
            unset($data[$key]['lead_source']);
            unset($data[$key]['comment']);

            // dd($data);

            $salePlan = SalesPlan::create($data[$key]);

            if ($data[$key]['status'] == '1') {
                $transaction = $this->financialTransactionInterface->makeSalesPlanTransaction($salePlan->id);
                if (is_a($transaction, 'Exception') && is_a($transaction, 'GeneralException')) {
                    return apiErrorResponse('invalid_amout');
                }
            }
        }

        TempSalePlan::query()->truncate();

        return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }

    public function downloadInvestmentPlan($file_name)
    {
        $path = public_path('app-assets/pdf/sales-plans/investment-plan');
        $file_path = $path . '/' . $file_name;
        return response()->download($file_path);
    }

    public function downloadPaymentPlan($file_name)
    {
        $path = public_path('app-assets/pdf/sales-plans/payment-plan');
        $file_path = $path . '/' . decryptParams($file_name);
        return response()->download($file_path);
    }
}
