<?php

namespace App\Http\Controllers;

use App\Models\SalesPlan;
use Illuminate\Http\Request;
use App\Models\SalesPlanTemplate;
use Illuminate\Support\Facades\Auth;

class PrintSalesPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('app.sites.floors.units.sales-plan.sales-plan-templates.signature-sales-plan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $salesPlan = SalesPlan::find($id);

        $template = SalesPlanTemplate::find(1);

        $data['unit_no'] = $salesPlan->unit->floor_unit_number;

        $data['floor_short_label'] = $salesPlan->unit->floor->short_label;

        $data['category'] = $salesPlan->unit->type->name;

        $data['size'] = $salesPlan->unit->gross_area;

        $data['client_name'] = 'Ali Raza';

        $data['rate'] = $salesPlan->unit->price_sqft;

        $data['down_payment'] = '25';

        $data['discount'] = '5';

        $data['sales_person_name'] = Auth::user()->name;

        $role = Auth::user()->roles->pluck('name');

        $data['sales_person_contact'] = $salesPlan->stakeholder->contact;

        $data['sales_person_status'] = $role[0];

        $data['sales_person_phone_no'] = Auth::user()->phone_no;

        $data['sales_person_sales_type'] = 'Direct';

        $data['indirect_source'] = '';

        $data['instalments'] = $salesPlan->installments;

        $data['additional_costs'] = $salesPlan->additionalCosts;
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadView('app.sites.floors.units.sales-plan.sales-plan-templates.signature-sales-plan',compact('data'));
        // return $pdf->download('sales-plan.pdf');
        return view('app.sites.floors.units.sales-plan.sales-plan-templates.'.$template->slug,compact('data'));
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
}
