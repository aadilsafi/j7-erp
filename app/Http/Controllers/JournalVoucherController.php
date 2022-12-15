<?php

namespace App\Http\Controllers;

use App\DataTables\JournalVouchersDatatable;
use App\Models\AccountHead;
use App\Models\JournalVoucher;
use App\Models\Stakeholder;
use App\Services\JournalVouchers\JournalVouchersInterface;
use Illuminate\Http\Request;

class JournalVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $journalVoucherInterface;

    public function __construct(JournalVouchersInterface $journalVoucherInterface)
    {
        $this->journalVoucherInterface = $journalVoucherInterface;
    }

    public function index(JournalVouchersDatatable $dataTable, $site_id)
    {

        $data = [
            'site_id' => decryptParams($site_id),
        ];

        return $dataTable->with($data)->render('app.sites.journal-vouchers.index', $data);
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
                'fifthLevelAccount' => AccountHead::where('level', 5)->get(),
                'stakeholders' => Stakeholder::all(),
                'journal_serial_number' => '001',
            ];

            return view('app.sites.journal-vouchers.create', $data);
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
     * @param  \App\Models\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function show(JournalVoucher $journalVoucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function edit(JournalVoucher $journalVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JournalVoucher $journalVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(JournalVoucher $journalVoucher)
    {
        //
    }
}
