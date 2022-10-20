<div class="d-flex justify-content-cetner align-items-center ">
    @if (Route::current()->getName() == 'sites.file-managements.file-resale.index')
        @can('sites.file-managements.file-resale.approve')
            @php
                $checkFileResale = DB::table('file_resales')
                    ->where('file_id', $file_id)
                    ->first();
            @endphp
            @if (isset($checkFileResale) && $file_refund_status == 0)
                <a onclick="ApproveModal('{{ encryptParams($site_id) }}','{{ encryptParams($customer_id) }}','{{ encryptParams($unit_id) }}','{{ encryptParams( $file_id) }}')"
                    class="btn btn-relief-outline-primary waves-effect waves-float waves-light text-center"
                    style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve request" href="#">
                    Approve Request
                </a>
            @else
                -
            @endif
        @endcan
    @endif
</div>
