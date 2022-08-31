<?php

namespace App\Http\Controllers;

use App\Services\Stakeholder\Interface\StakeholderInterface;
use Illuminate\Http\Request;

class StakeholderController extends Controller
{
    private $stakeholderInterface;

    public function __construct(
        StakeholderInterface $stakeholderInterface
    ) {
        $this->stakeholderInterface = $stakeholderInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function ajaxGetById(Request $request, $site_id, $stakeholder_id)
    {
        if ($request->ajax()) {
            $stakeholder = $this->stakeholderInterface->getById($site_id, $stakeholder_id);
            return apiSuccessResponse($stakeholder);
        } else {
            abort(403);
        }
    }
}
