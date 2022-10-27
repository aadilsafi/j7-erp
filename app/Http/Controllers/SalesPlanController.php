<?php

namespace App\Http\Controllers;

use App\DataTables\SalesPlanDataTable;
use App\Exceptions\GeneralException;
use App\Models\{SalesPlan, Floor, Site, Unit, User};
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
use App\Utils\Enums\NatureOfAccountsEnum;
use App\Utils\Enums\StakeholderTypeEnum;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SalesPlanController extends Controller
{
    private $salesPlanInterface;
    private $additionalCostInterface;
    private $stakeholderInterface;
    private $leadSourceInterface;

    public function __construct(
        SalesPlanInterface $salesPlanInterface,
        AdditionalCostInterface $additionalCostInterface,
        StakeholderInterface $stakeholderInterface,
        LeadSourceInterface $leadSourceInterface,
        CustomFieldInterface $customFieldInterface
    ) {
        $this->salesPlanInterface = $salesPlanInterface;
        $this->additionalCostInterface = $additionalCostInterface;
        $this->stakeholderInterface = $stakeholderInterface;
        $this->leadSourceInterface = $leadSourceInterface;
        $this->customFieldInterface = $customFieldInterface;
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
            'unit' => (new Unit())->find(decryptParams($unit_id)),
            'salesPlanTemplates' => (new SalesPlanTemplate())->all(),
        ];
        return $dataTable->with($data)->render('app.sites.floors.units.sales-plan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id, $floor_id, $unit_id)
    {
        if (!request()->ajax()) {

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->salesPlanInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => (new Floor())->find(decryptParams($floor_id)),
                'unit' => (new Unit())->with('status', 'type')->find(decryptParams($unit_id)),
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'stakeholders' => $this->stakeholderInterface->getByAllWith(decryptParams($site_id), [
                    'stakeholder_types',
                ]),
                'stakeholderTypes' => StakeholderTypeEnum::values(),
                'leadSources' => $this->leadSourceInterface->getByAll(decryptParams($site_id)),
                'user' => auth()->user(),
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
    public function store(Request $request, $site_id, $floor_id, $unit_id)
    {
        // dd($request->all());
        try {
            $inputs = $request->input();

            $record = $this->salesPlanInterface->store(decryptParams($site_id), decryptParams($floor_id), decryptParams($unit_id), $inputs);
            return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(decryptParams($floor_id)), 'unit_id' => encryptParams(decryptParams($unit_id))])->withSuccess('Sales Plan Saved!');
        } catch (GeneralException $ex) {
            Log::error($ex->getLine() . " Message => " . $ex->getMessage());
            return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(decryptParams($floor_id)), 'unit_id' => encryptParams(decryptParams($unit_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        } catch (Exception $ex) {
            Log::error($ex->getLine() . " Message => " . $ex->getMessage());
            return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(decryptParams($floor_id)), 'unit_id' => encryptParams(decryptParams($unit_id))])->withDanger(__('lang.commons.something_went_wrong'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function printPage($site_id, $floor_id, $unit_id, $sales_plan_id, $tempalte_id)
    {
        //
        $salesPlan = SalesPlan::find(decryptParams($sales_plan_id));

        $template = SalesPlanTemplate::find(decryptParams($tempalte_id));

        $role = Auth::user()->roles->pluck('name');

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
            'sales_person_name' => Auth::user()->name,
            'sales_person_contact' => $salesPlan->stakeholder->contact,
            'sales_person_status' => $role[0],
            'sales_person_phone_no' => Auth::user()->phone_no,
            'sales_person_sales_type' => $salesPlan->sales_type,
            'indirect_source' => $salesPlan->indirect_source,
            'instalments' => collect($salesPlan->installments)->sortBy('installment_order'),
            'additional_costs' => $salesPlan->additionalCosts,
            'validity' =>  $salesPlan->validity,
            'contact' => $salesPlan->stakeholder->contact,
            'amount' => $salesPlan->total_price,
        ];

        actionLog(get_class($salesPlan), auth()->user(), $template, 'print', [
            'attributes' => $salesPlan->toArray()
        ]);

        return view('app.sites.floors.units.sales-plan.sales-plan-templates.' . $template->slug, compact('data'));
    }

    public function ajaxGenerateInstallments(Request $request, $site_id, $floor_id, $unit_id)
    {
        $inputs = $request->input();

        $installments = $this->salesPlanInterface->generateInstallments($site_id, $floor_id, $unit_id, $inputs);

        // dd($installments);
        if (is_a($installments, 'Exception')) {
            return apiErrorResponse('invalid_amout');
        }

        return apiSuccessResponse($installments);
    }

    public function approveSalesPlan(Request $request, $site_id, $floor_id, $unit_id)
    {

        $salesPlan = (new SalesPlan())->where('status', '!=', 3)->where('unit_id', decryptParams($unit_id))->update([
            'status' => 2,
            'approved_date' => now(),
        ]);

        $salesPlan = (new SalesPlan())->where('id', $request->salesPlanID)->update([
            'status' => 1,
            'approved_date' => now(),
        ]);

        $salesPlan = SalesPlan::with('stakeholder', 'stakeholder.stakeholderAsCustomer')->find($request->salesPlanID);

        $user = User::find($salesPlan->user_id);

        $accountCode = makeSalesPlanTransaction($salesPlan->id);

        $unit = Unit::find($salesPlan->unit_id);

        $unit->modelable()->create([
            'site_id' => decryptParams($site_id),
            'code' => $accountCode,
            'name' =>  $unit->floor_unit_number . ' Receviable',
            'level' => 4,
        ]);

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
            $salesPlan->unit->status_id = 1;
            $salesPlan->unit->save();

            $salesPlan->status = 3;
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
}
