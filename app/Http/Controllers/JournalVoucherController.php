<?php

namespace App\Http\Controllers;

use App\DataTables\JournalVouchersDatatable;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherEntry;
use App\Models\Stakeholder;
use App\Services\JournalVouchers\JournalVouchersInterface;
use App\Utils\Enums\NatureOfAccountsEnum;
use Auth;
use DB;
use Exception;
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

            $JournalVoucher = JournalVoucher::get();

            if (isset($JournalVoucher) && count($JournalVoucher) > 0) {

                $JournalVoucher = collect($JournalVoucher)->last();
                $JournalVoucher = $JournalVoucher->serial_number + 1;
                $serial_number =  sprintf('%03d', $JournalVoucher);
            } else {

                $serial_number = '001';
            }

            $data = [
                'site_id' => decryptParams($site_id),
                'fifthLevelAccount' => AccountHead::where('level', 5)->get(),
                'stakeholders' => Stakeholder::all(),
                'journal_serial_number' => $serial_number,
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
    public function store(Request $request, $site_id)
    {
        //
        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                $site_id = decryptParams($site_id);
                $record = $this->journalVoucherInterface->store($site_id, $inputs);
                return redirect()->route('sites.settings.journal-vouchers.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.journal-vouchers.create', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
        }
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
    public function edit(JournalVoucher $journalVoucher ,$site_id,$id)
    {
        //
        if (!request()->ajax()) {

            $JournalVoucher = JournalVoucher::find(decryptParams($id));
            $JournalVoucherEntries = JournalVoucherEntry::where('journal_voucher_id',$JournalVoucher->id)->get();

            $data = [
                'site_id' => decryptParams($site_id),
                'fifthLevelAccount' => AccountHead::where('level', 5)->get(),
                'stakeholders' => Stakeholder::all(),
                'journal_serial_number' => $JournalVoucher->serial_number,
                'JournalVoucher' => $JournalVoucher,
                'JournalVoucherEntries'=> $JournalVoucherEntries,
            ];

            return view('app.sites.journal-vouchers.edit', $data);
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JournalVoucher  $journalVoucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JournalVoucher $journalVoucher  ,$site_id,$id)
    {
        //
        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                $site_id = decryptParams($site_id);
                $record = $this->journalVoucherInterface->update(decryptParams($site_id), decryptParams($id),$inputs);
                return redirect()->route('sites.settings.journal-vouchers.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.settings.journal-vouchers.create', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
        }
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

    public function checkVoucher($site_id, $id)
    {
        $journalVoucher = JournalVoucher::find($id);

        $journalVoucher->status = 'checked';
        $journalVoucher->checked_by = Auth::user()->id;
        $journalVoucher->checked_date = now();
        $journalVoucher->update();

        return redirect()->route('sites.settings.journal-vouchers.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }

    public function postVoucher($site_id, $id)
    {
        DB::transaction(function () use ($site_id, $id) {
            $journalVoucher = JournalVoucher::find($id);

            $journalVoucher->status = 'posted';
            $journalVoucher->approved_by = Auth::user()->id;
            $journalVoucher->approved_date = now();
            $journalVoucher->update();

            $JournalVoucherEntries  = JournalVoucherEntry::where('journal_voucher_id', $id)->get();
            $count = count($JournalVoucherEntries);

            $origin_number = AccountLedger::get();

            if (isset($origin_number) && count($origin_number) > 0) {

                $origin_number = collect($origin_number)->last();

                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }

            for ($i = 0; $i < $count; $i++) {

                if ($JournalVoucherEntries[$i]['credit'] == null) {
                    $credit = 0;
                } else {
                    $credit = $JournalVoucherEntries[$i]['credit'];
                }

                if ($JournalVoucherEntries[$i]['debit'] == null) {
                    $debit = 0;
                } else {
                    $debit = $JournalVoucherEntries[$i]['debit'];
                }


                $ledger = new AccountLedger();
                $ledger->site_id =  $JournalVoucherEntries[$i]['site_id'];
                $ledger->account_head_code = $JournalVoucherEntries[$i]['account_number'];
                $ledger->account_action_id = 36;
                $ledger->credit =  $credit;
                $ledger->debit =  $debit;
                $ledger->nature_of_account =  NatureOfAccountsEnum::MANUAL_ENTRY;
                $ledger->origin_name =  NatureOfAccountsEnum::MANUAL_ENTRY->value. '-' . $journalVoucher->serial_number;
                $ledger->origin_number =  $origin_number;
                $ledger->manual_entry =  true;
                $ledger->created_date =  now();
                $ledger->save();
            }
        });

        return redirect()->route('sites.settings.journal-vouchers.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }
}
