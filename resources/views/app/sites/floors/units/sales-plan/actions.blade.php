<div class="d-flex justify-content-cetner align-items-center">

    @can('sites.floors.units.sales-plans.templates.print')
        <a href="javascript:void(0);" class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1"
            style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Sales Plan"
            onclick="openTemplatesModal('{{ encryptParams($id) }}');">
            <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

    @can('sites.floors.units.sales-plans.approve-sales-plan')
        @if ($status == 0)
            <a id="approveSalesPlan" approveSalesPlanId = '{{ $id }}' class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Approve Sales Plan"
                href="#" onclick="approveSalesPlan({{ $id }})">
                <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endif
    @endcan

    {{-- @can('sites.floors.units.sales-plans.receipts.index')
        @if ($status == 1)
            <a id="approveSalesPlan" approveSalesPlanId = '{{ $id }}' class="btn btn-relief-outline-info waves-effect waves-float waves-light me-1" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Receipts Against Sales Plan"
                href="{{ route('sites.floors.units.sales-plans.receipts.index', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($id) , 'unit_id' => encryptParams($unit_id) ,'sales_plan_id' => encryptParams($id) ]) }}" >
                <i class="bi bi-receipt-cutoff"></i>
            </a>
        @endif
    @endcan --}}

    @can('sites.floors.units.sales-plans.disapprove-sales-plan')
        <a id="disapproveSalesPlan" disapproveSalesPlanId = '{{ $id }}' class="btn btn-relief-outline-danger waves-effect waves-float waves-light me-1" style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top"
            title="Disapprove Sales Plan"
            href="#" onclick="disapproveSalesPlan({{ $id }})">
            <i class="bi bi-x-octagon-fill"></i>
        </a>
    @endcan

</div>
