<div class="d-flex justify-content-cetner align-items-center">

    @can('sites.floors.units.sales-plans.templates.print')
        <a href="javascript:void(0);" class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1"
            style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Sales Plan {{ $template_id }}"
            onclick="printSalesPlanTemplate('{{ encryptparams($template_id) }}','{{ encryptparams($id) }}')">
            <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

    @if ($status == 0)
        @can('sites.floors.units.sales-plans.approve-sales-plan')
            <a id="approveSalesPlan" approveSalesPlanId='{{ $id }}'
                class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Sales Plan" href="#"
                onclick="approveSalesPlan({{ $id }}, '{{ $created_date }}','{{ $unit_status }}')">
                <i class="bi bi-check" style="font-size: 1.1rem" class="m-10"></i>
            </a>
        @endcan

        @can('sites.floors.units.sales-plans.disapprove-sales-plan')
            <a id="disapproveSalesPlan" disapproveSalesPlanId='{{ $id }}'
                class="btn btn-relief-outline-danger waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Disapprove Sales Plan" href="#"
                onclick="disapproveSalesPlan({{ $id }})">
                <i class="bi bi-x-octagon-fill"></i>
            </a>
        @endcan
    @endif


    @if ($status == 1 && $unit_status < 4)
        @can('sites.floors.units.sales-plans.disapprove-sales-plan')
            <a id="disapproveSalesPlan" disapproveSalesPlanId='{{ $id }}'
                class="btn btn-relief-outline-danger waves-effect waves-float waves-light me-1" style="margin: 5px"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel Sales Plan" href="#"
                onclick="disapproveSalesPlan({{ $id }})">
                <i class="bi bi-x-octagon-fill"></i>
            </a>
        @endcan
    @endif
    @php
        $initialLink = route('sites.floors.units.sales-plans.initail-sales-plan', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams(1), 'unit_id' => encryptParams(1), 'id' => encryptParams($sales_plan_id)]);
        $updatedLink = route('sites.floors.units.sales-plans.updated-sales-plan', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams(1), 'unit_id' => encryptParams(1), 'id' => encryptParams($sales_plan_id)]);
    @endphp


    @canany('sites.floors.units.sales-plans.initail-sales-plan', 'sites.floors.units.sales-plans.updated-sales-plan')
        <a id="selectPreview" data-id="{{ $id }}"
            @if ($status != 1) href="{{ $initialLink }}"
            @else
            onclick="selectPreview({{ $id }}, '{{ $initialLink }}','{{ $updatedLink }}')" @endif
            class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1" style="margin: 5px"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Preview Sales Plan">
            <i class="bi bi-eye-fill" style="color:blue"></i>
        </a>
    @endcanany

    @can('sites.floors.units.sales-plans.show')
        <a href="javascript:void(0);" class="btn btn-relief-outline-primary waves-effect waves-float waves-light me-1"
            style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Sales Plan"
            onclick="openTemplatesModal('{{ encryptParams($id) }}');">
            <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
        </a>
    @endcan

</div>
