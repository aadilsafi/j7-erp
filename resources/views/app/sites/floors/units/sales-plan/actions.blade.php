<div class="d-flex justify-content-cetner align-items-center">

    @can('sites.floors.units.sales-plans.templates.print')
        <a href="javascript:void(0);" class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1"
            style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Sales Plan"
            onclick="openTemplatesModal('{{ encryptParams($id) }}');">
            <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

    @if ($status == 0)
        @can('sites.floors.units.sales-plans.approve-sales-plan')
                <a id="approveSalesPlan" approveSalesPlanId = '{{ $id }}' class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Approve Sales Plan"
                    href="#" onclick="approveSalesPlan({{ $id }})">
                    <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
                </a>
        @endcan

        @can('sites.floors.units.sales-plans.disapprove-sales-plan')

                <a id="disapproveSalesPlan" disapproveSalesPlanId = '{{ $id }}' class="btn btn-relief-outline-danger waves-effect waves-float waves-light me-1" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Disapprove Sales Plan"
                    href="#" onclick="disapproveSalesPlan({{ $id }})">
                    <i class="bi bi-x-octagon-fill"></i>
                </a>
        @endcan
    @endif
</div>
