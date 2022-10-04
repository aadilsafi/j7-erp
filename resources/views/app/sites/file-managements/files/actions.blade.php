<div class="d-flex justify-content-cetner align-items-center text-nowrap">
    @can('sites.file-managements.view-files')
        @if (Route::current()->getName() == 'sites.file-managements.view-files')
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="View File Details"
                href="{{ route('sites.file-managements.customers.units.files.index', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id)]) }}">
                <i class="bi bi-file-person-fill" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @else
        @endif
        {{-- @if (Route::current()->getName() == 'sites.file-managements.customers.units.files.create')
            <span class="badge badge-glow bg-success">File Already Exist</span>
        @endif --}}
    @endcan
    @if (Route::current()->getName() == 'sites.file-managements.file-refund.index')
        @php
            $checkFileRefund = DB::table('file_refunds')
                ->where('unit_id', $unit_id)
                ->where('stakeholder_id', $customer_id)
                ->first();
        @endphp
        @if (isset($checkFileRefund))
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="View File Refund"
                href="{{ route('sites.file-managements.file-refund.preview', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_refund_id' => encryptParams($checkFileRefund->id)]) }}">
                <i class="bi bi-view-stacked" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @else
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Create File Refund"
                href="{{ route('sites.file-managements.file-refund.create', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id)]) }}">
                <i class="bi bi-folder-plus" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endif

    @endif

</div>
