<?php

namespace App\Http\Controllers;
use App\DataTables\RebateIncentiveDataTable;
use App\Services\RebateIncentive\RebateIncentiveInterface;
use Illuminate\Http\Request;

class RebateIncentiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $rebateIncentive;

    public function __construct(
        RebateIncentiveInterface $rebateIncentive
    ) {
        $this->rebateIncentive = $rebateIncentive;
    }

    public function index(RebateIncentiveDataTable $dataTable,Request $request, $site_id)
    {
        //
        $data = [
            'site_id' => decryptParams($site_id),
        ];

        return $dataTable->with($data)->render('app.sites.file-managements.files.rebate-incentive.index', $data);
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
            $data = [
                'site_id' => decryptParams($site_id),
            ];

            return view('app.sites.file-managements.files.rebate-incentive.create', $data);
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
        abort(403);
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
