<div class="d-flex justify-content-cetner align-items-center ">
    @if (Route::current()->getName() == 'sites.file-managements.file-refund.index')
        @can('sites.file-managements.file-refund.approve')
            @php
                $checkFileRefund = DB::table('file_refunds')
                    ->where('unit_id', $unit_id)
                    ->where('stakeholder_id', $customer_id)
                    ->first();
            @endphp
            @if (isset($checkFileRefund))
                <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light text-center" style="margin: 5px"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Approve request" href="#">
                    Approve Request
                </a>
            @else
                 -
            @endif
        @endcan
    @endif
</div>
