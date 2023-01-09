<?php

namespace App\Services\JournalVouchers;

use App\Models\AccountHead;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherEntry;
use App\Services\JournalVouchers\JournalVouchersInterface;
use Auth;
use DB;

class JournalVouchersService implements JournalVouchersInterface
{
    public function model()
    {
        return new JournalVoucher();
    }

    // Store
    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {
            $voucher_amount = str_replace(',', '', $inputs['total_debit']) - str_replace(',', '', $inputs['total_credit']);

            $voucher_data = [
                'site_id' => $site_id,
                'user_id' => Auth::user()->id,
                'serial_number' => $inputs['serial_number'],
                'user_name' => $inputs['user_name'],
                // 'name' =>  $inputs['voucher_name'],
                'voucher_amount' => $voucher_amount,
                'total_debit' => str_replace(',', '', $inputs['total_debit']),
                'total_credit' => str_replace(',', '', $inputs['total_credit']),
                'status' => 'pending',
                'remarks' => $inputs['remarks'],
                'voucher_date' => $inputs['created_date'],
                'created_date' => $inputs['created_date'],
            ];
            $journal_voucher = $this->model()->create($voucher_data);

            if (isset($inputs['attachment'])&& count($inputs['attachment']) > 0) {
                for ($j = 0; $j < count($inputs['attachment']); $j++) {
                    $journal_voucher->addMedia($inputs['attachment'][$j])->toMediaCollection('journal_voucher_attachments');
                    changeImageDirectoryPermission();
                }
            }

            $voucher_entires = $inputs['journal-voucher-entries'];

            for ($i = 0; $i < count($voucher_entires); $i++) {

                $account_head = AccountHead::where('code', $voucher_entires[$i]['account_number'])->first();

                $journal_voucher_entry = new JournalVoucherEntry();

                $journal_voucher_entry->site_id = $site_id;
                $journal_voucher_entry->user_id = Auth::user()->id;
                $journal_voucher_entry->serial_number = $inputs['serial_number'];
                $journal_voucher_entry->journal_voucher_id = $journal_voucher->id;
                $journal_voucher_entry->account_head_code = $account_head->code;
                $journal_voucher_entry->account_number = $voucher_entires[$i]['account_number'];
                $journal_voucher_entry->created_date = $voucher_entires[$i]['voucher_date'];
                $journal_voucher_entry->debit = $voucher_entires[$i]['debit'];
                $journal_voucher_entry->credit = $voucher_entires[$i]['credit'];
                $journal_voucher_entry->remarks = $voucher_entires[$i]['remarks'];
                $journal_voucher_entry->status = 'pending';
                $journal_voucher_entry->total_debit = str_replace(',', '', $inputs['total_debit']);
                $journal_voucher_entry->total_credit = str_replace(',', '', $inputs['total_credit']);
                $journal_voucher_entry->total_amount = $voucher_amount;

                $journal_voucher_entry->save();
            }
        });
        return true;
    }

    public function update($site_id, $id, $inputs)
    {


        DB::transaction(function () use ($site_id, $id, $inputs) {

            $voucher_amount = str_replace(',', '', $inputs['total_debit']) - str_replace(',', '', $inputs['total_credit']);


            $voucher_data = [
                'site_id' => $site_id,
                'user_id' => Auth::user()->id,
                'serial_number' => $inputs['serial_number'],
                'user_name' => $inputs['user_name'],
                'name' =>  $inputs['voucher_name'],
                'voucher_amount' => $voucher_amount,
                'total_debit' => str_replace(',', '', $inputs['total_debit']),
                'total_credit' => str_replace(',', '', $inputs['total_credit']),
                'status' => 'pending',
                'remarks' => $inputs['remarks'],
                'voucher_date' => $inputs['created_date'],
                'created_date' => $inputs['created_date'],
            ];

            $journal_voucher = $this->model()->find($id)->update[$voucher_data];

            $voucher_entires = $inputs['journal-voucher-entries'];

            for ($i = 0; $i < count($voucher_entires); $i++) {

                $account_head = AccountHead::where('code', $voucher_entires[$i]['account_number'])->first();

                $journal_voucher_entry =  JournalVoucherEntry::updateOrCreate([
                    'id' => $journal_voucher->id,
                ]);

                $journal_voucher_entry->site_id = $site_id;
                $journal_voucher_entry->user_id = Auth::user()->id;
                $journal_voucher_entry->serial_number = $inputs['serial_number'];
                $journal_voucher_entry->journal_voucher_id = $journal_voucher->id;
                $journal_voucher_entry->account_head_code = $account_head->code;
                $journal_voucher_entry->account_number = $voucher_entires[$i]['account_number'];
                $journal_voucher_entry->created_date = $voucher_entires[$i]['voucher_date'];
                $journal_voucher_entry->debit = $voucher_entires[$i]['debit'];
                $journal_voucher_entry->credit = $voucher_entires[$i]['credit'];
                $journal_voucher_entry->remarks = $voucher_entires[$i]['remarks'];
                $journal_voucher_entry->status = 'pending';
                $journal_voucher_entry->total_debit = str_replace(',', '', $inputs['total_debit']);
                $journal_voucher_entry->total_credit = str_replace(',', '', $inputs['total_credit']);
                $journal_voucher_entry->total_amount = $voucher_amount;

                $journal_voucher_entry->save();
            }
        });
        return true;
    }

    public function destroy($site_id, $inputs)
    {
    }
}
