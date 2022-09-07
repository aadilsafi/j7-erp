@can('sites.floors.units.sales-plans.templates.print')
    <a href="javascript:void(0);" class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1"
        style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Sales Plan"
        onclick="openTemplatesModal('{{ encryptParams($id) }}');">
        <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
    </a>
@endcan
