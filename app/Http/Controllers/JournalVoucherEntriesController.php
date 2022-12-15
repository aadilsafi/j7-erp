<?php

namespace App\Http\Controllers;

use App\DataTables\JournalVouchersEntriesDatatable;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherEntry;
use Illuminate\Http\Request;

class JournalVoucherEntriesController extends Controller
{
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
    public function show(JournalVouchersEntriesDatatable $dataTable,$site_id,$id)
    {
        //
        $journalVoucher = JournalVoucher::find(decryptParams($id));

        $data = [
            'site_id' => decryptParams($site_id),
            'id' => $journalVoucher->id,
            'name'=>  $journalVoucher->name,
        ];

        return $dataTable->with($data)->render('app.sites.journal-vouchers-entries.index', $data);
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
