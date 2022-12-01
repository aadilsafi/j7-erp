<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentVoucherDatatable;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use Illuminate\Http\Request;

class PaymentVocuherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentVoucherDatatable $dataTable, $site_id)
    {
        //
        $data = [
            'site_id' => $site_id,
        ];
        return $dataTable->with($data)->render('app.sites.payment-voucher.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($site_id)
    {
        //
        if (!request()->ajax()) {
            $data = [
                'site_id' => $site_id,
                'stakholders' => Stakeholder::all(),
            ];
            return view('app.sites.payment-voucher.create', $data);
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


    public function stakeholder_types($id){
        $types = StakeholderType::where('stakeholder_id', $id)->where('status',true)->get();
        return response()->json([
            'success' => true,
            'types' => $types->toArray()
        ], 200);

    }
}
