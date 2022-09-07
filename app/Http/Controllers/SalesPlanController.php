<?php

namespace App\Http\Controllers;

use App\DataTables\SalesPlanDataTable;
use App\Models\{SalesPlan, Floor, Site, Unit};
use Illuminate\Http\Request;
use App\Models\SalesPlanTemplate;
use App\Services\Interfaces\AdditionalCostInterface;
use App\Services\{
    SalesPlan\Interface\SalesPlanInterface,
    Stakeholder\Interface\StakeholderInterface,
};
use App\Services\LeadSource\LeadSourceInterface;
use Illuminate\Support\Facades\Auth;

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
        LeadSourceInterface $leadSourceInterface
    ) {
        $this->salesPlanInterface = $salesPlanInterface;
        $this->additionalCostInterface = $additionalCostInterface;
        $this->stakeholderInterface = $stakeholderInterface;
        $this->leadSourceInterface = $leadSourceInterface;
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
            $data = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => (new Floor())->find(decryptParams($floor_id)),
                'unit' => (new Unit())->with('status', 'type')->find(decryptParams($unit_id)),
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'stakeholders' => $this->stakeholderInterface->getByAll(decryptParams($site_id)),
                'leadSources' => $this->leadSourceInterface->getByAll(decryptParams($site_id)),
                'user' => auth()->user(),
            ];
            // dd($data);

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
    public function store(Request $request,$site_id,$floor_id,$unit_id)
    {
        $data = $request->input();
        $record = $this->salesPlanInterface->store($site_id, $data);
        return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' =>$site_id, 'floor_id' => $floor_id, 'unit_id' => $unit_id])->withSuccess('Sales Plan Saved!');
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

    public function printPage($site_id,$floor_id,$unit_id,$sales_plan_id,$tempalte_id)
    {
        //
        $salesPlan = SalesPlan::find(decryptParams($sales_plan_id));

        $template = SalesPlanTemplate::find(decryptParams($tempalte_id));

        $data['unit_no'] = $salesPlan->unit->floor_unit_number;

        $data['floor_short_label'] = $salesPlan->unit->floor->short_label;

        $data['category'] = $salesPlan->unit->type->name;

        $data['size'] = $salesPlan->unit->gross_area;

        $data['client_name'] = $salesPlan->stakeholder->full_name;

        $data['rate'] = $salesPlan->unit->price_sqft;

        $data['down_payment_percentage'] = $salesPlan->down_payment_percentage;

        $data['down_payment_total'] = $salesPlan->down_payment_total;

        $data['discount_percentage'] = $salesPlan->discount_percentage;

        $data['discount_total'] = $salesPlan->discount_total;

        $data['sales_person_name'] = Auth::user()->name;

        $role = Auth::user()->roles->pluck('name');

        $data['sales_person_contact'] = $salesPlan->stakeholder->contact;

        $data['sales_person_status'] = $role[0];

        $data['sales_person_phone_no'] = Auth::user()->phone_no;

        $data['sales_person_sales_type'] = $salesPlan->sales_type;

        $data['indirect_source'] = $salesPlan->indirect_source;

        $data['instalments'] = $salesPlan->installments;

        $data['additional_costs'] = $salesPlan->additionalCosts;

        return view('app.sites.floors.units.sales-plan.sales-plan-templates.'.$template->slug,compact('data'));
    }

    public function ajaxGenerateInstallments(Request $request, $site_id, $floor_id, $unit_id)
    {
        $inputs = $request->input();

        $installments = $this->salesPlanInterface->generateInstallments($site_id, $floor_id, $unit_id, $inputs);

        if(is_a($installments, 'Exception')){
            return apiErrorResponse('invalid_amout');
        }

        return apiSuccessResponse($installments);
    }
}
