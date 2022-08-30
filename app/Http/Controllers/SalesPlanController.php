<?php

namespace App\Http\Controllers;

use App\DataTables\SalesPlanDataTable;
use App\Models\{SalesPlan, Floor, Site, Unit};
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\SalesPlanTemplate;
use App\Services\SalesPlan\Interface\SalesPlanInterface;
use Illuminate\Support\Facades\Auth;

class SalesPlanController extends Controller
{
    private $salesPlanInterface;

    public function __construct(SalesPlanInterface $salesPlanInterface)
    {
        $this->salesPlanInterface = $salesPlanInterface;
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
            'unit' => (new Unit())->find(decryptParams($unit_id))
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
    public function store(Request $request)
    {
        return $this->printPage(1,1);
        // return $request->all();
    }

    public function printPage($sales_plan_id,$tempalte_id)
    {
        //
        $salesPlan = SalesPlan::find($sales_plan_id);
        $template = SalesPlanTemplate::find($tempalte_id);
        $data['unit_no'] = $salesPlan->unit->floor_unit_number;
        $data['floor_short_label'] = $salesPlan->unit->floor->short_label;
        $data['category'] = $salesPlan->unit->type->name;
        $data['size'] = $salesPlan->unit->gross_area;
        $data['client_name'] = 'Ali Raza';
        $data['rate'] = $salesPlan->unit->price_sqft;
        $data['sales_person_name'] = Auth::user()->name;
        $role = Auth::user()->roles->pluck('name');
        $data['sales_person_contact'] = $salesPlan->stakeholder->contact;
        $data['sales_person_status'] = $role[0];
        $data['sales_person_phone_no'] = Auth::user()->phone_no;
        $data['sales_person_sales_type'] = 'Direct';
        $data['indirect_source'] = '';
        $data['instalments'] = $salesPlan->installments;
        $data['additional_costs'] = $salesPlan->additionalCosts;
        return view('app.sites.floors.units.sales-plan.print',compact('data'));
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

    public function ajaxGenerateInstallments(Request $request, $site_id, $floor_id, $unit_id)
    {
        $inputs = $request->input();

        $installmentDates = $this->dateRanges($inputs['startDate'], $inputs['length'], $inputs['rangeCount'], $inputs['rangeBy']);

        $data['amounts'] = $this->baseInstallment($inputs['installment_amount'], $inputs['length']);

        dd($inputs, $installmentDates, $data);

        return apiSuccessResponse($data);
    }

    private function baseInstallment($total, $divide)
    {
        return round($total / $divide);
    }

    private function dateRanges($requrestDate, $length = 1, $daysCount = 1, $rangeBy = 'days')
    {
        $startDate = Carbon::parse($requrestDate);

        $endDate =  (new Carbon($requrestDate))->add((($length - 1) * $daysCount), $rangeBy);

        $period = CarbonPeriod::create($startDate, ($daysCount . ' ' . $rangeBy), $endDate);

        $dates = $period->toArray();

        return $dates;
    }
}
