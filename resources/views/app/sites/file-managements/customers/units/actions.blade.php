<div class="d-flex justify-content-cetner align-items-center">

    @if (isset($isShowFileCreationButton) && $isShowFileCreationButton)
        @can('sites.file-managements.customers.units.files.index')
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Create Files"
                href="{{ route('sites.file-managements.customers.units.files.index', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id)]) }}">
                <i class="bi bi-folder-plus" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endcan
    @else
        @can('sites.file-managements.customers.units.files.show')
            <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="View File"
                href="{{ route('sites.file-managements.customers.units.files.show', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_id' => encryptParams($file_id)]) }}">
                <i class="bi bi-file-person-fill" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endcan

        {{-- <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px" 
                data-bs-toggle="tooltip" data-bs-placement="top" title="Print File"
                href="{{ route('sites.file-managements.customers.units.files.print', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id), 'file_id' => encryptParams($file_id)]) }}">
                <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
            </a> --}}
    @endif

</div>
