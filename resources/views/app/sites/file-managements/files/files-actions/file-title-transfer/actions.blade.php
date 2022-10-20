<div class="d-flex justify-content-cetner align-items-center ">
    @if (Route::current()->getName() == 'sites.file-managements.file-title-transfer.index')
        @can('sites.file-managements.file-title-transfer.approve')
            @php
                $checkFileTitleTransfer = DB::table('file_title_transfers')
                    ->where('unit_id', $unit_id)
                    ->where('stakeholder_id', $customer_id)
                    ->first();
            @endphp
            @if (isset($checkFileTitleTransfer) && $file_titleTransfer_status == true)
                <a  onclick="ApproveModal('{{ encryptParams($site_id) }}','{{ encryptParams($customer_id) }}','{{ encryptParams($unit_id) }}','{{ encryptParams( $file_id) }}')" class="btn btn-relief-outline-primary waves-effect waves-float waves-light text-center" style="margin: 5px"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Approve request" href="#">
                    Approve Request
                </a>
            @else
                 -
            @endif
        @endcan
    @endif
</div>
