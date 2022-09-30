<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.file-managements.customers.units.files.index')
        <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="View File Details"
            href="{{ route('sites.file-managements.customers.units.files.index', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id), 'unit_id' => encryptParams($unit_id)]) }}">
            <i class="bi bi-file-person-fill" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan
</div>
