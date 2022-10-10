<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\StakeholderType;
use App\Models\RebateIncentiveModel;
use App\DataTables\DealerIncentiveDataTable;
use App\Models\Stakeholder;

class DealerIncentiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DealerIncentiveDataTable $dataTable, Request $request, $site_id)
    {
        //
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        return $dataTable->with($data)->render('app.sites.file-managements.files.dealer-incentive.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {

        if (!request()->ajax()) {
            $data = [
                'site_id' => decryptParams($site_id),
                'units' => Unit::where('status_id', 5)->with('floor', 'type')->get(),
                'rebate_files' => RebateIncentiveModel::pluck('id')->toArray(),
                'dealer_data' => StakeholderType::where('type','D')->where('status',1)->with('stakeholder')->get(),
                'stakeholders' => Stakeholder::where('site_id',decryptParams($site_id))->with('dealer_stakeholder','stakeholder_types')->get(),
            ];
            return view('app.sites.file-managements.files.dealer-incentive.create', $data);
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
}
