<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.file-managements.customers.units')
        <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
            title="Units"
            href="{{ route('sites.file-managements.customers.units', ['site_id' => encryptParams($site_id), 'customer_id' => encryptParams($customer_id)]) }}">
            <i class="bi bi-door-open" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan
</div>
