<div class="d-flex justify-content-cetner align-items-center">

    @if ($status == 0)
        <a id="approveSalesPlan" approveSalesPlanId = '{{ $id }}' class="btn btn-relief-outline-success waves-effect waves-float waves-light" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
            title="Approve Sales Plan"
            href="#" onclick="approveSalesPlan({{ $id }})">
            <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endif

    @can('sites.floors.units.sales-plans.templates.print')
        <a href="javascript:void(0);" class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1"
            style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Sales Plan"
            onclick="openTemplatesModal('{{ encryptParams($id) }}');">
            <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

</div>
