<a class="btn btn-relief-outline-warning waves-effect waves-float waves-light me-1" style="margin: 5px"
    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Floor"
    href="{{ route('sites.floors.units.edit', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($floor_id), 'id' => encryptParams($id)]) }}">
    <i class="bi bi-pencil" style="font-size: 1.1rem" class="m-10"></i>
</a>

@if ($status == 1)
    <a class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Sales Plan"
        href="{{ route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($floor_id), 'unit_id' => encryptParams($id)]) }}">
        <i class="bi bi-receipt" style="font-size: 1.1rem" class="m-10"></i>
    </a>
@endif
