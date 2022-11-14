<div class="d-flex justify-content-cetner align-items-center ">
    @if (Route::current()->getName() == 'sites.file-managements.file-title-transfer.index')
        @can('sites.file-managements.file-title-transfer.approve')
            @php
                $checkFileTitleTransfer = DB::table('file_title_transfers')
                    ->where('id', $file_title_transfer_id)
                    ->first();
            @endphp
            @if (isset($checkFileTitleTransfer) && $checkFileTitleTransfer->status == false)
                <a  onclick="ApproveModal('{{ encryptParams($site_id) }}','{{ encryptParams($customer_id) }}','{{ encryptParams($unit_id) }}','{{ encryptParams( $file_title_transfer_id) }}')" class="btn btn-relief-outline-primary waves-effect waves-float waves-light text-center" style="margin: 5px"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Approve request" href="#">
                    Approve Request
                </a>
            @else
                 -
            @endif
        @endcan
    @endif
</div>
