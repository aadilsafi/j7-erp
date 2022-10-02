<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.file-managements.customers.units.files.index')
        <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top"
            @if (isset($file)) title="View Files"
            @else
            title="Create Files" @endif
            href="{{ route('sites.file-managements.customers.units.files.index', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id)]) }}">

            @if (isset($file))
                <i class="bi bi-file-person-fill" style="font-size: 1.1rem" class="m-10"></i>
            @else
                <i class="bi bi-folder-plus" style="font-size: 1.1rem" class="m-10"></i>
            @endif
        </a>
    @endcan
</div>
