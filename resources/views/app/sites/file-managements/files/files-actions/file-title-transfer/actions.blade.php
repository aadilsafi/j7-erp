<div class="d-flex justify-content-cetner align-items-center ">
    @if (Route::current()->getName() == 'sites.file-managements.file-title-transfer.index')
        @can('sites.file-managements.file-title-transfer.approve')
            @php
                $checkFileTitleTransfer = DB::table('file_title_transfers')
                    ->where('unit_id', $unit_id)
                    ->where('stakeholder_id', $customer_id)
                    ->first();
            @endphp
            @if (isset($checkFileTitleTransfer) && $file_refund_status == 0)
                <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light text-center" style="margin: 5px"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Approve request" href="{{ route('sites.file-managements.file-title-transfer.approve', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id) , 'file_title_transfer_id' =>encryptParams($file_refund_id)  ]) }}">
                    Approve Request
                </a>
            @else
                 -
            @endif
        @endcan
    @endif
</div>
