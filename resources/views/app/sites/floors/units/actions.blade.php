<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.floors.units.edit')
        <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Unit"
            href="{{ route('sites.floors.units.edit', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($floor_id), 'id' => encryptParams($id)]) }}">
            <i class="bi bi-pencil" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan
    @can('sites.floors.units.sales-plans.index')
        <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="View Sales Plan"
            href="{{ route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($floor_id), 'unit_id' => encryptParams($id)]) }}">
            <i class="bi bi-receipt" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan
</div>
