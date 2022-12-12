<div class="d-flex justify-content-cetner align-items-center">
    @can('sites.floors.floor-plan')
        <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Floor Plan"
            href="{{ route('sites.floors.floor-plan', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}">
            <i class="bi bi-plus" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

    @can('sites.floors.edit')
        <a class="btn btn-relief-outline-warning waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Floor"
            href="{{ route('sites.floors.edit', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}">
            <i class="bi bi-pencil" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

    @can('sites.floors.units.index')
        <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Floor Units"
            href="{{ route('sites.floors.units.index', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($id)]) }}">
            <i class="bi bi-door-open" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

</div>
