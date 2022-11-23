<?php

namespace App\Http\Controllers;

use App\DataTables\BlacklistedStakeholderDataTable;
use App\Models\BacklistedStakeholder;
use Illuminate\Http\Request;

class BacklistedStakeholderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BlacklistedStakeholderDataTable $dataTable, $site_id)
    {
        //
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.stakeholders.blacklisted-stakeholders.index', $data);
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
     * @param  \App\Models\BacklistedStakeholder  $backlistedStakeholder
     * @return \Illuminate\Http\Response
     */
    public function show(BacklistedStakeholder $backlistedStakeholder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BacklistedStakeholder  $backlistedStakeholder
     * @return \Illuminate\Http\Response
     */
    public function edit(BacklistedStakeholder $backlistedStakeholder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BacklistedStakeholder  $backlistedStakeholder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BacklistedStakeholder $backlistedStakeholder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BacklistedStakeholder  $backlistedStakeholder
     * @return \Illuminate\Http\Response
     */
    public function destroy(BacklistedStakeholder $backlistedStakeholder)
    {
        //
    }
}
